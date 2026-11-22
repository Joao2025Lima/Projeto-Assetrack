
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsseTrack - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

<div class="tela-sistema">

    @include('partials.sidebar')

    <main class="conteudo-principal">

        <header class="cabecalho">

            <div class="titulo-pagina">
                <div class="icone-dashboard">
                    <svg viewBox="0 0 24 24">
                        <rect x="4" y="4" width="7" height="7" rx="1"/>
                        <rect x="13" y="4" width="7" height="7" rx="1"/>
                        <rect x="4" y="13" width="7" height="7" rx="1"/>
                        <rect x="13" y="13" width="7" height="7" rx="1"/>
                    </svg>
                </div>
                <div>
                    <span>PROTÓTIPO EMPRESARIAL</span>
                    <h1>Dashboard</h1>
                </div>
            </div>

            <div class="acoes-cabecalho">

                <a href="{{ route('ativos.index') }}" class="botao-icone" aria-label="Pesquisar">
                    <svg viewBox="0 0 24 24">
                        <circle cx="10.5" cy="10.5" r="6.5"/>
                        <path d="m16 16 4 4"/>
                    </svg>
                </a>

                <button class="botao-icone" type="button" aria-label="Notificações">
                    <svg viewBox="0 0 24 24">
                        <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                    </svg>
                </button>

                <div class="usuario">
                    <div class="icone-usuario">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="3"/>
                            <path d="M5 20a7 7 0 0 1 14 0"/>
                        </svg>
                    </div>
                    <strong>{{ auth()->user()->name }}</strong>
                </div>

            </div>
        </header>

        <!-- INDICADORES -->

        <section class="area-cards">

            <div class="card">
                <div class="card-conteudo">
                    <p>Total de ativos</p>
                    <h2>{{ $totalAtivos }}</h2>
                    <span>Equipamentos cadastrados</span>
                </div>
                <div class="icone-card azul">
                    <svg viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                        <path d="M8 8h8v8H8zM10 11h4M10 13h4"/>
                    </svg>
                </div>
            </div>

            <div class="card">
                <div class="card-conteudo">
                    <p>Ativos localizados</p>
                    <h2>{{ $ativosLocalizados }}</h2>
                    <span>{{ number_format($percentualLocalizados, 0, ',', '.') }}% do total</span>
                </div>
                <div class="icone-card verde">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m8 12 3 3 5-6"/>
                    </svg>
                </div>
            </div>

            <div class="card">
                <div class="card-conteudo">
                    <p>Sem atualização</p>
                    <h2>{{ $ativosSemAtualizacao }}</h2>
                    <span>Acima de 72h</span>
                </div>
                <div class="icone-card amarelo">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </div>
            </div>

            <div class="card">
                <div class="card-conteudo">
                    <p>Sem localização</p>
                    <h2>{{ $ativosSemLocalizacao }}</h2>
                    <span>Exigem leitura</span>
                </div>
                <div class="icone-card vermelho">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                </div>
            </div>

        </section>

        <!-- CATEGORIAS E MAPA -->

        <section class="area-inferior">

            <div class="painel painel-categorias">

                <div class="painel-cabecalho">
                    <div>
                        <h3>Ativos por categoria</h3>
                        <p>Distribuição operacional cadastrada</p>
                    </div>
                    <a href="{{ route('ativos.index') }}" class="botao-ver-lista">
                        Ver lista <span>›</span>
                    </a>
                </div>

                @forelse ($categorias as $categoria)
                    <div class="categoria">
                        <div class="categoria-info">
                            <span>{{ $categoria->nome }}</span>
                            <span>{{ $categoria->total }} ativos</span>
                        </div>
                        <div class="barra">
                            <div
                                class="progresso"
                                style="width: {{ $maiorQuantidade > 0 ? ($categoria->total / $maiorQuantidade) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Nenhuma categoria cadastrada.</p>
                @endforelse

            </div>

            <div class="painel painel-mapa">

                <div class="painel-cabecalho">
                    <div>
                        <h3>Mapa resumido</h3>
                        <p>Visualização ilustrativa</p>
                    </div>
                    <a href="{{ route('ativos.index') }}" class="botao-mapa" aria-label="Abrir mapa">↗</a>
                </div>

                <div class="mapa-simulado">
                    <div class="area-agua agua-um"></div>
                    <div class="area-agua agua-dois"></div>
                    <div class="rua rua-um"></div>
                    <div class="rua rua-dois"></div>
                    <div class="rua rua-tres"></div>

                    <div class="marcador marcador1">
                        <div class="pino azul-pino">
                            <svg viewBox="0 0 24 24">
                                <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                        </div>
                        <strong>AsseTrack</strong>
                    </div>
                </div>

            </div>

        </section>

        <!-- ATIVOS CRÍTICOS -->

        <section class="painel painel-criticos">

            <div class="painel-cabecalho">
                <div>
                    <h3>Ativos críticos</h3>
                    <p>Equipamentos que exigem atenção da equipe</p>
                </div>
                <span class="atualizado">
                    Atualizado em {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>

            <div class="tabela-responsiva">

                <table class="tabela-criticos">

                    <thead>
                        <tr>
                            <th>Ativo</th>
                            <th>Categoria</th>
                            <th>Responsável</th>
                            <th>Status</th>
                            <th>Última localização</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($ativosCriticos as $ativo)
                            <tr>
                                <td>
                                    <strong>{{ $ativo->nome }}</strong>
                                    <small>
                                        {{ $ativo->patrimonio }}
                                        · Série {{ $ativo->numero_serie ?? 'Não informada' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $ativo->categoria?->nome ?? 'Sem categoria' }}
                                </td>

                                <td>
                                    {{ $ativo->usuario?->name ?? 'Não informado' }}
                                </td>

                                <td>
                                    @if ($ativo->local_id === null)
                                        <span class="status sem-localizacao">
                                            Sem localização
                                        </span>
                                    @elseif ($ativo->updated_at && $ativo->updated_at->lte($limiteAtualizacao))
                                        <span class="status sem-atualizacao">
                                            Sem atualização
                                        </span>
                                    @else
                                        <span class="status localizado">
                                            Localizado
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $ativo->local?->nome ?? 'Não registrada' }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5">
                                    Nenhum ativo crítico encontrado.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>
        </section>

    </main>
</div>

</body>
</html>
