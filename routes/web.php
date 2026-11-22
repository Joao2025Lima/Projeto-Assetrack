
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\AtivoController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\MapaController;

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

        // MAPA DE ATIVOS (última localização e último usuário)
    Route::get('/mapa', [MapaController::class, 'index'])->name('mapa.index');
    
        // MOVIMENTAÇÃO
    Route::get('/movimentar/{ativo}', [MovimentacaoController::class, 'create'])->name('movimentar.create');
    Route::post('/movimentar', [MovimentacaoController::class, 'store'])->name('movimentar.store');

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // CATEGORIAS
    Route::get('/categorias', [CategoriaController::class, 'index'])
        ->name('categorias.index');

    Route::post('/categorias', [CategoriaController::class, 'store'])
        ->name('categorias.store');

    Route::get('/categorias/{id}', [CategoriaController::class, 'show'])
        ->name('categorias.show');

    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])
        ->name('categorias.update');

    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])
        ->name('categorias.destroy');

    // LOCAIS
    Route::get('/locais', [LocalController::class, 'index'])
        ->name('locais.index');

    Route::post('/locais', [LocalController::class, 'store'])
        ->name('locais.store');

    Route::get('/locais/{id}', [LocalController::class, 'show'])
        ->name('locais.show');

    Route::put('/locais/{id}', [LocalController::class, 'update'])
        ->name('locais.update');

    Route::delete('/locais/{id}', [LocalController::class, 'destroy'])
        ->name('locais.destroy');

    // EQUIPAMENTOS (ATIVOS)

    // Cadastro precisa vir antes da rota /ativos/{id}
    Route::get('/ativos/create', [AtivoController::class, 'create'])
        ->name('ativos.create');

    Route::get('/ativos', [AtivoController::class, 'index'])
        ->name('ativos.index');

    Route::post('/ativos', [AtivoController::class, 'store'])
        ->name('ativos.store');

    Route::get('/ativos/{id}', [AtivoController::class, 'show'])
        ->name('ativos.show');

    Route::put('/ativos/{id}', [AtivoController::class, 'update'])
        ->name('ativos.update');

    Route::delete('/ativos/{id}', [AtivoController::class, 'destroy'])
        ->name('ativos.destroy');

    // LEITURA DE QR CODE
    Route::get('/leitura/{id}', [AtivoController::class, 'show'])
        ->name('leitura.qrcode');

    // HISTÓRICO
    Route::get('/historicos', [HistoricoController::class, 'index'])
        ->name('historicos.index');

    Route::get('/historicos/ativo/{id}', [HistoricoController::class, 'show'])
        ->name('historicos.show');

    Route::post('/historicos', [HistoricoController::class, 'store'])
        ->name('historicos.store');
});

// PÁGINA INICIAL
Route::get('/', function () {
    return redirect('/login');
});
