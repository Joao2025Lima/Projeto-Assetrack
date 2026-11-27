<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AsseTrack - Mapa de ativos</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="{{ asset('css/mapa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mapa-gps.css') }}">
    <style>
        .status.sem-localizacao { background: #fde8ea; color: #a1212e; }
    </style>
</head>
<body>

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

        <section class="area-mapa area-mapa-ampla">

            <div class="titulo-area">
                <h2>Onde estão os ativos e onde você está</h2>
                <p>Sua posição é atualizada em tempo real pelo GPS do aparelho. Os ativos aparecem na última posição conhecida do local onde foram registrados.</p>
            </div>

            <div class="gm-layout">

                <div class="gm-mapa-caixa">
                    <div id="mapa" class="gm-mapa"></div>

                    <div class="gm-legenda">
                        <span><i class="gm-ponto ok"></i> Localizado</span>
                        <span><i class="gm-ponto atencao"></i> Sem atualização (+72h)</span>
                        <span><i class="gm-ponto eu"></i> Você</span>
                        <span>O número no pino é a quantidade de ativos no local.</span>
                    </div>

                    <div class="gm-barra">
                        <button type="button" class="gm-btn primario" id="btnMim">Centralizar em mim</button>
                        <button type="button" class="gm-btn" id="btnTodos">Ver todos os ativos</button>
                        <span class="gm-atualizacao" id="ultimaAtualizacao"></span>
                    </div>
                </div>

                <aside class="gm-lateral">

                    <div class="gm-painel">
                        <h3>Minha localização</h3>
                        <div class="gm-gps-linha">
                            <i class="gm-gps-dot" id="gpsDot"></i>
                            <span id="gpsTexto">Aguardando permissão do GPS...</span>
                        </div>
                        <p id="gpsDetalhe"></p>
                        <button type="button" class="gm-btn" id="btnGps" hidden>Tentar novamente</button>
                    </div>

                    <div class="gm-painel">
                        <h3>Ativos</h3>
                        <input type="search" id="busca" class="gm-busca" placeholder="Buscar por nome, patrimônio ou local">
                        <div class="gm-lista" id="listaAtivos"></div>
                    </div>

                    <div class="gm-painel">
                        <details id="painelDefinir">
                            <summary>Definir posição dos locais</summary>
                            <p>As posições atuais são de exemplo. Escolha um local e marque a posição real no mapa, ou use a sua posição atual se estiver no lugar.</p>

                            <select id="selLocal"></select>

                            <div class="gm-acoes">
                                <button type="button" class="gm-btn" id="btnMarcar">Marcar no mapa</button>
                                <button type="button" class="gm-btn" id="btnUsarMinha" disabled>Usar minha posição</button>
                                <button type="button" class="gm-btn primario" id="btnSalvar" disabled>Salvar</button>
                            </div>

                            <div class="gm-msg" id="defMsg"></div>
                        </details>
                    </div>

                </aside>
            </div>

        </section>

    </main>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    'use strict';

    var CFG = {{ \Illuminate\Support\Js::from($configMapa) }};
    var dados = {{ \Illuminate\Support\Js::from($dados) }};
    var URL_DADOS = @json(route('mapa.dados'));
    var URL_COORD = @json(route('locais.coordenadas', ['id' => '__ID__']));
    var CSRF = document.querySelector('meta[name="csrf-token"]').content;

    function $(id) { return document.getElementById(id); }

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    // Distância em metros entre dois pontos (fórmula de Haversine)
    function distancia(a, b) {
        var R = 6371000, rad = Math.PI / 180;
        var dLat = (b.lat - a.lat) * rad, dLng = (b.lng - a.lng) * rad;
        var x = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(a.lat * rad) * Math.cos(b.lat * rad) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
        return 2 * R * Math.asin(Math.sqrt(x));
    }

    function fmtDist(m) {
        return m < 1000 ? Math.round(m) + ' m' : (m / 1000).toFixed(1).replace('.', ',') + ' km';
    }

    function temPos(l) { return l && l.lat !== null && l.lng !== null; }

    /* ---------------- MAPA ---------------- */

    var mapa = L.map('mapa').setView([CFG.centro.lat, CFG.centro.lng], CFG.zoom);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapa);

    var camada = L.featureGroup().addTo(mapa);
    var marcadores = {};
    var localAberto = null;

    camada.on('popupopen', function (e) { localAberto = e.popup._source._localId; });
    camada.on('popupclose', function () { localAberto = null; });

    // O menu lateral abre/fecha e muda o tamanho do mapa
    if (window.ResizeObserver) {
        new ResizeObserver(function () { mapa.invalidateSize(); }).observe($('mapa'));
    }

    function grupos() {
        var g = {};
        dados.ativos.forEach(function (a) {
            if (!a.local || !temPos(a.local)) return;
            if (!g[a.local.id]) g[a.local.id] = { local: a.local, ativos: [] };
            g[a.local.id].ativos.push(a);
        });
        return g;
    }

    function popupHtml(local, ativos) {
        var dist = posUsuario ? '<small>A ' + fmtDist(distancia(posUsuario, local)) + ' de você</small>' : '';
        var itens = ativos.map(function (a) {
            return '<li><a href="' + esc(a.url) + '">' + esc(a.nome) + '</a>' +
                '<small>' + esc(a.patrimonio) + ' · ' + esc(a.status_texto) + '</small>' +
                '<small>Último usuário: ' + esc(a.ultimo_usuario || 'Nenhum registro') + '</small></li>';
        }).join('');
        return '<div class="gm-popup"><strong>' + esc(local.nome) + '</strong>' + dist + '<ul>' + itens + '</ul></div>';
    }

    function desenharMapa() {
        var reabrir = localAberto;
        camada.clearLayers();
        marcadores = {};

        var g = grupos();
        Object.keys(g).forEach(function (id) {
            var grupo = g[id];
            var atencao = grupo.ativos.some(function (a) { return a.status !== 'localizado'; });
            var icone = L.divIcon({
                className: 'gm-pino-wrap',
                html: '<div class="gm-pino' + (atencao ? ' atencao' : '') + '"><span>' + grupo.ativos.length + '</span></div>',
                iconSize: [34, 42],
                iconAnchor: [17, 41],
                popupAnchor: [0, -38]
            });
            var m = L.marker([grupo.local.lat, grupo.local.lng], { icon: icone, title: grupo.local.nome });
            m._localId = grupo.local.id;
            m.bindPopup(popupHtml(grupo.local, grupo.ativos), { maxWidth: 280 });
            m.addTo(camada);
            marcadores[id] = m;
        });

        if (reabrir && marcadores[reabrir]) marcadores[reabrir].openPopup();
    }

    function ajustarVista(incluirUsuario) {
        var pts = [];
        var g = grupos();
        Object.keys(g).forEach(function (id) { pts.push([g[id].local.lat, g[id].local.lng]); });
        if (incluirUsuario && posUsuario) pts.push([posUsuario.lat, posUsuario.lng]);
        if (!pts.length) return;
        if (pts.length === 1) { mapa.setView(pts[0], 18); return; }
        mapa.fitBounds(L.latLngBounds(pts), { padding: [50, 50], maxZoom: 18 });
    }

    /* ---------------- GPS DO USUÁRIO ---------------- */

    var posUsuario = null;
    var marcadorUsuario = null;
    var circuloPrecisao = null;
    var primeiraPosicao = true;
    var watchId = null;

    function setGps(estado, texto, detalhe) {
        $('gpsDot').className = 'gm-gps-dot ' + estado;
        $('gpsTexto').textContent = texto;
        $('gpsDetalhe').textContent = detalhe || '';
        $('btnGps').hidden = estado !== 'erro';
    }

    function aoReceberPosicao(p) {
        var lat = p.coords.latitude, lng = p.coords.longitude, prec = p.coords.accuracy || 0;
        posUsuario = { lat: lat, lng: lng, precisao: prec };

        if (!marcadorUsuario) {
            marcadorUsuario = L.marker([lat, lng], {
                icon: L.divIcon({ className: 'gm-pino-wrap', html: '<div class="gm-usuario"></div>', iconSize: [18, 18], iconAnchor: [9, 9] }),
                zIndexOffset: 1000,
                title: 'Você está aqui'
            }).addTo(mapa);
            circuloPrecisao = L.circle([lat, lng], { radius: prec, color: '#1e7fe0', fillColor: '#1e7fe0', fillOpacity: 0.12, weight: 1 }).addTo(mapa);
        } else {
            marcadorUsuario.setLatLng([lat, lng]);
            circuloPrecisao.setLatLng([lat, lng]).setRadius(prec);
        }

        var hora = new Date().toLocaleTimeString('pt-BR');
        setGps('ok', 'GPS ativo', 'Precisão de cerca de ' + Math.round(prec) + ' m · ' +
            lat.toFixed(6) + ', ' + lng.toFixed(6) + ' · ' + hora);

        $('btnUsarMinha').disabled = false;
        renderLista();

        if (primeiraPosicao) {
            primeiraPosicao = false;
            var g = grupos(), menor = null;
            Object.keys(g).forEach(function (id) {
                var d = distancia(posUsuario, g[id].local);
                if (menor === null || d < menor) menor = d;
            });
            if (menor === null || menor <= 20000) {
                ajustarVista(true);
            } else {
                setGps('ok', 'GPS ativo', 'Você está a ' + fmtDist(menor) + ' do ativo mais próximo. Use "Centralizar em mim" para ver sua posição.');
            }
        }
    }

    function aoFalharPosicao(err) {
        if (err.code === 1) {
            setGps('erro', 'Permissão negada', 'Libere a localização para este site nas configurações do navegador e tente novamente.');
        } else if (err.code === 2) {
            setGps('erro', 'Posição indisponível', 'Não foi possível obter o sinal de GPS agora.');
        } else {
            setGps('buscando', 'Buscando sinal de GPS...', 'Está demorando mais que o normal. Se estiver em ambiente fechado, vá para perto de uma janela.');
        }
    }

    function iniciarGps() {
        if (!('geolocation' in navigator)) {
            setGps('erro', 'GPS não suportado', 'Este navegador não oferece geolocalização.');
            return;
        }
        if (!window.isSecureContext) {
            setGps('erro', 'Conexão não segura', 'O navegador só libera o GPS em HTTPS ou em localhost. Acesse o sistema por um endereço HTTPS.');
            return;
        }
        if (watchId !== null) navigator.geolocation.clearWatch(watchId);
        setGps('buscando', 'Buscando sinal de GPS...', 'Aceite o pedido de permissão do navegador.');
        watchId = navigator.geolocation.watchPosition(aoReceberPosicao, aoFalharPosicao, {
            enableHighAccuracy: true,
            maximumAge: 5000,
            timeout: 20000
        });
    }

    /* ---------------- LISTA DE ATIVOS ---------------- */

    function renderLista() {
        var termo = $('busca').value.trim().toLowerCase();
        var itens = dados.ativos.map(function (a) {
            var d = (posUsuario && a.local && temPos(a.local)) ? distancia(posUsuario, a.local) : null;
            return { a: a, d: d };
        }).filter(function (i) {
            if (!termo) return true;
            var texto = (i.a.nome + ' ' + (i.a.patrimonio || '') + ' ' + (i.a.local ? i.a.local.nome : '')).toLowerCase();
            return texto.indexOf(termo) !== -1;
        });

        itens.sort(function (x, y) {
            if (x.d !== null && y.d !== null) return x.d - y.d;
            if (x.d !== null) return -1;
            if (y.d !== null) return 1;
            return x.a.nome.localeCompare(y.a.nome, 'pt-BR');
        });

        if (!itens.length) {
            $('listaAtivos').innerHTML = '<p>Nenhum ativo encontrado.</p>';
            return;
        }

        $('listaAtivos').innerHTML = itens.map(function (i) {
            var a = i.a, onde, clicavel = a.local && temPos(a.local);
            if (!a.local) onde = 'Sem local registrado';
            else if (!clicavel) onde = esc(a.local.nome) + ' · sem posição no mapa';
            else onde = esc(a.local.nome) + (i.d !== null ? ' · a ' + fmtDist(i.d) : '');
            return '<div class="gm-item' + (clicavel ? '' : ' sem-pos') + '"' +
                (clicavel ? ' data-local="' + a.local.id + '"' : '') + '>' +
                '<div class="gm-item-topo"><strong>' + esc(a.nome) + '</strong>' +
                '<span class="status ' + esc(a.status) + '">' + esc(a.status_texto) + '</span></div>' +
                '<small>' + esc(a.patrimonio || '') + '</small><small>' + onde + '</small></div>';
        }).join('');
    }

    $('listaAtivos').addEventListener('click', function (e) {
        var el = e.target.closest('[data-local]');
        if (!el) return;
        var m = marcadores[el.getAttribute('data-local')];
        if (!m) return;
        mapa.flyTo(m.getLatLng(), Math.max(mapa.getZoom(), 18));
        mapa.once('moveend', function () { m.openPopup(); });
    });

    $('busca').addEventListener('input', renderLista);

    /* ---------------- DEFINIR POSIÇÃO DOS LOCAIS ---------------- */

    var marcando = false;
    var marcadorTemp = null;
    var posTemp = null;

    function msg(texto, tipo) {
        var el = $('defMsg');
        el.textContent = texto || '';
        el.className = 'gm-msg' + (tipo ? ' ' + tipo : '');
    }

    function preencherLocais() {
        var sel = $('selLocal'), atual = sel.value;
        sel.innerHTML = dados.locais.map(function (l) {
            return '<option value="' + l.id + '">' + esc(l.nome) + (temPos(l) ? '' : ' (sem posição)') + '</option>';
        }).join('');
        if (atual) sel.value = atual;
    }

    function definirTemp(lat, lng) {
        posTemp = { lat: lat, lng: lng };
        var icone = L.divIcon({
            className: 'gm-pino-wrap',
            html: '<div class="gm-pino temp"><span>?</span></div>',
            iconSize: [34, 42], iconAnchor: [17, 41]
        });
        if (!marcadorTemp) {
            marcadorTemp = L.marker([lat, lng], { icon: icone, draggable: true, zIndexOffset: 900 }).addTo(mapa);
            marcadorTemp.on('dragend', function () {
                var p = marcadorTemp.getLatLng();
                definirTemp(p.lat, p.lng);
            });
        } else {
            marcadorTemp.setLatLng([lat, lng]);
        }
        $('btnSalvar').disabled = false;
        msg('Nova posição: ' + lat.toFixed(6) + ', ' + lng.toFixed(6) + '. Arraste o pino para ajustar e clique em Salvar.');
    }

    function limparTemp() {
        if (marcadorTemp) { mapa.removeLayer(marcadorTemp); marcadorTemp = null; }
        posTemp = null;
        $('btnSalvar').disabled = true;
    }

    function alternarMarcar(ligado) {
        marcando = ligado;
        $('mapa').classList.toggle('marcando', ligado);
        $('btnMarcar').classList.toggle('ativo', ligado);
        if (ligado) msg('Clique no mapa para marcar a posição do local.');
    }

    mapa.on('click', function (e) {
        if (!marcando) return;
        definirTemp(e.latlng.lat, e.latlng.lng);
    });

    $('btnMarcar').addEventListener('click', function () { alternarMarcar(!marcando); });

    $('btnUsarMinha').addEventListener('click', function () {
        if (!posUsuario) return;
        definirTemp(posUsuario.lat, posUsuario.lng);
        mapa.flyTo([posUsuario.lat, posUsuario.lng], 19);
    });

    $('selLocal').addEventListener('change', function () {
        limparTemp();
        var l = dados.locais.filter(function (x) { return String(x.id) === $('selLocal').value; })[0];
        if (l && temPos(l)) {
            mapa.flyTo([l.lat, l.lng], 18);
            msg('Posição atual: ' + l.lat.toFixed(6) + ', ' + l.lng.toFixed(6));
        } else {
            msg('Este local ainda não tem posição definida.');
        }
    });

    $('btnSalvar').addEventListener('click', function () {
        var id = $('selLocal').value;
        if (!id || !posTemp) return;
        $('btnSalvar').disabled = true;
        fetch(URL_COORD.replace('__ID__', id), {
            method: 'PUT',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ latitude: posTemp.lat, longitude: posTemp.lng })
        }).then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        }).then(function (j) {
            limparTemp();
            alternarMarcar(false);
            msg(j.mensagem || 'Posição salva!', 'ok');
            return atualizarDados();
        }).catch(function () {
            $('btnSalvar').disabled = false;
            msg('Não foi possível salvar. Verifique a conexão e tente de novo.', 'erro');
        });
    });

    /* ---------------- ATUALIZAÇÃO EM TEMPO REAL ---------------- */

    function atualizarDados() {
        return fetch(URL_DADOS, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        }).then(function (novo) {
            dados = novo;
            desenharMapa();
            renderLista();
            preencherLocais();
            $('ultimaAtualizacao').textContent = 'Atualizado às ' + new Date().toLocaleTimeString('pt-BR');
        }).catch(function () {
            $('ultimaAtualizacao').textContent = 'Sem conexão com o servidor. Tentando de novo...';
        });
    }

    setInterval(function () {
        if (!document.hidden) atualizarDados();
    }, Math.max(5, CFG.atualizacaoSegundos) * 1000);

    /* ---------------- BOTÕES E INÍCIO ---------------- */

    $('btnMim').addEventListener('click', function () {
        if (!posUsuario) { setGps('buscando', 'Ainda sem posição', 'Aguarde o sinal do GPS ou libere a permissão.'); return; }
        mapa.flyTo([posUsuario.lat, posUsuario.lng], 18);
    });

    $('btnTodos').addEventListener('click', function () { ajustarVista(false); });
    $('btnGps').addEventListener('click', iniciarGps);

    preencherLocais();
    desenharMapa();
    renderLista();
    ajustarVista(false);
    $('ultimaAtualizacao').textContent = 'Atualizado às ' + new Date().toLocaleTimeString('pt-BR');
    iniciarGps();
})();
</script>
</body>
</html>