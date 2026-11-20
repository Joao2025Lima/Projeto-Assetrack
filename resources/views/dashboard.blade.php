
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

    <!-- MENU LATERAL -->
    <nav class="menu-lateral">

        <div class="logo">
            <div class="logo-icone">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <circle cx="12" cy="12" r="5"/>
                    <circle cx="12" cy="12" r="1"/>
                    <path d="M12 3v3M21 12h-3M12 21v-3M3 12h3"/>
                </svg>
            </div>
            <div>
                <h2>AsseTrack</h2>
                <p>Gestão de ativos</p>
            </div>
        </div>

        <ul class="lista-menu">

            <li>
                <a href="{{ route('login') }}">
                    <svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4M12 15v3"/></svg>
                    Login
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard') }}" class="ativo">
                    <svg viewBox="0 0 24 24"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="14" rx="2"/><path d="M8 9h8M8 13h8M8 16h4"/></svg>
                    Lista de ativos
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 12 0v2M19 8v8M15 12h8"/></svg>
                    Cadastro de ativo
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24"><path d="M4 5h16v14H4zM8 9h8M8 13h5M8 16h3"/></svg>
                    Detalhes do ativo
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2v2h-2zM18 14h2v6h-2zM14 18h2v2h-2z"/></svg>
                    Leitura de QR Code
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-7.2 8-13a8 8 0 1 0-16 0c0 5.8 8 13 8 13z"/><circle cx="12" cy="9" r="2.5"/></svg>
                    Registro por GPS
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"/></svg>
                    Mapa de ativos
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 2.6-6.4L3 8"/><path d="M3 3v5h5M12 7v5l3 2"/></svg>
                    Histórico
                </a>
            </li>

        </ul>

        <div class="informacao-menu">
            <div class="info-titulo">
                <svg viewBox="0 0 24 24"><path d="M4 10a12 12 0 0 1 16 0M7 13a7 7 0 0 1 10 0M10 16a3 3 0 0 1 4 0"/><path d="M12 20h.01"/></svg>
                <strong>QR + GPS ativo</strong>
            </div>
            <p>Registros móveis sincronizados com mapa corporativo em tempo real</p>
        </div>

    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
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

                <div class="usuario">
                    <div class="icone-usuario">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="3"/>
                            <path d="M5 20a7 7 0 0 1 14 0"/>
                        </svg>
                    </div>
                    <strong>{{ Auth::user()->name }}</strong>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="botao-icone" type="submit" title="Sair do sistema">
                        Sair
                    </button>
                </form>

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
                    <svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8v8H8zM10 11h4M10 13h4"/></svg>
                </div>
            </div>

            <div class="card">
                <div class="card-conteudo">
                    <p>Ativos localizados</p>
                    <h2>{{ $ativosLocalizados }}</h2>
                    <span>{{ $percentualLocalizados }}% dos ativos</span>
                </div>
                <div class="icone-card verde">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>
                </div>
            </div>

            <div class="card">
                <div class="card-conteudo">
                    <p>Sem atualização</p>
                    <h2>{{ $ativosSemAtualizacao }}</h2>
                    <span>Há mais de 72 horas</span>
                </div>
                <div class="icone-card amarelo">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </div>
            </div>

            <div class="card">
                <div class="card-conteudo">
                    <p>Sem localização</p>
                    <h2>{{ $ativosSemLocalizacao }}</h2>
                    <span>Exigem atualização</span>
                </div>
                <div class="icone-card vermelho">
                    <svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z"/><circle cx="12" cy="10" r="2.5"/></svg>
                </div>
            </div>

        </section>

        <!-- GRÁFICO E MAPA -->
        <section class="area-inferior">

            <div class="painel painel-categorias">

                <div class="painel-cabecalho">
                    <div>
                        <h3>Ativos por categoria</h3>
                        <p>Distribuição operacional cadastrada</p>
                    </div>
                    <a href="#" class="botao-ver-lista">
                        Ver lista
                        <span>›</span>
                    </a>
                </div>

                @forelse ($categorias as $categoria)

                    <div class="categoria">
                        <div class="categoria-info">
                            <span>{{ $categoria->nome }}</span>
                            <span>{{ $categoria->total }} ativos</span>
                        </div>

                        <div class="barra">
                            <div class="progresso"
                                 style="width: {{ ($categoria->total / $maiorQuantidade) * 100 }}%;">
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
                        <p>Visualização ilustrativa de posições</p>
                    </div>
                    <a href="#" class="botao-mapa" aria-label="Abrir mapa">↗</a>
                </div>

                <div class="mapa-simulado">

                    <div class="area-agua agua-um"></div>
                    <div class="area-agua agua-dois"></div>
                    <div class="rua rua-um"></div>
                    <div class="rua rua-dois"></div>
                    <div class="rua rua-tres"></div>

                    <div class="marcador marcador1">
                        <div class="pino azul-pino">
                            <svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        </div>
                        <strong>PAT-2048</strong>
                    </div>

                    <div class="marcador marcador2">
                        <div class="pino verde-pino">
                            <svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        </div>
                        <strong>PAT-1784</strong>
                    </div>

                    <div class="marcador marcador3">
                        <div class="pino amarelo-pino">
                            <svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        </div>
                        <strong>PAT-4190</strong>
                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>
