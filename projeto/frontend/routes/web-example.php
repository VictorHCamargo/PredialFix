<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstoqueInternoController;

// ============================================
// Rotas Públicas (Sem Autenticação)
// ============================================

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    // Registro
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Dashboard
Route::get('/', function () {
    return redirect()->route('chamados.index');
})
    ->name('dashboard')
    ->middleware('auth');

// ============================================
// Rotas Protegidas (Requer Autenticação)
// ============================================

Route::middleware('auth')->group(function () {
    // ============ PERFIL ============
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name(
        'profile.updatePassword',
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============ CHAMADOS ============
    Route::get('/chamados', [ChamadoController::class, 'index'])->name('chamados.index');
    Route::get('/chamados/create', [ChamadoController::class, 'create'])->name('chamados.create');
    Route::post('/chamados', [ChamadoController::class, 'store'])->name('chamados.store');
    Route::get('/chamados/{id}', [ChamadoController::class, 'show'])->name('chamados.show');
    Route::patch('/chamados/{id}/status', [ChamadoController::class, 'updateStatus'])->name(
        'chamados.updateStatus',
    );
    Route::put('/chamados/{id}', [ChamadoController::class, 'update'])->name('chamados.update');
    Route::delete('/chamados/{id}', [ChamadoController::class, 'destroy'])->name(
        'chamados.destroy',
    );

    // ============ ESTOQUE (apenas admin e gerente) ============
    // Você pode adicionar middleware customizado aqui se desejar
    Route::resource('estoque', EstoqueInternoController::class);
});

// ============================================
// Exemplo de Middleware Customizado
// ============================================
/*
// Adicione a seguinte lógica em app/Http/Middleware:

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccessLevel
{
    public function handle(Request $request, Closure $next, ...$levels)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (in_array(auth()->user()->nivel_acesso, $levels)) {
            return $next($request);
        }

        return redirect()->route('chamados.index')->with('error', 'Você não tem permissão para acessar esta página.');
    }
}

// Adicione ao Kernel.php:
protected $routeMiddleware = [
    // ...
    'access' => \App\Http\Middleware\CheckAccessLevel::class,
];

// Use nas rotas:
Route::middleware(['auth', 'access:administrador,gerente_manutencao'])->group(function () {
    Route::resource('estoque', EstoqueInternoController::class);
});
*/
