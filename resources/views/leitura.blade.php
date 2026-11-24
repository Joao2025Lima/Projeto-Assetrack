
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AsseTrack - Leitura de QR Code</title>
    <link rel="stylesheet" href="{{ asset('css/leitura.css') }}">

    <style>
        #leitor {
            display: none;
            position: absolute;
            inset: 0;
            overflow: hidden;
            border-radius: 12px;
            background: #000;
            z-index: 2;
        }

        #leitor video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .moldura-camera {
            position: relative;
        }

        .canto {
            z-index: 3;
            pointer-events: none;
        }

        .qr-simbolo {
            position: relative;
            z-index: 1;
        }

        .botao-fechar-camera {
            display: none;
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 5;
            border: none;
            border-radius: 6px;
            padding: 8px 11px;
            color: white;
            background: #d93025;
            cursor: pointer;
            font-size: 13px;
        }

        .ferramentas-leitura {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .ferramentas-leitura input {
            flex: 1;
            min-width: 200px;
            padding: 10px;
            border: 1px solid #ccd5e3;
            border-radius: 8px;
            font-size: 14px;
        }

        .ferramentas-leitura button {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #ccd5e3;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        .ferramentas-leitura button.principal {
            background: #0b5ed7;
            color: #fff;
            border-color: #0b5ed7;
        }

        .botao-continuar {
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="tela-sistema">

    @include('partials.sidebar')

    <main class="conteudo-principal">

        <header class="cabecalho">
            <div class="titulo-pagina">
                <div class="icone-pagina"><span>▦</span></div>

                <div>
                    <span class="subtitulo">PROTÓTIPO EMPRESARIAL</span>
                    <h1>Leitura de QR Code</h1>
                </div>
            </div>

            <div class="acoes-cabecalho">
                <button class="icone-botao" type="button" aria-label="Pesquisar">⌕</button>
                <button class="icone-botao" type="button" aria-label="Notificações">♧</button>

                <div class="usuario">
                    <div class="usuario-icone">♙</div>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <section class="area-leitura">

            <div class="painel-celular">
                <div class="celular">
                    <div class="tela-celular">

                        <div class="barra-celular">
                            <span>AsseTrack</span>
                            <span>◉ 92%</span>
                        </div>

                        <div class="area-camera">

                            <div class="moldura-camera">

                                <span class="canto canto-superior-esquerdo"></span>
                                <span class="canto canto-superior-direito"></span>
                                <span class="canto canto-inferior-esquerdo"></span>
                                <span class="canto canto-inferior-direito"></span>

                                <div id="leitor"></div>

                                <button
                                    type="button"
                                    id="fechar-camera"
                                    class="botao-fechar-camera"
                                    onclick="fecharCamera()">
                                    Fechar câmera
                                </button>

                                <div class="qr-simbolo" id="simbolo">
                                    <svg viewBox="0 0 100 100" aria-label="Símbolo ilustrativo de QR Code">
                                        <path d="M12 12h25v25H12z M18 18h13v13H18z"/>
                                        <path d="M63 12h25v25H63z M69 18h13v13H69z"/>
                                        <path d="M12 63h25v25H12z M18 69h13v13H18z"/>
                                        <path d="M45 12h8v8h-8z M45 26h8v8h-8z"/>
                                        <path d="M42 44h8v8h-8z M56 44h8v8h-8z M70 44h8v8h-8z"/>
                                        <path d="M42 58h8v8h-8z M56 58h8v8h-8z M70 58h8v8h-8z"/>
                                        <path d="M44 72h8v8h-8z M58 72h8v8h-8z M72 72h8v8h-8z"/>
                                    </svg>
                                </div>

                            </div>

                            <div class="resultado-camera">
                                <strong id="cam-titulo">Aguardando leitura</strong>
                                <p id="cam-texto">Abra a câmera e aponte para o QR Code do equipamento.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="painel-informacoes">

                <div class="titulo-painel">
                    <h2>Leitura de QR Code</h2>
                    <p>Fluxo móvel para identificar o equipamento antes de registrar a posição</p>
                </div>

                <div class="ferramentas-leitura">

                    <button type="button" class="principal" id="abrir-camera" onclick="abrirCamera()">
                        Abrir câmera
                    </button>

                    <input
                        type="text"
                        id="codigo-manual"
                        placeholder="Ou digite o patrimônio / código do ativo">

                    <button type="button" onclick="buscarManual()">
                        Buscar
                    </button>

                </div>

                <div class="grade-dados">

                    <div class="dado">
                        <span>ATIVO ENCONTRADO</span>
                        <strong id="d-nome">—</strong>
                    </div>

                    <div class="dado">
                        <span>PATRIMÔNIO</span>
                        <strong id="d-pat">—</strong>
                    </div>

                    <div class="dado">
                        <span>NÚMERO DE SÉRIE</span>
                        <strong id="d-serie">—</strong>
                    </div>

                    <div class="dado">
                        <span>QR CODE</span>
                        <strong id="d-qr">—</strong>
                    </div>

                </div>

                <div class="confirmacao">

                    <div class="icone-sucesso">✓</div>

                    <div class="texto-confirmacao">
                        <strong id="c-titulo">Aguardando leitura</strong>
                        <p id="c-texto">Leia um QR Code para validar o equipamento.</p>
                    </div>

                    <button class="botao-continuar" type="button" onclick="continuar()">
                        Continuar <span>›</span>
                    </button>

                </div>

            </div>

        </section>

    </main>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
    const token = document.querySelector('meta[name="csrf-token"]').content;

    const el = (id) => document.getElementById(id);

    let leitor = null;
    let lendo = false;
    let urlContinuar = null;

    function mostrarAtivo(d) {
        el('d-nome').textContent = d.nome ?? '—';
        el('d-pat').textContent = d.patrimonio ?? '—';
        el('d-serie').textContent = d.numero_serie ?? '—';
        el('d-qr').textContent = d.qr_code ?? '—';

        el('cam-titulo').textContent = 'QR Code identificado';
        el('cam-texto').textContent =
            (d.nome ?? 'Equipamento') + ' - ' + (d.patrimonio ?? '');

        el('c-titulo').textContent = 'Equipamento validado';
        el('c-texto').textContent =
            'Pronto para capturar a localização usando o GPS do celular.';

        urlContinuar = d.url ?? null;
    }

    function mostrarErro(msg) {
        ['d-nome', 'd-pat', 'd-serie', 'd-qr'].forEach((id) => {
            el(id).textContent = '—';
        });

        el('cam-titulo').textContent = 'Não foi possível realizar a leitura';
        el('cam-texto').textContent = msg;

        el('c-titulo').textContent = 'Aguardando leitura';
        el('c-texto').textContent =
            'Leia um QR Code para validar o equipamento.';

        urlContinuar = null;
    }

    async function buscar(codigo) {
        try {
            const r = await fetch("{{ route('leitura.buscar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    codigo: codigo
                })
            });

            const d = await r.json();

            if (!r.ok) {
                mostrarErro(
                    d.erro || d.message || 'Não foi possível encontrar esse equipamento.'
                );
                return;
            }

            mostrarAtivo(d);

        } catch (e) {
            mostrarErro('Erro de conexão com o servidor.');
        }
    }

    function buscarManual() {
        const valor = el('codigo-manual').value.trim();

        if (valor) {
            buscar(valor);
        } else {
            mostrarErro('Digite o patrimônio ou código do equipamento.');
        }
    }

    async function abrirCamera() {
        if (!window.isSecureContext) {
            mostrarErro(
                'A câmera exige HTTPS ou localhost. O endereço HTTP pelo IP do computador não permite acessar a câmera.'
            );
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            mostrarErro('Este navegador não disponibiliza acesso à câmera.');
            return;
        }

        if (leitor) return;

        if (typeof Html5Qrcode === 'undefined') {
            mostrarErro('Não foi possível carregar o leitor de QR Code. Verifique sua conexão com a internet.');
            return;
        }

        lendo = false;
        leitor = new Html5Qrcode('leitor');

        el('leitor').style.display = 'block';
        el('simbolo').style.display = 'none';
        el('fechar-camera').style.display = 'block';

        el('cam-titulo').textContent = 'Abrindo câmera...';
        el('cam-texto').textContent = 'Permita o acesso à câmera quando o navegador solicitar.';

        const configuracao = {
            fps: 10,
            qrbox: function (largura, altura) {
                const tamanho = Math.floor(Math.min(largura, altura) * 0.70);

                return {
                    width: tamanho,
                    height: tamanho
                };
            },
            aspectRatio: 1
        };

        const aoLer = async (texto) => {
            if (lendo) return;

            lendo = true;

            await fecharCamera();

            el('cam-titulo').textContent = 'Código identificado';
            el('cam-texto').textContent = 'Consultando equipamento...';

            buscar(texto);
        };

        try {
            try {
                await leitor.start(
                    { facingMode: 'environment' },
                    configuracao,
                    aoLer
                );

            } catch (erroTraseira) {
                const cameras = await Html5Qrcode.getCameras();

                if (!cameras || cameras.length === 0) {
                    throw erroTraseira;
                }

                await leitor.start(
                    cameras[0].id,
                    configuracao,
                    aoLer
                );
            }

            el('cam-titulo').textContent = 'Câmera ativa';
            el('cam-texto').textContent = 'Aponte a câmera para o QR Code do equipamento.';

        } catch (e) {
            await fecharCamera();

            mostrarErro(
                'Não foi possível abrir a câmera. Verifique a permissão do navegador, se ela está sendo utilizada por outro aplicativo e se existe uma câmera disponível.'
            );
        }
    }

    async function fecharCamera() {
        if (leitor) {
            try {
                await leitor.stop();
            } catch (e) {}

            try {
                leitor.clear();
            } catch (e) {}

            leitor = null;
        }

        el('leitor').style.display = 'none';
        el('simbolo').style.display = '';
        el('fechar-camera').style.display = 'none';
    }

    function continuar() {
        if (urlContinuar) {
            window.location.href = urlContinuar;
        } else {
            alert('Primeiro, leia ou pesquise um equipamento.');
        }
    }

    el('codigo-manual').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            buscarManual();
        }
    });

    window.addEventListener('beforeunload', () => {
        if (leitor) {
            leitor.stop().catch(() => {});
        }
    });
</script>

</body>
</html>
