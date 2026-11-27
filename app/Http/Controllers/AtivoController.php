<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Categoria;
use App\Models\Historico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AtivoController extends Controller
{
    // ABRIR TELA DE CADASTRO
    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        $locais = \App\Models\Local::orderBy('nome')->get();

        return view('cadastro', compact('categorias', 'locais'));
    }

    // LISTAR EQUIPAMENTOS
    public function index(Request $request)
    {
        $consulta = Ativo::with(['categoria', 'local', 'usuario'])
            ->orderBy('nome');

        if ($request->filled('busca')) {
            $busca = $request->busca;

            $consulta->where(function ($query) use ($busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('patrimonio', 'like', "%{$busca}%")
                    ->orWhere('numero_serie', 'like', "%{$busca}%")
                    ->orWhereHas('usuario', function ($usuario) use ($busca) {
                        $usuario->where('name', 'like', "%{$busca}%");
                    });
            });
        }

        $limiteAtualizacao = now()->subHours(72);

        if ($request->status === 'sem_localizacao') {
            $consulta->whereNull('local_id');

        } elseif ($request->status === 'atualizacao') {
            $consulta->whereNotNull('local_id')
                ->where('updated_at', '<=', $limiteAtualizacao);

        } elseif ($request->status === 'localizado') {
            $consulta->whereNotNull('local_id')
                ->where('updated_at', '>', $limiteAtualizacao);
        }

        $ativos = $consulta->get();

        if ($request->expectsJson()) {
            return response()->json($ativos);
        }

        return view('ativos', compact('ativos', 'limiteAtualizacao'));
    }

    // CADASTRAR EQUIPAMENTO E GERAR QR CODE
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

        $url = route('leitura.qrcode', ['id' => $ativo->id]);

        $qrCode = QrCode::format('svg')
            ->size(300)
            ->generate($url);

        $caminhoQrCode = 'qrcodes/ativo-' . $ativo->id . '.svg';

        Storage::disk('public')->put($caminhoQrCode, $qrCode);

        $ativo->update([
            'qr_code' => $caminhoQrCode
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'mensagem' => 'Equipamento cadastrado com sucesso!',
                'ativo' => $ativo->fresh()
            ], 201);
        }

        return redirect()->route('ativos.create')
            ->with('sucesso', 'Equipamento cadastrado com sucesso!')
           ->with('qrCode', 'data:image/svg+xml;base64,' . base64_encode($qrCode))
            ->with('codigoEtiqueta', $ativo->patrimonio);
    }

    // EXIBIR DETALHES DO ATIVO
    public function show($id)
    {
        $ativo = Ativo::with([
            'categoria',
            'local',
            'usuario'
        ])->findOrFail($id);

        $historicos = $ativo->historicos()
            ->with(['localNovo', 'usuario'])
            ->orderByDesc('data_movimentacao')
            ->take(3)
            ->get();

        return view('detalhes', compact('ativo', 'historicos'));
    }

    // ATUALIZAR EQUIPAMENTO
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

    
    // EXCLUIR EQUIPAMENTO
    public function destroy(Request $request, $id)
    {
        $ativo = Ativo::findOrFail($id);

        // Exclui o arquivo do QR Code, se existir
        if ($ativo->qr_code) {
            Storage::disk('public')->delete($ativo->qr_code);
        }

        // Os históricos vinculados serão excluídos em cascata
        $ativo->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'mensagem' => 'Equipamento excluído com sucesso!'
            ]);
        }

        return redirect()->route('ativos.index')
            ->with('sucesso', 'Ativo excluído com sucesso!');
    }

}
