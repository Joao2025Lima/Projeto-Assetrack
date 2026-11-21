<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    // Listar locais
    public function index()
    {
        $locais = Local::orderBy('nome')->get();

        return response()->json($locais);
    }

    // Cadastrar local
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'setor' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        $local = Local::create($dados);

        return response()->json([
            'mensagem' => 'Local cadastrado com sucesso!',
            'local' => $local
        ], 201);
    }

    // Exibir um local
    public function show($id)
    {
        $local = Local::findOrFail($id);

        return response()->json($local);
    }

    // Atualizar local
    public function update(Request $request, $id)
    {
        $local = Local::findOrFail($id);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'setor' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        $local->update($dados);

        return response()->json([
            'mensagem' => 'Local atualizado com sucesso!',
            'local' => $local
        ]);
    }

    // Excluir local
    public function destroy($id)
    {
        $local = Local::findOrFail($id);

        if ($local->ativos()->exists()) {
            return response()->json([
                'mensagem' => 'Não é possível excluir um local que possui equipamentos cadastrados.'
            ], 422);
        }

        $local->delete();

        return response()->json([
            'mensagem' => 'Local excluído com sucesso!'
        ]);
    }
}
