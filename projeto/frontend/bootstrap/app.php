<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Registra o alias 'auth.custom' para o nosso middleware de autenticação.
        // Usado nas rotas: Route::middleware('auth.custom')->group(...)
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\EnsureAuthenticated::class,
            'access.level' => \App\Http\Middleware\CheckAccessLevel::class,
        ]);

        // CORS para Mobile App - Permite requisições de qualquer origem
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
