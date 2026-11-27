<?php

return [

    /*
    | Centro inicial do mapa (usado quando ainda não há locais com posição).
    | Os locais de EXEMPLO (LocaisExemploSeeder) são espalhados ao redor deste ponto.
    | Troque no .env quando souber o endereço real da empresa.
    */
    'centro' => [
        'lat' => (float) env('MAPA_CENTRO_LAT', -22.4967),
        'lng' => (float) env('MAPA_CENTRO_LNG', -44.5606),
    ],

    'zoom' => (int) env('MAPA_ZOOM', 17),

    // De quanto em quanto tempo o mapa recarrega a posição dos ativos
    'atualizacao_segundos' => (int) env('MAPA_ATUALIZACAO_SEGUNDOS', 30),

];