<?php

namespace Database\Seeders;

use App\Models\Local;
use Illuminate\Database\Seeder;

/**
 * Preenche posições de EXEMPLO (fictícias) para os locais, ao redor do centro
 * definido em config/mapa.php. Não sobrescreve locais que já têm posição.
 *
 * Rodar com: php artisan db:seed --class=LocaisExemploSeeder
 */
class LocaisExemploSeeder extends Seeder
{
    // [metros ao norte, metros a leste] a partir do centro do mapa
    private const POSICOES = [
        'Showroom'             => [40, -30],
        'Pátio de veículos'    => [-20, -90],
        'Oficina mecânica'     => [-70, 40],
        'Funilaria e pintura'  => [-90, 110],
        'Lavagem'              => [-40, 150],
        'Estoque de peças'     => [20, 120],
        'Recepção'             => [55, -5],
        'Escritório de vendas' => [75, 35],
        'Sala de TI'           => [90, 80],
        'Almoxarifado'         => [-10, 60],
        'Test-drive (em rota)' => [450, 520],
    ];

    public function run(): void
    {
        $lat0 = (float) config('mapa.centro.lat');
        $lng0 = (float) config('mapa.centro.lng');

        foreach (self::POSICOES as $nome => [$norte, $leste]) {
            $local = Local::firstOrNew(['nome' => $nome]);

            // Não sobrescreve uma posição que já foi definida
            if ($local->exists && $local->latitude !== null && $local->longitude !== null) {
                continue;
            }

            $local->latitude = round($lat0 + $norte / 111320, 7);
            $local->longitude = round($lng0 + $leste / (111320 * cos(deg2rad($lat0))), 7);
            $local->save();
        }
    }
}