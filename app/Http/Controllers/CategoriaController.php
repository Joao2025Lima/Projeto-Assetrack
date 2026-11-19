<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Listar categorias
    public function index()
    {
        $categorias = Categoria::orderBy('nome')->get();

        return response()->json($categorias);
    }

    // Cadastrar categoria
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        $categoria = Categoria::create($dados);

        return response()->json([
            'mensagem' => 'Categoria cadastrada com sucesso!',
            'categoria' => $categoria
        ], 201);
    }

    // Exibir uma categoria
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);

        return response()->json($categoria);
    }

    // Atualizar categoria
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        $categoria->update($dados);

        return response()->json([
            'mensagem' => 'Categoria atualizada com sucesso!',
            'categoria' => $categoria
        ]);
    }

    // Excluir categoria
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        if ($categoria->ativos()->exists()) {
            return response()->json([
                'mensagem' => 'Não é possível excluir uma categoria que possui equipamentos cadastrados.'
            ], 422);
        }

        $categoria->delete();

        return response()->json([
            'mensagem' => 'Categoria excluída com sucesso!'
        ]);
    }
}
