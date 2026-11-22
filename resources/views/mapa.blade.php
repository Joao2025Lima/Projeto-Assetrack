<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsseTrack - Mapa de ativos</title>
    <link rel="stylesheet" href="{{ asset('css/mapa.css') }}">
    <style>
        .status.sem-localizacao { background: #fde8ea; color: #a1212e; }
    </style>
</head>
<body>
@php
    $limite = now()->subHours(72);
@endphp

<div class="tela-sistema">

   @include('partials.sidebar')
   
    <main class="conteudo-principal">

        <header class="cabecalho">
            <div class="titulo-pagina">
                <div class="icone-pagina">
                    <svg viewBox="0 0 24 24">
                        <path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"/>
                    </svg>
                </div>
                <div>
                    <span class="subtitulo">PROTÓTIPO EMPRESARIAL</span>
                    <h1>Mapa de ativos</h1>
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
                    <strong>{{ auth()->user()->name }}</strong>
                </div>
            </div>
        </header>

        <section class="area-mapa">

            <div class="titulo-area">
                <h2>Última localização e último usuário do produto</h2>
                <p>Consulte onde cada equipamento foi localizado e quem realizou o último registro.</p>
            </div>

            <div class="lista-localizacoes">

                @forelse ($ativos as $ativo)
                    @php
                        if (!$ativo->local_id) {
                            $classe = 'sem-localizacao';
                            $texto  = 'Sem localização';
                        } elseif ($ativo->updated_at && $ativo->updated_at->lte($limite)) {
                            $classe = 'sem-atualizacao';
                            $texto  = 'Sem atualização';
                        } else {
                            $classe = 'localizado';
                            $texto  = 'Localizado';
                        }

                        $linkMaps = null;
                        if ($ativo->local) {
                            $linkMaps = $ativo->local->link_maps
                                ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($ativo->local->nome);
                        }

                        $ultimoUsuario = $ultimos->get($ativo->id)?->usuario?->name;
                    @endphp

                    <article class="cartao-ativo">

                        <div class="cartao-topo">
                            <h3>{{ $ativo->nome }}</h3>
                            <span class="status {{ $classe }}">{{ $texto }}</span>
                        </div>

                        <div class="informacoes-ativo">
                            <strong>Última localização:</strong>
                            @if ($linkMaps)
                                <a href="{{ $linkMaps }}" target="_blank" rel="noopener noreferrer">
                                    {{ $ativo->local->nome }} – abrir no Google Maps
                                </a>
                            @else
                                <span>Não registrada</span>
                            @endif
                        </div>

                        <p class="ultimo-usuario">
                            <strong>Último usuário:</strong> {{ $ultimoUsuario ?? 'Nenhum registro' }}
                        </p>

                    </article>
                @empty
                    <p>Nenhum ativo cadastrado.</p>
                @endforelse

            </div>

        </section>

    </main>

</div>
</body>
</html>