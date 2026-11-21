
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AsseTrack - Login</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
                <a href="{{ route('login') }}" class="ativo">
                    <svg viewBox="0 0 24 24">
                        <rect x="5" y="11" width="14" height="10" rx="2"/>
                        <path d="M8 11V7a4 4 0 0 1 8 0v4M12 15v3"/>
                    </svg>
                    Login
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard') }}">
                    <svg viewBox="0 0 24 24">
                        <rect x="4" y="4" width="7" height="7" rx="1"/>
                        <rect x="13" y="4" width="7" height="7" rx="1"/>
                        <rect x="4" y="13" width="7" height="7" rx="1"/>
                        <rect x="13" y="13" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24">
                        <rect x="4" y="5" width="16" height="14" rx="2"/>
                        <path d="M8 9h8M8 13h8M8 16h4"/>
                    </svg>
                    Lista de ativos
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24">
                        <circle cx="9" cy="8" r="3"/>
                        <path d="M3 20v-2a6 6 0 0 1 12 0v2M19 8v8M15 12h8"/>
                    </svg>
                    Cadastro de ativo
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 5h16v14H4zM8 9h8M8 13h5M8 16h3"/>
                    </svg>
                    Detalhes do ativo
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2v2h-2zM18 14h2v6h-2zM14 18h2v2h-2z"/>
                    </svg>
                    Leitura de QR Code
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 22s8-7.2 8-13a8 8 0 1 0-16 0c0 5.8 8 13 8 13z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                    Registro por GPS
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24">
                        <path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"/>
                    </svg>
                    Mapa de ativos
                </a>
            </li>

            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 12a9 9 0 1 0 2.6-6.4L3 8"/>
                        <path d="M3 3v5h5M12 7v5l3 2"/>
                    </svg>
                    Histórico
                </a>
            </li>

        </ul>

        <div class="informacao-menu">

            <div class="info-titulo">
                <svg viewBox="0 0 24 24">
                    <path d="M4 10a12 12 0 0 1 16 0M7 13a7 7 0 0 1 10 0M10 16a3 3 0 0 1 4 0"/>
                    <path d="M12 20h.01"/>
                </svg>

                <strong>QR + GPS ativo</strong>
            </div>

            <p>Registros móveis sincronizados com mapa corporativo em tempo real</p>

        </div>

    </nav>

    <!-- CONTEÚDO PRINCIPAL -->

    <main class="conteudo-principal">

        <!-- CABEÇALHO -->

        <header class="cabecalho">

            <div class="titulo-pagina">

                <div class="icone-pagina">
                    <svg viewBox="0 0 24 24">
                        <rect x="5" y="11" width="14" height="10" rx="2"/>
                        <path d="M8 11V7a4 4 0 0 1 8 0v4M12 15v3"/>
                    </svg>
                </div>

                <div>
                    <span class="subtitulo">PROTÓTIPO EMPRESARIAL</span>
                    <h1>Login</h1>
                </div>

            </div>

            <div class="acoes-cabecalho">

                <button class="botao-icone" type="button" aria-label="Pesquisar">
                    <svg viewBox="0 0 24 24">
                        <circle cx="10.5" cy="10.5" r="6.5"/>
                        <path d="m16 16 4 4"/>
                    </svg>
                </button>

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

                    <strong>Guilherme</strong>

                </div>

            </div>

        </header>

        <!-- ÁREA DE LOGIN -->

        <section class="area-login">

            <!-- FORMULÁRIO -->

            <div class="painel-login">

                <div class="marca-login">

                    <div class="logo-login">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/>
                            <circle cx="12" cy="12" r="5"/>
                            <circle cx="12" cy="12" r="1"/>
                            <path d="M12 3v3M21 12h-3M12 21v-3M3 12h3"/>
                        </svg>
                    </div>

                    <div>
                        <h2>AsseTrack</h2>
                        <p>Acesso seguro ao painel corporativo</p>
                    </div>

                </div>

                @if ($errors->any())
                    <div class="mensagem-erro">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form class="formulario-login"
                      action="{{ route('login.post') }}"
                      method="POST">

                    @csrf

                    <div class="campo">
                        <label for="email">E-mail corporativo</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="gestor@empresa.com.br"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="senha">Senha</label>

                        <input
                            type="password"
                            id="senha"
                            name="password"
                            placeholder="Digite sua senha"
                            required
                        >
                    </div>

                    <div class="informacao-login">
                        <span>Autenticação empresarial habilitada</span>
                        <a href="#" onclick="alert('A recuperação de senha será implementada posteriormente.'); return false;">
                            Recuperar senha
                        </a>
                    </div>

                    <button type="submit" class="botao-entrar">
                        <svg viewBox="0 0 24 24">
                            <rect x="5" y="11" width="14" height="10" rx="2"/>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4M12 15v3"/>
                        </svg>

                        Entrar no sistema
                    </button>

                </form>

            </div>

            <!-- PAINEL INFORMATIVO -->

            <div class="painel-informativo">

                <span class="etiqueta">QR Code + GPS</span>

                <h2>
                    Localize<br>
                    equipamentos em<br>
                    segundos, direto pelo<br>
                    celular.
                </h2>

                <div class="recursos">

                    <div class="recurso">

                        <svg viewBox="0 0 24 24">
                            <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2v2h-2zM18 14h2v6h-2zM14 18h2v2h-2z"/>
                        </svg>

                        <h3>Escaneie</h3>
                        <p>Identificação pelo QR do ativo.</p>

                    </div>

                    <div class="recurso">

                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"/>
                            <circle cx="12" cy="12" r="8"/>
                            <path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>
                        </svg>

                        <h3>Registre</h3>
                        <p>GPS captura latitude e longitude.</p>

                    </div>

                    <div class="recurso">

                        <svg viewBox="0 0 24 24">
                            <path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"/>
                        </svg>

                        <h3>Acompanhe</h3>
                        <p>Mapa e histórico atualizados.</p>

                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>
