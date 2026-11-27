<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Historico;
use App\Models\Local;

class MapaController extends Controller
{
    public function index()
    {
        return view('mapa', [
            'dados' => $this->montarDados(),
            'configMapa' => [
                'centro' => config('mapa.centro'),
                'zoom' => config('mapa.zoom'),
                'atualizacaoSegundos' => config('mapa.atualizacao_segundos'),
            ],
        ]);
    }

    // JSON consultado pelo mapa a cada alguns segundos
    public function dados()
    {
        return response()->json($this->montarDados());
    }

    private function montarDados(): array
    {
        $limite = now()->subHours(72);

        $ativos = Ativo::with('local')->orderBy('nome')->get();

        // Último registro de movimentação de cada ativo (com o usuário que fez)
        $ultimos = Historico::with('usuario')
            ->whereIn('ativo_id', $ativos->pluck('id'))
            ->orderByDesc('data_movimentacao')
            ->get()
            ->unique('ativo_id')
            ->keyBy('ativo_id');

        $listaAtivos = $ativos->map(function (Ativo $ativo) use ($limite, $ultimos) {
            if (!$ativo->local_id) {
                $status = 'sem-localizacao';
                $texto = 'Sem localização';
            } elseif ($ativo->updated_at && $ativo->updated_at->lte($limite)) {
                $status = 'sem-atualizacao';
                $texto = 'Sem atualização';
            } else {
                $status = 'localizado';
                $texto = 'Localizado';
            }

            $local = $ativo->local;

            return [
                'id' => $ativo->id,
                'nome' => $ativo->nome,
                'patrimonio' => $ativo->patrimonio,
                'status' => $status,
                'status_texto' => $texto,
                'local' => $local ? [
                    'id' => $local->id,
                    'nome' => $local->nome,
                    'lat' => $local->latitude,
                    'lng' => $local->longitude,
                ] : null,
                'ultimo_usuario' => $ultimos->get($ativo->id)?->usuario?->name,
                'atualizado_em' => $ativo->updated_at?->format('d/m/Y H:i'),
                'url' => route('ativos.show', $ativo->id),
            ];
        })->values();

        $locais = Local::orderBy('nome')->get()->map(fn (Local $local) => [
            'id' => $local->id,
            'nome' => $local->nome,
            'lat' => $local->latitude,
            'lng' => $local->longitude,
        ])->values();

        return [
            'ativos' => $listaAtivos,
            'locais' => $locais,
        ];
    }
}