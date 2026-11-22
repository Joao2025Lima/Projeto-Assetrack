<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsseTrack - Registro por GPS</title>
    <link rel="stylesheet" href="{{ asset('css/gps.css') }}">
    <style>
        .campo select { width: 100%; padding: 12px 14px; border: 1px solid #d6deeb; border-radius: 10px; font-size: 14px; background: #fff; box-sizing: border-box; font-family: inherit; }
        .campo input[name="descricao"] { background: #fff; }
        .erro-mov { background: #fde8ea; color: #a1212e; padding: 10px 14px; border-radius: 10px; margin-bottom: 14px; font-size: 14px; }
        .botoes button { cursor: pointer; }
        .gps-lateral { margin-top: auto; background: rgba(255,255,255,.06); border-radius: 14px; padding: 14px; font-size: 13px; }
        .gps-lateral h4 { margin: 0 0 6px; font-size: 14px; }
        .gps-lateral p { margin: 0 0 6px; opacity: .8; }
    </style>
</head>
<body>

<div class="tela-sistema">

    @include('partials.sidebar')

    <main class="conteudo-principal">

        <header class="cabecalho">
            <div class="titulo-pagina">
                <div class="icone-pagina">▦</div>
                <div>
                    <span class="subtitulo">PROTÓTIPO EMPRESARIAL</span>
                    <h1>Registro por GPS</h1>
                </div>
            </div>

            <div class="acoes-cabecalho">
                <button class="icone-botao" type="button">⌕</button>
                <button class="icone-botao" type="button">♧</button>
                <div class="usuario">
                    <div class="usuario-icone">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <div class="area-conteudo">

            <section class="caixa-registro">

                <h3>Registro de localização por GPS</h3>
                <p class="descricao">
                    Confirmação da posição do equipamento após leitura do QR Code
                </p>

                @if ($errors->any())
                    <div class="erro-mov">
                        @foreach ($errors->all() as $erro)
                            <div>{{ $erro }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('movimentar.store') }}">
                    @csrf
                    <input type="hidden" name="ativo_id" value="{{ $ativo->id }}">

                    <div class="campos">

                        <div class="campo">
                            <label>Ativo</label>
                            <input type="text" value="{{ $ativo->nome }}" readonly>
                        </div>

                        <div class="campo">
                            <label>Código / patrimônio</label>
                            <input type="text" value="{{ $ativo->patrimonio }}" readonly>
                        </div>

                        <div class="campo">
                            <label>Local atual</label>
                            <input type="text" value="{{ $ativo->local?->nome ?? 'Não registrado' }}" readonly>
                        </div>

                        <div class="campo">
                            <label>Novo local</label>
                            <select name="local_novo_id" id="novo-local" required>
                                <option value="">Selecione...</option>
                                @foreach ($locais as $local)
                                    <option value="{{ $local->id }}" @selected(old('local_novo_id') == $local->id)>{{ $local->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="campo">
                            <label>Observação</label>
                            <input type="text" name="descricao" value="{{ old('descricao') }}" maxlength="255" placeholder="Opcional">
                        </div>

                        <div class="campo">
                            <label>Data/hora</label>
                            <input type="text" value="{{ now()->format('d/m/Y H:i') }}" readonly>
                        </div>

                    </div>

                    <div class="aviso-gps">
                        <div class="icone-gps">◔</div>
                        <div>
                            <strong>Localização informada manualmente</strong>
                            <p>O GPS em tempo real não está ativo nesta versão. Escolha o novo local na lista.</p>
                        </div>
                    </div>

                    <div class="botoes">
                        <button class="botao-confirmar" type="submit">
                            ◎ Confirmar localização
                        </button>

                        <button class="botao-nova-captura" type="reset" onclick="atualizarStatus('')">
                            ↻ Limpar escolha
                        </button>
                    </div>
                </form>

            </section>

            <aside class="caixa-status">

                <h3>Coleta móvel</h3>

                <div class="status-item">
                    <div class="status-icone">▧</div>
                    <div>
                        <strong>QR Code lido</strong>
                        <p>{{ $ativo->patrimonio }}</p>
                    </div>
                </div>

                <div class="status-item">
                    <div class="status-icone">▯</div>
                    <div>
                        <strong>Local atual</strong>
                        <p>{{ $ativo->local?->nome ?? 'Não registrado' }}</p>
                    </div>
                </div>

                <div class="status-item">
                    <div class="status-icone">♧</div>
                    <div>
                        <strong>Posição a registrar</strong>
                        <p id="status-novo">Aguardando escolha</p>
                    </div>
                </div>

            </aside>

        </div>

    </main>

</div>

<script>
    const select = document.getElementById('novo-local');
    const status = document.getElementById('status-novo');

    function atualizarStatus(valor) {
        status.textContent = valor || 'Aguardando escolha';
    }

    select.addEventListener('change', () => {
        const opcao = select.options[select.selectedIndex];
        atualizarStatus(select.value ? opcao.text : '');
    });

    if (select.value) atualizarStatus(select.options[select.selectedIndex].text);
</script>
</body>
</html>