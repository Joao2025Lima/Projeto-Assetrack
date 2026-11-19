
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
            <li><a href="{{ route('login') }}" class="ativo">Login</a></li>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Lista de ativos</a></li>
            <li><a href="#">Cadastro de ativo</a></li>
            <li><a href="#">Detalhes do ativo</a></li>
            <li><a href="#">Leitura de QR Code</a></li>
            <li><a href="#">Registro por GPS</a></li>
            <li><a href="#">Mapa de ativos</a></li>
            <li><a href="#">Histórico</a></li>
        </ul>

        <div class="informacao-menu">
            <div class="info-titulo">
                <strong>QR + GPS ativo</strong>
            </div>
            <p>Registros móveis sincronizados com mapa corporativo em tempo real</p>
        </div>

    </nav>

    <main class="conteudo-principal">

        <header class="cabecalho">

            <div class="titulo-pagina">
                <div class="icone-pagina"></div>

                <div>
                    <span class="subtitulo">PROTÓTIPO EMPRESARIAL</span>
                    <h1>Login</h1>
                </div>
            </div>

            <div class="acoes-cabecalho">

                <div class="usuario">
                    <div class="icone-usuario"></div>
                    <strong>AsseTrack</strong>
                </div>

            </div>

        </header>

        <section class="area-login">

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

                    @if ($errors->any())
                        <p class="mensagem-erro">
                            {{ $errors->first() }}
                        </p>
                    @endif

                    <div class="informacao-login">
                        <span>Autenticação empresarial habilitada</span>
                        <a href="#" onclick="alert('A recuperação de senha será implementada posteriormente.'); return false;">
                            Recuperar senha
                        </a>
                    </div>

                    <button type="submit" class="botao-entrar">
                        Entrar no sistema
                    </button>

                </form>

            </div>

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
                        <h3>Escaneie</h3>
                        <p>Identificação pelo QR do ativo.</p>
                    </div>

                    <div class="recurso">
                        <h3>Registre</h3>
                        <p>GPS captura latitude e longitude.</p>
                    </div>

                    <div class="recurso">
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
