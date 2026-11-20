
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsseTrack - Detalhes do Ativo</title>
    <link rel="stylesheet" href="{{ asset('css/detalhes.css') }}">
</head>
<body>
@php
    $limiteAtualizacao = now()->subHours(72);

    if (!$ativo->local_id) {
        $statusClasse = '';
        $textoStatus = 'Sem localização';
    } elseif ($ativo->updated_at && $ativo->updated_at->lte($limiteAtualizacao)) {
        $statusClasse = '';
        $textoStatus = 'Sem atualização';
    } else {
        $statusClasse = 'localizado';
        $textoStatus = 'Localizado';
    }

    // Recebe os três históricos enviados pelo AtivoController
    $historicosRecentes = $historicos;
@endphp

<div class="tela-sistema">

    <aside class="menu-lateral">
        <div class="logo">
            <div class="logo-icone">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 18V6l8-3 8 3v12l-8 3-8-3Z"/><path d="m8 9 4-2 4 2v6l-4 2-4-2V9Z"/><path d="m8 9 4 2 4-2M12 11v6"/></svg>
            </div>
            <div><h2>AsseTrack</h2><p>Gestão de ativos</p></div>
        </div>

        <nav>
            <ul class="lista-menu">
                <li><a href="{{ route('login') }}"><svg viewBox="0 0 24 24"><path d="m3 10 9-7 9 7"/><path d="M5 9v11h14V9M9 20v-7h6v7"/></svg>Login</a></li>
                <li><a href="{{ route('dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a></li>
                <li><a href="{{ route('ativos.index') }}"><svg viewBox="0 0 24 24"><path d="M4 5h16M4 12h16M4 19h16"/><path d="M7 3v4M7 10v4M7 17v4"/></svg>Lista de ativos</a></li>
                <li><a href="{{ route('ativos.create') }}"><svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>Cadastro de ativo</a></li>
                <li><a href="{{ route('ativos.show', $ativo->id) }}" class="ativo"><svg viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M8 8h8v8H8z"/></svg>Detalhes do ativo</a></li>
                <li><a href="{{ route('leitura.qrcode', $ativo->id) }}"><svg viewBox="0 0 24 24"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/><path d="M14 14h2v2h-2zM18 14h2v6h-2zM14 18h2v2h-2z"/></svg>Leitura de QR Code</a></li>
                <li><a href="{{ route('ativos.index') }}"><svg viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="9" r="2.3"/></svg>Registro por GPS</a></li>
                <li><a href="{{ route('ativos.index') }}"><svg viewBox="0 0 24 24"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3V6Z"/><path d="M9 3v15M15 6v15"/></svg>Mapa de ativos</a></li>
                <li><a href="{{ route('historicos.index') }}"><svg viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 2.6-6.4L3 8"/><path d="M3 3v5h5M12 7v5l3 2"/></svg>Histórico</a></li>
            </ul>
        </nav>

        <div class="gps-lateral">
            <h4><span class="mini-diamond">◆</span> QR + GPS ativo</h4>
            <p>Registros móveis sincronizados com mapa corporativo em tempo real.</p>
            <span><i></i> Sistema operacional</span>
        </div>
    </aside>

    <main class="conteudo-principal">
        <header class="cabecalho">
            <div class="titulo-pagina">
                <span class="subtitulo">GESTÃO DE EQUIPAMENTOS</span>
                <h1>Detalhes do ativo</h1>
            </div>
            <div class="acoes-cabecalho">
                <a href="{{ route('ativos.index') }}" class="icone-botao" aria-label="Voltar para lista">
                    <svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <div class="usuario">
                    <div class="usuario-icone">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <section class="area-detalhes">
            <div class="coluna-esquerda">

                <section class="painel informacoes-ativo">
                    <div class="identificacao">
                        <div>
                            <div class="etiquetas">
                                <span class="etiqueta-patrimonio">{{ $ativo->patrimonio }}</span>
                                <span class="status {{ $statusClasse }}">{{ $textoStatus }}</span>
                            </div>
                            <h2>{{ $ativo->nome }}</h2>
                            <p class="numero-serie">Série {{ $ativo->numero_serie ?: 'Não informada' }}</p>
                        </div>
                        <a href="{{ route('ativos.index') }}" class="botao-editar" title="Voltar para ativos" aria-label="Voltar para ativos">
                            <svg viewBox="0 0 24 24"><path d="m14 5 5 5M4 20l4.5-1 11-11a2.1 2.1 0 0 0-3-3l-11 11L4 20Z"/><path d="m13 6 5 5"/></svg>
                        </a>
                    </div>

                    <div class="grade-informacoes">
                        <div class="informacao">
                            <span class="rotulo">CATEGORIA</span>
                            <strong>{{ $ativo->categoria?->nome ?? 'Não informada' }}</strong>
                        </div>
                        <div class="informacao">
                            <span class="rotulo">RESPONSÁVEL</span>
                            <strong>{{ $ativo->usuario?->name ?? 'Não atribuído' }}</strong>
                        </div>
                        <div class="informacao">
                            <span class="rotulo">QR CODE</span>
                            <strong>{{ $ativo->qr_code ? 'Gerado' : 'Não gerado' }}</strong>
                        </div>
                        <div class="informacao">
                            <span class="rotulo">ÚLTIMA LOCALIZAÇÃO</span>
                            <strong>{{ $ativo->local?->nome ?? 'Não registrada' }}</strong>
                        </div>
                        <div class="informacao">
                            <span class="rotulo">DATA/HORA DA ATUALIZAÇÃO</span>
                            <strong>{{ $ativo->updated_at?->format('d/m/Y H:i') ?? '—' }}</strong>
                        </div>
                        <div class="informacao">
                            <span class="rotulo">COORDENADAS</span>
                            <strong>Não registradas</strong>
                        </div>
                    </div>
                </section>

                <section class="painel historico-recente">
                    <div class="titulo-secao">
                        <div><h2>Histórico recente</h2></div>
                        <a href="{{ route('historicos.show', $ativo->id) }}">Ver histórico completo</a>
                    </div>

                    @forelse ($historicosRecentes as $historico)
                        <div class="movimentacao">
                            <div class="icone-movimentacao">
                                <svg viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="9" r="2.3"/></svg>
                            </div>
                            <div class="dados-movimentacao">
                                <strong>
                                    {{ $historico->localNovo?->nome ?? $historico->descricao ?? ucfirst($historico->tipo) }}
                                </strong>
                                <p>
                                    {{ $historico->usuario?->name ?? 'Usuário não identificado' }}
                                    ·
                                    {{ $historico->data_movimentacao
                                        ? \Carbon\Carbon::parse($historico->data_movimentacao)->format('d/m/Y H:i')
                                        : 'Data não informada' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p>Este ativo ainda não possui movimentações registradas.</p>
                    @endforelse
                </section>
            </div>

            <div class="coluna-direita">
                <section class="painel painel-qr">
                    <h2>QR Code do ativo</h2>
                    <p>Identificação única do equipamento</p>

                    <div class="cartao-qr">
                        <div class="qr-visual">
                            @if ($ativo->qr_code)
                                <img
                                    class="imagem-qr"
                                    src="{{ asset('storage/' . $ativo->qr_code) }}"
                                    alt="QR Code do ativo {{ $ativo->patrimonio }}"
                                >
                            @else
                                <span>QR Code não gerado</span>
                            @endif
                        </div>
                        <strong>{{ $ativo->patrimonio }}</strong>
                        <span>Escaneie para abrir o registro do ativo</span>
                    </div>

                    <a href="{{ route('leitura.qrcode', $ativo->id) }}" class="botao-ler">
                        <svg viewBox="0 0 24 24"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/><path d="M14 14h2v2h-2zM18 14h2v6h-2zM14 18h2v2h-2z"/></svg>
                        Ler QR Code
                    </a>

                    <a href="{{ route('ativos.index') }}" class="botao-localizacao">
                        <svg viewBox="0 0 24 24"><path d="m12 3 2.2 6.8L21 12l-6.8 2.2L12 21l-2.2-6.8L3 12l6.8-2.2L12 3Z"/></svg>
                        Registrar localização
                    </a>
                </section>

                <section class="painel localizacao-atual">
                    <h2>Localização atual</h2>
                    <div class="mapa-ilustrativo">
                        <div class="mancha-mapa mancha-um"></div>
                        <div class="mancha-mapa mancha-dois"></div>
                        <div class="rua rua-horizontal"></div>
                        <div class="rua rua-vertical"></div>
                        <div class="rua rua-diagonal"></div>
                        <div class="marcador-mapa">
                            <svg viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="9" r="2.3"/></svg>
                        </div>
                    </div>
                    <div class="texto-localizacao">
                        <strong>{{ $ativo->local?->nome ?? 'Localização não registrada' }}</strong>
                        <p>Mapa ilustrativo. Coordenadas GPS ainda não disponíveis.</p>
                    </div>
                </section>
            </div>
        </section>
    </main>
</div>
</body>
</html>
