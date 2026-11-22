@php
    $primeiroAtivoId = \App\Models\Ativo::value('id') ?? 1;
@endphp

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

            <li>
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'ativo' : '' }}">
                    <span>⌂</span> Login
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'ativo' : '' }}">
                    <span>▦</span> Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('ativos.index') }}" class="{{ request()->routeIs('ativos.index') ? 'ativo' : '' }}">
                    <span>▤</span> Lista de ativos
                </a>
            </li>

            <li>
                <a href="{{ route('ativos.create') }}" class="{{ request()->routeIs('ativos.create') ? 'ativo' : '' }}">
                    <span>＋</span> Cadastro de ativo
                </a>
            </li>

            <li>
                <a href="{{ route('ativos.show', $primeiroAtivoId) }}" class="{{ request()->routeIs('ativos.show') ? 'ativo' : '' }}">
                    <span>▣</span> Detalhes do ativo
                </a>
            </li>

            <li>
                <a href="{{ route('ativos.index') }}">
                    <span>▦</span> Leitura de QR Code
                </a>
            </li>

            <li>
                <a href="{{ route('ativos.index') }}">
                    <span>⌖</span> Registro por GPS
                </a>
            </li>

            <li>
                <a href="{{ route('mapa.index') }}" class="{{ request()->routeIs('mapa.index') ? 'ativo' : '' }}">
                 <span>▧</span> Mapa de ativos
                 </a>
            </li>
            
            <li>
                <a href="{{ route('historicos.index') }}" class="{{ request()->routeIs('historicos.index') ? 'ativo' : '' }}">
                    <span>◷</span> Histórico
                </a>
            </li>

        </ul>
    </nav>

    <div class="gps-lateral">
        <h4>◎ QR + GPS ativo</h4>
        <p>Registros sincronizados</p>
        <span>● Sistema operacional</span>
    </div>

</aside>