<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ChamadoApiController;
use App\Http\Controllers\Api\FeedbackApiController;
use App\Http\Controllers\Api\ReferenceApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Mobile App)
|--------------------------------------------------------------------------
| Endpoints para o aplicativo mobile Flutter
| Autenticação via Sanctum Tokens
*/

// ==================== AUTENTICAÇÃO (Pública) ====================
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/profile', [AuthApiController::class, 'profile'])->middleware('auth:sanctum');
});

// ==================== ROTAS PROTEGIDAS (Requer Autenticação) ====================
Route::middleware('auth:sanctum')->group(function () {
    // ---- CHAMADOS ----
    Route::prefix('chamados')->group(function () {
        Route::get('/', [ChamadoApiController::class, 'index']);           // Listar
        Route::post('/', [ChamadoApiController::class, 'store']);          // Criar
        Route::get('/{id}', [ChamadoApiController::class, 'show']);        // Ver detalhes
        Route::put('/{id}', [ChamadoApiController::class, 'update']);      // Atualizar
        Route::delete('/{id}', [ChamadoApiController::class, 'destroy']);  // Deletar
        Route::patch('/{id}/status', [ChamadoApiController::class, 'updateStatus']); // Alterar status
    });

    // ---- FEEDBACK / AVALIAÇÕES ----
    Route::prefix('feedback')->group(function () {
        Route::get('/', [FeedbackApiController::class, 'index']);       // Listar
        Route::post('/', [FeedbackApiController::class, 'store']);      // Criar
        Route::get('/{id}', [FeedbackApiController::class, 'show']);    // Ver
        Route::put('/{id}', [FeedbackApiController::class, 'update']);  // Atualizar
        Route::delete('/{id}', [FeedbackApiController::class, 'destroy']); // Deletar
    });
});

// ==================== REFERÊNCIAS (Públicas - Dados Estáticos) ====================
Route::prefix('reference')->group(function () {
    Route::get('/locais', [ReferenceApiController::class, 'getLocais']);
    Route::get('/tipos-problema', [ReferenceApiController::class, 'getTiposProblema']);
});
