<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Chamado;
use App\Http\Controllers\ChamadoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $chamadosRecentes = Chamado::with(['local', 'tipoProblema'])
        ->latest('data_abertura')
        ->take(5)
        ->get();

    return view('dashboard', [
        'chamadosRecentes' => $chamadosRecentes,
        'totalChamados'    => Chamado::count(),
        'emAndamento'      => Chamado::where('status', 'em_andamento')->count(),
        'concluidos'       => Chamado::where('status', 'concluido')->count(),
        'cancelados'       => Chamado::where('status', 'cancelado')->count(),
    ]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chamados',[ChamadoController::class, 'index'])->name('chamados.index');
    Route::get('/chamados/create',[ChamadoController::class, 'create'])->name('chamados.create');
});

require __DIR__ . '/auth.php';
