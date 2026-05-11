<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Exibe a tela de login.
     */
    public function showLogin()
    {
        // Se já estiver logado, manda pro dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Processa o login: verifica email + cod_entrada.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'cod_entrada' => ['required', 'integer'],
        ], [
            'email.required'       => 'O e-mail é obrigatório.',
            'email.email'          => 'Informe um e-mail válido.',
            'cod_entrada.required' => 'O código de entrada é obrigatório.',
            'cod_entrada.integer'  => 'O código deve ser numérico.',
        ]);

        // Busca o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Verifica se o usuário existe e se o cod_entrada bate
        if (! $user || (int) $user->cod_entrada !== (int) $request->cod_entrada) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['cod_entrada' => 'E-mail ou código incorretos.']);
        }

        // Faz o login (cria sessão)
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Faz o logout do usuário.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
