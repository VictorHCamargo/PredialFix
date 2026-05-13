<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\ProfileController;
use App\Models\Chamado;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (públicas)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (exigem login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth.custom')->group(function () {
    // Redireciona / para o dashboard
    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', function () {
        $chamadosRecentes = Chamado::with(['local', 'tipoProblema'])
            ->latest('data_abertura')
            ->take(5)
            ->get();

        return view('dashboard', [
            'chamadosRecentes' => $chamadosRecentes,
            'totalChamados' => Chamado::count(),
            'emAndamento' => Chamado::where('status', 'em_andamento')->count(),
            'concluidos' => Chamado::where('status', 'concluido')->count(),
            'cancelados' => Chamado::where('status', 'cancelado')->count(),
        ]);
    })->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name(
        'profile.updatePassword',
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chamados
    Route::get('/chamados', [ChamadoController::class, 'index'])->name('chamados.index');
    Route::get('/chamados/create', [ChamadoController::class, 'create'])->name('chamados.create');
    Route::post('/chamados', [ChamadoController::class, 'store'])->name('chamados.store');
    Route::get('/chamados/{id}', [ChamadoController::class, 'show'])->name('chamados.show');
    Route::get('/chamados/{id}/edit', [ChamadoController::class, 'edit'])->name('chamados.edit');
    Route::put('/chamados/{id}', [ChamadoController::class, 'update'])->name('chamados.update');
    Route::patch('/chamados/{id}/status', [ChamadoController::class, 'updateStatus'])->name(
        'chamados.updateStatus',
    );
    Route::delete('/chamados/{id}', [ChamadoController::class, 'destroy'])->name(
        'chamados.destroy',
    );

    // Avaliações
    Route::get('/avaliar', [\App\Http\Controllers\FeedbackController::class, 'index'])->name(
        'avaliar.index',
    );
    Route::get('/avaliar/{id}', [\App\Http\Controllers\FeedbackController::class, 'create'])->name(
        'avaliar.create',
    );
    Route::post('/avaliar', [\App\Http\Controllers\FeedbackController::class, 'store'])->name(
        'avaliar.store',
    );
});
