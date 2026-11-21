<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Historico;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimentacaoController extends Controller
{
    public function create(Ativo $ativo)
    {
        $locais = Local::orderBy('nome')->get();

        return view('movimentar', compact('ativo', 'locais'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'ativo_id'      => 'required|exists:ativos,id',
            'local_novo_id' => 'required|exists:locals,id',
            'descricao'     => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($dados) {
            $ativo = Ativo::findOrFail($dados['ativo_id']);

            Historico::create([
                'ativo_id'          => $ativo->id,
                'local_anterior_id' => $ativo->local_id,
                'local_novo_id'     => $dados['local_novo_id'],
                'user_id'           => auth()->id(),
                'tipo'              => 'movimentacao',
                'descricao'         => $dados['descricao'] ?? null,
                'data_movimentacao' => now(),
            ]);

            $ativo->update(['local_id' => $dados['local_novo_id']]);
        });

        return redirect('/')->with('sucesso', 'Movimentação registrada!');
    }
}