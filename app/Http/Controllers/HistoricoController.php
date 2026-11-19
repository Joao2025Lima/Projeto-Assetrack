<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\Ativo;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    public function index()
    {
        $historicos = Historico::with([
            'ativo',
            'localAnterior',
            'localNovo',
            'usuario'
        ])
        ->orderBy('data_movimentacao', 'desc')
        ->get();

        return response()->json($historicos);
    }

    public function show($id)
    {
        $ativo = Ativo::findOrFail($id);

        $historicos = Historico::with([
            'localAnterior',
            'localNovo',
            'usuario'
        ])
        ->where('ativo_id', $ativo->id)
        ->orderBy('data_movimentacao', 'desc')
        ->get();

        return response()->json([
            'ativo' => $ativo,
            'historicos' => $historicos
        ]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'ativo_id' => ['required', 'exists:ativos,id'],
            'local_novo_id' => ['required', 'exists:locals,id'],
            'descricao' => ['nullable', 'string'],
        ]);

        $ativo = Ativo::findOrFail($dados['ativo_id']);

        $localAnterior = $ativo->local_id;

        $ativo->update([
            'local_id' => $dados['local_novo_id']
        ]);

        $historico = Historico::create([
            'ativo_id' => $ativo->id,
            'local_anterior_id' => $localAnterior,
            'local_novo_id' => $dados['local_novo_id'],
            'user_id' => $request->user()->id,
            'tipo' => 'movimentacao',
            'descricao' => $dados['descricao'] ?? 'Movimentação de equipamento',
            'data_movimentacao' => now(),
        ]);

        return response()->json([
            'mensagem' => 'Movimentação registrada com sucesso!',
            'historico' => $historico
        ], 201);
    }
}
