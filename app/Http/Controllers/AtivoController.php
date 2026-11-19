<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Historico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AtivoController extends Controller
{
    public function index()
    {
        $ativos = Ativo::with(['categoria', 'local', 'usuario'])
            ->orderBy('nome')
            ->get();

        return response()->json($ativos);
    }

    // Cadastrar equipamento e gerar QR Code
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'patrimonio' => ['required', 'string', 'max:255', 'unique:ativos,patrimonio'],
            'numero_serie' => ['nullable', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'local_id' => ['nullable', 'exists:locals,id'],
            'observacoes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:ativo,inativo,manutencao'],
        ]);

        $dados['user_id'] = $request->user()->id;
        $dados['status'] = $dados['status'] ?? 'ativo';

        $ativo = DB::transaction(function () use ($dados) {
            $ativo = Ativo::create($dados);

            // Registrar localização inicial no histórico
            if ($ativo->local_id !== null) {
                Historico::create([
                    'ativo_id' => $ativo->id,
                    'local_anterior_id' => null,
                    'local_novo_id' => $ativo->local_id,
                    'user_id' => $dados['user_id'],
                    'tipo' => 'cadastro',
                    'descricao' => 'Localização inicial do equipamento',
                    'data_movimentacao' => now(),
                ]);
            }

            return $ativo;
        });

        // Gerar QR Code com a rota de leitura do equipamento
        $url = route('leitura.qrcode', ['id' => $ativo->id]);

        $qrCode = QrCode::format('svg')
            ->size(300)
            ->generate($url);

        // Salvar QR Code no armazenamento público
        $caminhoQrCode = 'qrcodes/ativo-' . $ativo->id . '.svg';

        Storage::disk('public')->put($caminhoQrCode, $qrCode);

        // Salvar o caminho do QR Code no banco
        $ativo->update([
            'qr_code' => $caminhoQrCode
        ]);

        return response()->json([
            'mensagem' => 'Equipamento cadastrado com sucesso!',
            'ativo' => $ativo->fresh()
        ], 201);
    }

    // Exibir equipamento
    public function show($id)
    {
        $ativo = Ativo::with([
            'categoria',
            'local',
            'usuario',
            'historicos.localAnterior',
            'historicos.localNovo',
            'historicos.usuario'
        ])->findOrFail($id);

        return response()->json($ativo);
    }

    // Atualizar equipamento
    public function update(Request $request, $id)
    {
        $ativo = Ativo::findOrFail($id);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'patrimonio' => [
                'required',
                'string',
                'max:255',
                'unique:ativos,patrimonio,' . $ativo->id
            ],
            'numero_serie' => ['nullable', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'local_id' => ['nullable', 'exists:locals,id'],
            'observacoes' => ['nullable', 'string'],
            'status' => ['required', 'in:ativo,inativo,manutencao'],
        ]);

        DB::transaction(function () use ($ativo, $dados, $request) {

            $localAnterior = $ativo->local_id;
            $novoLocal = $dados['local_id'] ?? null;

            $ativo->update($dados);

            // Registrar histórico somente se o local mudou
            if ($localAnterior != $novoLocal) {
                Historico::create([
                    'ativo_id' => $ativo->id,
                    'local_anterior_id' => $localAnterior,
                    'local_novo_id' => $novoLocal,
                    'user_id' => $request->user()->id,
                    'tipo' => 'movimentacao',
                    'descricao' => 'Localização do equipamento atualizada',
                    'data_movimentacao' => now(),
                ]);
            }
        });

        return response()->json([
            'mensagem' => 'Equipamento atualizado com sucesso!',
            'ativo' => $ativo->fresh(['categoria', 'local', 'usuario'])
        ]);
    }

    // Excluir equipamento
    public function destroy($id)
    {
        $ativo = Ativo::findOrFail($id);

        $ativo->delete();

        return response()->json([
            'mensagem' => 'Equipamento excluído com sucesso!'
        ]);
    }
}
