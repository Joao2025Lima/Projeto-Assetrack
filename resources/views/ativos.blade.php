
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AsseTrack - Lista de Ativos</title>

    <link rel="stylesheet" href="{{ asset('css/ativos.css') }}">
</head>

<body>

    <div class="tela-sistema">

        @include('partials.sidebar')

        <!-- CONTEÚDO PRINCIPAL -->

        <main class="conteudo-principal">

            <header class="cabecalho">

                <div class="titulo-pagina">
                    <span class="subtitulo">GESTÃO DE EQUIPAMENTOS</span>
                    <h1>Lista de ativos</h1>
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

            <!-- PESQUISA E FILTRO -->

            <form action="{{ route('ativos.index') }}" method="GET" class="barra-ferramentas">

                <div class="campo-pesquisa">
                    <span>⌕</span>

                    <input
                        type="text"
                        name="busca"
                        value="{{ request('busca') }}"
                        placeholder="Buscar por nome, patrimônio, responsável ou série"
                    >
                </div>

                <select
                    class="filtro-status"
                    name="status"
                    onchange="this.form.submit()"
                >

                    <option value="">Todos os status</option>

                    <option value="localizado"
                        {{ request('status') === 'localizado' ? 'selected' : '' }}>
                        Localizado
                    </option>

                    <option value="atualizacao"
                        {{ request('status') === 'atualizacao' ? 'selected' : '' }}>
                        Sem atualização
                    </option>

                    <option value="sem_localizacao"
                        {{ request('status') === 'sem_localizacao' ? 'selected' : '' }}>
                        Sem localização
                    </option>

                </select>

                <button type="submit" class="botao-filtros">
                    Buscar
                </button>

                <a href="{{ route('ativos.create') }}" class="botao-novo">
                    + Novo ativo
                </a>

            </form>

            <!-- INVENTÁRIO -->

            <section class="inventario">

                <div class="inventario-cabecalho">

                    <div>
                        <h2>Inventário de ativos</h2>

                        <p>
                            Clique em uma linha para ver os detalhes do equipamento
                        </p>
                    </div>

                    <span>
                        {{ $ativos->count() }} ativo(s) encontrado(s)
                    </span>

                </div>

                <!-- TABELA -->

                <div class="tabela-container">

                    <table>

                        <thead>
                            <tr>
                                <th>Ativo</th>
                                <th>Categoria</th>
                                <th>Responsável</th>
                                <th>Status</th>
                                <th>Última localização</th>
                                <th>Atualização</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($ativos as $ativo)

                                @php
                                    if (!$ativo->local_id) {
                                        $status = 'sem-localizacao';
                                        $textoStatus = 'Sem localização';
                                    } elseif ($ativo->updated_at && $ativo->updated_at->lte($limiteAtualizacao)) {
                                        $status = 'atualizacao';
                                        $textoStatus = 'Sem atualização';
                                    } else {
                                        $status = 'localizado';
                                        $textoStatus = 'Localizado';
                                    }
                                @endphp

                                <tr
                                    onclick="window.location.href='{{ route('ativos.show', $ativo->id) }}'"
                                    style="cursor: pointer;"
                                    title="Clique para ver os detalhes deste ativo"
                                >

                                    <td>
                                        <strong>
                                            <a
                                                href="{{ route('ativos.show', $ativo->id) }}"
                                                style="color: inherit; text-decoration: none;"
                                            >
                                                {{ $ativo->nome }}
                                            </a>
                                        </strong>

                                        <small>
                                            {{ $ativo->patrimonio }}
                                            · S/N: {{ $ativo->numero_serie ?: 'Não informado' }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $ativo->categoria?->nome ?? 'Não informada' }}
                                    </td>

                                    <td>
                                        {{ $ativo->usuario?->name ?? 'Não atribuído' }}
                                    </td>

                                    <td>
                                        <span class="status {{ $status }}">
                                            {{ $textoStatus }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $ativo->local?->nome ?? 'Não registrado' }}
                                    </td>

                                    <td>
                                        {{ $ativo->updated_at
                                            ? $ativo->updated_at->format('d/m/Y H:i')
                                            : '—' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px;">
                                        Nenhum ativo encontrado.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</body>
</html>
