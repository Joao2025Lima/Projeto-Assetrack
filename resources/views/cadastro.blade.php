
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsseTrack - Cadastro de Ativo</title>
    <link rel="stylesheet" href="{{ asset('css/cadastro.css') }}">
</head>

<body>

<div class="tela-sistema">

    <aside class="menu-lateral">

        <div class="logo">
            <div class="logo-icone">A</div>
            <div>
                <h2>AsseTrack</h2>
                <p>Gestão de ativos</p>
            </div>
        </div>

        <nav>
            <ul class="lista-menu">
                <li><a href="{{ route('dashboard') }}"><span>⌂</span> Dashboard</a></li>
                <li><a href="{{ route('ativos.index') }}"><span>▤</span> Lista de ativos</a></li>
                <li><a href="{{ route('ativos.create') }}" class="ativo"><span>＋</span> Cadastro de ativo</a></li>
                <li><a href="#"><span>▣</span> Detalhes do ativo</a></li>
                <li><a href="#"><span>▦</span> Leitura de QR Code</a></li>
                <li><a href="#"><span>⌖</span> Registro por GPS</a></li>
                <li><a href="#"><span>▧</span> Mapa de ativos</a></li>
                <li><a href="{{ route('historicos.index') }}"><span>◷</span> Histórico</a></li>
            </ul>
        </nav>

        <div class="gps-lateral">
            <h4>◎ QR + GPS ativo</h4>
            <p>Registros móveis sincronizados com mapa corporativo em tempo real.</p>
            <span>● Sistema operacional</span>
        </div>

    </aside>

    <main class="conteudo-principal">

        <header class="cabecalho">

            <div class="titulo-pagina">
                <span class="subtitulo">GESTÃO DE EQUIPAMENTOS</span>
                <h1>Cadastro de ativo</h1>
            </div>

            <div class="acoes-cabecalho">
                <div class="usuario">
                    <div class="usuario-icone">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </div>

        </header>

        <section class="area-cadastro">

            <div class="painel-formulario">

                <div class="titulo-painel">
                    <h2>Cadastro de ativo</h2>
                    <p>Insira as informações do equipamento</p>
                </div>

                @if (session('sucesso'))
                    <div style="background:#dcfce7;color:#166534;padding:12px;border-radius:5px;margin-bottom:15px;font-size:12px;">
                        {{ session('sucesso') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div style="background:#fee2e2;color:#991b1b;padding:12px;border-radius:5px;margin-bottom:15px;font-size:12px;">
                        <strong>Confira os campos abaixo:</strong>
                        <ul style="padding-left:18px;margin-top:6px;">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="formulario" action="{{ route('ativos.store') }}" method="POST">
                    @csrf

                    <div class="linha-campos">

                        <div class="campo">
                            <label for="nome">Nome do ativo</label>
                            <input type="text" id="nome" name="nome"
                                placeholder="Ex: Notebook Dell"
                                value="{{ old('nome') }}" required>
                        </div>

                        <div class="campo">
                            <label for="patrimonio">Código / patrimônio</label>
                            <input type="text" id="patrimonio" name="patrimonio"
                                placeholder="PAT-0001"
                                value="{{ old('patrimonio') }}" required>
                        </div>

                    </div>

                    <div class="linha-campos">

                        <div class="campo">
                            <label for="categoria_id">Categoria</label>
                            <select id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>

                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="campo">
                            <label for="responsavel">Responsável</label>
                            <input type="text" id="responsavel"
                                value="{{ auth()->user()->name }}" readonly>
                        </div>

                    </div>

                    <div class="linha-campos">

                        <div class="campo">
                            <label for="numero_serie">Número de série</label>
                            <input type="text" id="numero_serie" name="numero_serie"
                                placeholder="Ex: SN-2026-001"
                                value="{{ old('numero_serie') }}">
                        </div>

                        <div class="campo">
                            <label for="local_id">Localização inicial</label>
                            <select id="local_id" name="local_id">
                                <option value="">Sem localização definida</option>

                                @foreach ($locais as $local)
                                    <option value="{{ $local->id }}"
                                        {{ old('local_id') == $local->id ? 'selected' : '' }}>
                                        {{ $local->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="campo campo-observacoes">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes"
                            placeholder="Adicione informações sobre o equipamento, utilização e necessidades de localização...">{{ old('observacoes') }}</textarea>
                    </div>

                    <div class="botoes-formulario">
                        <button type="submit" class="botao-salvar">
                            ▣ Salvar ativo
                        </button>
                    </div>

                </form>

            </div>

            <div class="painel-qr">

                <div class="titulo-painel">
                    <h2>Etiqueta QR</h2>
                    <p>Pré-visualização para impressão</p>
                </div>

                <div class="cartao-etiqueta">

                    <div class="qr-visual">

                        @if (session('qrCode'))
                            <img src="{{ session('qrCode') }}"
                                alt="QR Code do ativo"
                                style="width:100%;height:100%;object-fit:contain;">
                        @else
                            <svg class="qr-svg" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                <rect width="21" height="21" fill="#ffffff"/>
                                <path fill="#101d30" fill-rule="evenodd" d="M1 1h7v7H1z M2 2v5h5V2z"/>
                                <rect x="3" y="3" width="3" height="3" fill="#101d30"/>
                                <path fill="#101d30" fill-rule="evenodd" d="M13 1h7v7h-7z M14 2v5h5V2z"/>
                                <rect x="15" y="3" width="3" height="3" fill="#101d30"/>
                                <path fill="#101d30" fill-rule="evenodd" d="M1 13h7v7H1z M2 14v5h5v-5z"/>
                                <rect x="3" y="15" width="3" height="3" fill="#101d30"/>
                                <path fill="#101d30" d="
                                    M9 1h1v1H9z M11 1h1v2h-1z
                                    M9 3h2v1H9z M12 3h1v2h-1z
                                    M9 5h1v2H9z M11 6h2v1h-2z
                                    M9 8h1v1H9z M11 8h1v2h-1z
                                    M13 9h1v1h-1z M15 9h2v1h-2z
                                    M18 9h2v1h-2z
                                    M1 9h1v2H1z M3 9h2v1H3z
                                    M6 9h2v1H6z
                                    M9 11h2v1H9z M12 11h1v2h-1z
                                    M14 11h2v1h-2z M17 11h1v2h-1z
                                    M19 11h1v1h-1z
                                    M9 13h1v2H9z M11 14h2v1h-2z
                                    M14 13h1v2h-1z M16 14h2v1h-2z
                                    M19 13h1v2h-1z
                                    M9 16h2v1H9z M12 16h1v2h-1z
                                    M14 16h2v1h-2z M17 16h1v2h-1z
                                    M19 16h1v1h-1z
                                    M9 18h1v2H9z M11 19h2v1h-2z
                                    M14 18h1v2h-1z M16 19h2v1h-2z
                                    M19 18h1v2h-1z
                                "/>
                            </svg>
                        @endif

                    </div>

                    <h3>AsseTrack</h3>
                    <p>Escaneie para abrir o registro do ativo</p>

                    <div class="codigo-etiqueta">
                        {{ session('codigoEtiqueta', 'AST-QRC-0000') }}
                    </div>

                </div>

                @if (session('qrCode'))
                    <a href="{{ session('qrCode') }}"
                        download="qrcode-{{ session('codigoEtiqueta') }}.svg"
                        class="botao-baixar"
                        style="text-align:center;text-decoration:none;">
                        ↓ Baixar etiqueta
                    </a>
                @else
                    <button type="button" class="botao-baixar"
                        onclick="alert('Salve o ativo primeiro para gerar e baixar seu QR Code.')">
                        ↓ Baixar etiqueta
                    </button>
                @endif

            </div>

        </section>

    </main>

</div>

</body>
</html>
