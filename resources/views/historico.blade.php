<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AsseTrack - Histórico</title>
    <link rel="stylesheet" href="{{ asset('css/ativos.css') }}">
    <style>
        .pagina-historico { display: flex; min-height: 100vh; background: #f4f7fb; }
        .pagina-historico .menu-lateral { position: sticky; top: 0; height: 100vh; flex-shrink: 0; }
        .pagina-historico .conteudo-hist { flex: 1; padding: 30px; min-width: 0; font-family: Arial, sans-serif; }
        .conteudo-hist h1 { margin: 0 0 4px; font-size: 28px; color: #0f1b2d; }
        .conteudo-hist .sub { color: #7a8aa0; font-size: 12px; letter-spacing: .08em; text-transform: uppercase; }
        .cards-hist { display: flex; gap: 16px; margin: 24px 0; flex-wrap: wrap; }
        .card-hist { flex: 1; min-width: 180px; background: #fff; border-radius: 14px; padding: 18px 20px; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .card-hist span { color: #5b6b82; font-size: 14px; }
        .card-hist strong { display: block; font-size: 34px; margin-top: 6px; color: #0f1b2d; }
        .caixa-hist { background: #fff; border-radius: 14px; padding: 22px; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .filtros-hist { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 18px; }
        .filtros-hist select, .filtros-hist input { padding: 9px 10px; border: 1px solid #ccd5e3; border-radius: 8px; font-size: 14px; }
        .filtros-hist button, .filtros-hist a { padding: 9px 16px; border-radius: 8px; border: 1px solid #ccd5e3; background: #fff; cursor: pointer; font-size: 14px; text-decoration: none; color: #0f1b2d; }
        .filtros-hist button { background: #0b5ed7; color: #fff; border-color: #0b5ed7; }
        table.tab-hist { width: 100%; border-collapse: collapse; }
        .tab-hist th { text-align: left; padding: 12px 10px; background: #f4f7fb; color: #5b6b82; font-size: 13px; }
        .tab-hist td { padding: 14px 10px; border-bottom: 1px solid #edf1f7; font-size: 14px; color: #0f1b2d; }
        .tab-hist small { color: #7a8aa0; }
        .vazio-hist { text-align: center; color: #7a8aa0; padding: 30px; }
        .pag-hist { margin-top: 16px; font-size: 14px; }
        .pag-hist a { color: #0b5ed7; text-decoration: none; }
    </style>
</head>
<body>
<div class="pagina-historico">

    @include('partials.sidebar')

    <main class="conteudo-hist">
        <div class="sub">Gestão de equipamentos</div>
        <h1>Histórico</h1>

        <div class="cards-hist">
            <div class="card-hist"><span>Movimentações hoje</span><strong>{{ $movimentosHoje }}</strong></div>
            <div class="card-hist"><span>Movimentações no mês</span><strong>{{ $movimentosMes }}</strong></div>
            <div class="card-hist"><span>Total de registros</span><strong>{{ $totalMovimentos }}</strong></div>
        </div>

        <div class="caixa-hist">
            <h3 style="margin-top:0">Histórico de movimentações</h3>

            <form method="GET" action="{{ route('historicos.index') }}" class="filtros-hist">
                <select name="ativo_id">
                    <option value="">Todos os ativos</option>
                    @foreach ($ativos as $a)
                        <option value="{{ $a->id }}" @selected(request('ativo_id') == $a->id)>{{ $a->nome }}</option>
                    @endforeach
                </select>

                <select name="local_id">
                    <option value="">Todos os locais</option>
                    @foreach ($locais as $l)
                        <option value="{{ $l->id }}" @selected(request('local_id') == $l->id)>{{ $l->nome }}</option>
                    @endforeach
                </select>

                <input type="date" name="data" value="{{ request('data') }}">

                <button type="submit">Filtrar</button>
                <a href="{{ route('historicos.index') }}">Limpar</a>
            </form>

            <table class="tab-hist">
                <thead>
                    <tr>
                        <th>Ativo</th>
                        <th>Movimentação</th>
                        <th>Usuário</th>
                        <th>Observação</th>
                        <th>Data/hora</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($historicos as $h)
                    <tr>
                        <td>{{ $h->ativo?->nome ?? 'Ativo removido' }}<br><small>{{ $h->ativo?->patrimonio }}</small></td>
                        <td>{{ $h->localAnterior?->nome ?? 'Sem local' }} → {{ $h->localNovo?->nome ?? 'Sem local' }}</td>
                        <td>{{ $h->usuario?->name ?? 'Usuário removido' }}</td>
                        <td>{{ $h->descricao }}</td>
                        <td>{{ $h->data_movimentacao->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="vazio-hist">Nenhuma movimentação encontrada.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="pag-hist">
                @if ($historicos->hasPages())
                    @if ($historicos->onFirstPage())
                        <span style="color:#aab4c3">← Anterior</span>
                    @else
                        <a href="{{ $historicos->previousPageUrl() }}">← Anterior</a>
                    @endif

                    <span style="margin:0 12px">Página {{ $historicos->currentPage() }} de {{ $historicos->lastPage() }}</span>

                    @if ($historicos->hasMorePages())
                        <a href="{{ $historicos->nextPageUrl() }}">Próxima →</a>
                    @else
                        <span style="color:#aab4c3">Próxima →</span>
                    @endif
                @endif
            </div>
        </div>
    </main>

</div>
</body>