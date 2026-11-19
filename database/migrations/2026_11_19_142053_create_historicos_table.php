
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
        Schema::create('historicos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ativo_id')
                ->constrained('ativos')
                ->cascadeOnDelete();

            $table->foreignId('local_anterior_id')
                ->nullable()
                ->constrained('locals')
                ->nullOnDelete();

            $table->foreignId('local_novo_id')
                ->nullable()
                ->constrained('locals')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('tipo')->default('movimentacao');
            $table->text('descricao')->nullable();
            $table->timestamp('data_movimentacao')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historicos');
    }
};
