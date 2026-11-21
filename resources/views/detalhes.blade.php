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

    // Último usuário que movimentou o ativo
    $ultimoUsuario = $historicosRecentes->first()?->usuario?->name;

    // Link do Google Maps: usa o link do local, se existir; senão busca pelo nome
    $linkMaps = null;
    if ($ativo->local) {
        $linkMaps = $ativo->local->link_maps
            ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($ativo->local->nome);
    }
@endphp

<div class="tela-sistema">

    @include('partials.sidebar')

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

                    <a href="{{ route('movimentar.create', $ativo->id) }}" class="botao-localizacao">
                        <svg viewBox="0 0 24 24"><path d="m12 3 2.2 6.8L21 12l-6.8 2.2L12 21l-2.2-6.8L3 12l6.8-2.2L12 3Z"/></svg>
                        Movimentar ativo
                    </a>
                </section>

                <section class="painel localizacao-atual">
                    <h2>Última localização e último usuário do produto</h2>

                    <div class="texto-localizacao">
                        <p><strong>Localização:</strong>
                            @if ($linkMaps)
                                <a href="{{ $linkMaps }}" target="_blank" rel="noopener">
                                    {{ $ativo->local->nome }} (abrir no Google Maps)
                                </a>
                            @else
                                Não registrada
                            @endif
                        </p>

                        <p><strong>Último usuário:</strong> {{ $ultimoUsuario ?? 'Nenhuma movimentação registrada' }}</p>

                        <p><strong>Atualizado em:</strong> {{ $ativo->updated_at?->format('d/m/Y H:i') ?? '—' }}</p>
                    </div>
                </section>
            </div>
        </section>
    </main>
</div>
</body>
</html>