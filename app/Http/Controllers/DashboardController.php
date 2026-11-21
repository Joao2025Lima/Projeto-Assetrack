<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Indicadores principais
        $totalAtivos = Ativo::count();

        $ativosLocalizados = Ativo::whereNotNull('local_id')->count();

        $ativosSemLocalizacao = Ativo::whereNull('local_id')->count();

        $ativosSemAtualizacao = Ativo::where(
            'updated_at',
            '<=',
            now()->subHours(72)
        )->count();

        // Percentual de ativos localizados
        $percentualLocalizados = $totalAtivos > 0
            ? round(($ativosLocalizados / $totalAtivos) * 100)
            : 0;

        // Quantidade de ativos por categoria
        $categorias = DB::table('categorias')
            ->leftJoin('ativos', 'categorias.id', '=', 'ativos.categoria_id')
            ->select(
                'categorias.id',
                'categorias.nome',
                DB::raw('COUNT(ativos.id) as total')
            )
            ->groupBy('categorias.id', 'categorias.nome')
            ->orderByDesc('total')
            ->get();

        // Maior quantidade para calcular o tamanho das barras
        $maiorQuantidade = $categorias->max('total') ?: 1;

        // Data limite para considerar um ativo sem atualização
        $limiteAtualizacao = now()->subHours(72);

        // Busca os ativos críticos
        // Critérios: sem localização ou sem atualização há mais de 72 horas
        $ativosCriticos = Ativo::with([
                'categoria',
                'local',
                'usuario'
            ])
            ->where(function ($query) use ($limiteAtualizacao) {
                $query->whereNull('local_id')
                    ->orWhere('updated_at', '<=', $limiteAtualizacao);
            })
            ->orderByRaw('CASE WHEN local_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('updated_at')
            ->limit(10)
            ->get();

        // Envia os dados para a tela do Dashboard
        return view('dashboard', compact(
            'totalAtivos',
            'ativosLocalizados',
            'ativosSemLocalizacao',
            'ativosSemAtualizacao',
            'percentualLocalizados',
            'categorias',
            'maiorQuantidade',
            'ativosCriticos',
            'limiteAtualizacao'
        ));
    }
}
