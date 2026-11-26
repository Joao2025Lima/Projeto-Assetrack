<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Local;
use Illuminate\Database\Seeder;

class DadosIniciaisSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Veículos', 'Informática', 'Ferramentas de oficina',
            'Equipamentos de diagnóstico', 'Equipamentos de lavagem',
            'Mobiliário', 'Telefonia e eletrônicos', 'Segurança',
        ];

        foreach ($categorias as $nome) {
            Categoria::firstOrCreate(['nome' => $nome]);
        }

        $locais = [
            'Showroom', 'Pátio de veículos', 'Oficina mecânica',
            'Funilaria e pintura', 'Lavagem', 'Estoque de peças',
            'Recepção', 'Escritório de vendas', 'Sala de TI',
            'Almoxarifado', 'Test-drive (em rota)',
        ];

        foreach ($locais as $nome) {
            Local::firstOrCreate(['nome' => $nome]);
        }
    }
}