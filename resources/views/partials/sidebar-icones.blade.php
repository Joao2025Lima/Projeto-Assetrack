@php
    $primeiroAtivoId = \App\Models\Ativo::value('id');
@endphp

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

        <li>
            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24">
                    <rect x="5" y="11" width="14" height="10" rx="2"/>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4M12 15v3"/>
                </svg>
                Login
            </a>
        </li>

        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24">
                    <rect x="4" y="4" width="7" height="7" rx="1"/>
                    <rect x="13" y="4" width="7" height="7" rx="1"/>
                    <rect x="4" y="13" width="7" height="7" rx="1"/>
                    <rect x="13" y="13" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('ativos.index') }}" class="{{ request()->routeIs('ativos.index') ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24">
                    <rect x="4" y="5" width="16" height="14" rx="2"/>
                    <path d="M8 9h8M8 13h8M8 16h4"/>
                </svg>
                Lista de ativos
            </a>
        </li>

        <li>
            <a href="{{ route('ativos.create') }}" class="{{ request()->routeIs('ativos.create') ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24">
                    <circle cx="9" cy="8" r="3"/>
                    <path d="M3 20v-2a6 6 0 0 1 12 0v2M19 8v8M15 12h8"/>
                </svg>
                Cadastro de ativo
            </a>
        </li>

        <li>
            <a href="{{ $primeiroAtivoId ? route('ativos.show', $primeiroAtivoId) : route('ativos.index') }}" class="{{ request()->routeIs('ativos.show') ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24">
                    <path d="M4 5h16v14H4zM8 9h8M8 13h5M8 16h3"/>
                </svg>
                Detalhes do ativo
            </a>
        </li>

        {{-- Provisório: ainda sem tela própria --}}
        <li>
            <a href="{{ route('ativos.index') }}">
                <svg viewBox="0 0 24 24">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2v2h-2zM18 14h2v6h-2zM14 18h2v2h-2z"/>
                </svg>
                Leitura de QR Code
            </a>
        </li>

        {{-- Provisório: ainda sem tela própria --}}
        <li>
            <a href="{{ route('ativos.index') }}">
                <svg viewBox="0 0 24 24">
                    <path d="M12 22s8-7.2 8-13a8 8 0 1 0-16 0c0 5.8 8 13 8 13z"/>
                    <circle cx="12" cy="9" r="2.5"/>
                </svg>
                Registro por GPS
            </a>
        </li>

        <li>
            <a href="{{ route('mapa.index') }}" class="{{ request()->routeIs('mapa.index') ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24">
                    <path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"/>
                </svg>
                Mapa de ativos
            </a>
        </li>

        <li>
            <a href="{{ route('historicos.index') }}" class="{{ request()->routeIs('historicos.index') ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24">
                    <path d="M3 12a9 9 0 1 0 2.6-6.4L3 8"/>
                    <path d="M3 3v5h5M12 7v5l3 2"/>
                </svg>
                Histórico
            </a>
        </li>

    </ul>

    <div class="informacao-menu">
        <div class="info-titulo">
            <svg viewBox="0 0 24 24">
                <path d="M4 10a12 12 0 0 1 16 0M7 13a7 7 0 0 1 10 0M10 16a3 3 0 0 1 4 0"/>
                <path d="M12 20h.01"/>
            </svg>
            <strong>QR + GPS ativo</strong>
        </div>
        <p>Registros móveis sincronizados com mapa corporativo em tempo real</p>
    </div>

</nav>