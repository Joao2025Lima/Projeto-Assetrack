<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Historico;

class MapaController extends Controller
{
    public function index()
    {
        $ativos = Ativo::with('local')->orderBy('nome')->get();

        // Último registro de movimentação de cada ativo (com o usuário que fez)
        $ultimos = Historico::with('usuario')
            ->whereIn('ativo_id', $ativos->pluck('id'))
            ->orderByDesc('data_movimentacao')
            ->get()
            ->unique('ativo_id')
            ->keyBy('ativo_id');

        return view('mapa', compact('ativos', 'ultimos'));
    }
}