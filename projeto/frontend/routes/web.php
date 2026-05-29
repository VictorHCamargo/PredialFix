<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Chamado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.custom')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));

    Route::get('/dashboard', function () {
        $hoje = Carbon::today();
        $chamadosRecentes = Chamado::with(['local', 'tipoProblema', 'usuario'])
            ->latest('data_abertura')
            ->take(5)
            ->get();

        return view('dashboard', [
            'totalUsuarios' => User::count(),
            'totalChamados' => Chamado::count(),
            'chamadosPendentes' => Chamado::where('status', 'aberto')->count(),
            'chamadosResolvidosHoje' => Chamado::where('status', 'concluido')
                ->whereDate('data_conclusao', $hoje)
                ->count(),
            'emAndamento' => Chamado::where('status', 'em_andamento')->count(),
            'concluidos' => Chamado::where('status', 'concluido')->count(),
            'cancelados' => Chamado::where('status', 'cancelado')->count(),
            'chamadosRecentes' => $chamadosRecentes,
        ]);
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chamados', [ChamadoController::class, 'index'])->name('chamados.index');
    Route::get('/chamados/create', [ChamadoController::class, 'create'])->name('chamados.create');
    Route::post('/chamados', [ChamadoController::class, 'store'])->name('chamados.store');
    Route::get('/chamados/{id}', [ChamadoController::class, 'show'])->name('chamados.show');
    Route::get('/chamados/{id}/edit', [ChamadoController::class, 'edit'])->name('chamados.edit');
    Route::put('/chamados/{id}', [ChamadoController::class, 'update'])->name('chamados.update');
    Route::patch('/chamados/{id}/status', [ChamadoController::class, 'updateStatus'])->name('chamados.updateStatus');
    Route::delete('/chamados/{id}', [ChamadoController::class, 'destroy'])->name('chamados.destroy');

    Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::patch('/notificacoes/{id}/lida', [NotificacaoController::class, 'marcarLida'])->name('notificacoes.lida');
    Route::patch('/notificacoes/todas-lidas', [NotificacaoController::class, 'marcarTodasLidas'])->name('notificacoes.todasLidas');

    Route::middleware('access.level:administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{id}/toggle', [UserController::class, 'toggleAtivo'])->name('usuarios.toggle');
    });

    Route::get('/avaliar', [\App\Http\Controllers\FeedbackController::class, 'index'])->name('avaliar.index');
    Route::get('/avaliar/{id}', [\App\Http\Controllers\FeedbackController::class, 'create'])->name('avaliar.create');
    Route::post('/avaliar', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('avaliar.store');
});
