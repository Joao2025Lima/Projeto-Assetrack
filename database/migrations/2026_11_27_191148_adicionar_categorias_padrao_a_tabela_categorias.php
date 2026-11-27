
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categorias = [
            'Veículos',
            'Informática',
            'Ferramentas de oficina',
            'Equipamentos de diagnóstico',
            'Equipamentos de lavagem',
            'Mobiliário',
            'Telefonia e eletrônicos',
            'Segurança',
        ];

        foreach ($categorias as $nome) {
            $existe = DB::table('categorias')
                ->where('nome', $nome)
                ->exists();

            if (!$existe) {
                DB::table('categorias')->insert([
                    'nome' => $nome,
                    'descricao' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Não remove categorias para preservar os dados existentes.
    }
};
