
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ativos', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->string('patrimonio')->unique();
            $table->string('numero_serie')->nullable();

            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->restrictOnDelete();

            $table->foreignId('local_id')
                ->nullable()
                ->constrained('locals')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('observacoes')->nullable();
            $table->string('status')->default('ativo');
            $table->string('qr_code')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ativos');
    }
};
