
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AsseTrack - Criar conta</title>

    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #172338;
        }

        .conteudo-principal {
            min-height: 100vh;
            padding: 30px;
        }

        .cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 35px;
        }

        .subtitulo {
            color: #64748b;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .titulo-pagina h1 {
            margin: 7px 0;
            font-size: 27px;
            color: #172338;
        }

        .area-cadastro-usuario {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0 40px;
        }

        .painel-cadastro {
            width: 100%;
            max-width: 480px;
            padding: 35px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(15, 35, 65, 0.08);
            border: 1px solid #e5eaf2;
        }

        .marca {
            text-align: center;
            margin-bottom: 28px;
        }

        .marca h2 {
            color: #17365d;
            font-size: 25px;
            margin: 0 0 8px;
        }

        .marca p {
            color: #718096;
            font-size: 13px;
            margin: 0;
        }

        .titulo-formulario {
            margin-bottom: 25px;
        }

        .titulo-formulario h3 {
            font-size: 21px;
            margin: 0 0 8px;
            color: #172338;
        }

        .titulo-formulario p {
            color: #718096;
            font-size: 13px;
            margin: 0;
        }

        .campo {
            margin-bottom: 18px;
        }

        .campo label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #334155;
        }

        .campo input {
            width: 100%;
            height: 45px;
            border: 1px solid #d8e0eb;
            border-radius: 6px;
            padding: 0 13px;
            font-size: 14px;
            outline: none;
            background: #ffffff;
        }

        .campo input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        .botao-criar {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 6px;
            background: #173f70;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .botao-criar:hover {
            background: #24558e;
        }

        .link-login {
            text-align: center;
            margin-top: 23px;
            font-size: 13px;
            color: #64748b;
        }

        .link-login a {
            color: #1d4f91;
            font-weight: bold;
            text-decoration: none;
        }

        .link-login a:hover {
            text-decoration: underline;
        }

        .mensagem-erro {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .mensagem-erro ul {
            padding-left: 18px;
            margin-bottom: 0;
        }

        .mensagem-erro li {
            margin-top: 5px;
        }

        .aviso-senha {
            color: #718096;
            font-size: 11px;
            margin-top: 6px;
        }

        @media (max-width: 600px) {
            .conteudo-principal {
                padding: 75px 15px 25px;
            }

            .cabecalho {
                margin-bottom: 20px;
            }

            .titulo-pagina h1 {
                font-size: 22px;
            }

            .painel-cadastro {
                padding: 25px 20px;
            }

            .area-cadastro-usuario {
                padding-top: 10px;
            }
        }
    </style>
</head>

<body>

<div class="tela-sistema">

    @include('partials.sidebar')

    <main class="conteudo-principal">

        <header class="cabecalho">
            <div class="titulo-pagina">
                <span class="subtitulo">BEM-VINDO AO ASSETRACK</span>
                <h1>Criar conta</h1>
            </div>
        </header>

        <section class="area-cadastro-usuario">

            <div class="painel-cadastro">

                <div class="marca">
                    <h2>AsseTrack</h2>
                    <p>Gestão inteligente de ativos</p>
                </div>

                <div class="titulo-formulario">
                    <h3>Crie sua conta</h3>
                    <p>Preencha os dados abaixo para acessar o sistema.</p>
                </div>

                @if ($errors->any())
                    <div class="mensagem-erro">
                        <strong>Confira os campos abaixo:</strong>

                        <ul>
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('cadastro.post') }}" method="POST">
                    @csrf

                    <div class="campo">
                        <label for="name">Nome completo</label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Digite seu nome completo"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="email">E-mail</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite seu e-mail"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="password">Senha</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Crie uma senha"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <p class="aviso-senha">
                            A senha deve possuir pelo menos 8 caracteres.
                        </p>
                    </div>

                    <div class="campo">
                        <label for="password_confirmation">
                            Confirmar senha
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Digite novamente sua senha"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >
                    </div>

                    <button type="submit" class="botao-criar">
                        Criar minha conta
                    </button>

                </form>

                <div class="link-login">
                    Já possui uma conta?
                    <a href="{{ route('login') }}">
                        Já possuo uma conta
                    </a>
                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>
