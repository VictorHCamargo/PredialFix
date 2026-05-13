<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$accessLevels
     */
    public function handle(Request $request, Closure $next, ...$accessLevels): Response
    {
        $user = $request->user();

        // Se não há usuário, redirecionar para login
        if (!$user) {
            return redirect('login');
        }

        // Verificar se o nível de acesso está na lista permitida
        if (!in_array($user->nivel_acesso, $accessLevels)) {
            return response()
                ->view('errors.403', [
                    'message' => 'Acesso negado. Seu nível de acesso não permite acessar este recurso.'
                ], 403);
        }

        return $next($request);
    }
}
