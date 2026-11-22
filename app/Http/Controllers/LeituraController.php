<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use Illuminate\Http\Request;

class LeituraController extends Controller
{
    public function index()
    {
        return view('leitura');
    }

    public function buscar(Request $request)
    {
        $request->validate(['codigo' => 'required|string|max:500']);

        $codigo = trim($request->codigo);

        // Se o QR trouxer uma URL, usa o último trecho dela (ex.: .../leitura/1 -> 1)
        if (filter_var($codigo, FILTER_VALIDATE_URL)) {
            $caminho = parse_url($codigo, PHP_URL_PATH) ?? '';
            $codigo = trim(basename($caminho));
        }

        $ativo = Ativo::where('patrimonio', $codigo)
            ->when(ctype_digit($codigo), fn ($q) => $q->orWhere('id', $codigo))
            ->first();

        if (!$ativo) {
            return response()->json(['erro' => 'Nenhum ativo encontrado para este código.'], 404);
        }

        return response()->json([
            'id'           => $ativo->id,
            'nome'         => $ativo->nome,
            'patrimonio'   => $ativo->patrimonio,
            'numero_serie' => $ativo->numero_serie ?: 'Não informado',
            'qr_code'      => $ativo->qr_code ? 'Gerado' : 'Não gerado',
            'url'          => route('movimentar.create', $ativo->id),
        ]);
    }
}