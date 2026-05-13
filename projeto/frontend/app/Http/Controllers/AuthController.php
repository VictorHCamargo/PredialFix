<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    /**
     * Exibe a tela de login.
     */
    public function showLogin() {
        // Se já estiver logado, manda pro dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Processa o login: verifica email + senha.
     */
    public function login(Request $request) {
        $request->validate(
            [
                'email' => ['required', 'email'],
                'senha' => ['required', 'string'],
            ],
            [
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'Informe um e-mail válido.',
                'senha.required' => 'A senha é obrigatória.',
                'senha.string' => 'A senha deve ser texto.',
            ],
        );

        // Busca o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Verifica se o usuário existe e se a senha bate
        if (!$user || !Hash::check($request->senha, $user->senha)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'E-mail ou senha incorretos.']);
        }

        // Verifica se o usuário está ativo
        if (!$user->ativo) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Usuário inativo. Contate o administrador.']);
        }

        // Faz o login (cria sessão)
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Exibe a tela de registro com seleção de nível de perfil.
     */
    public function showRegister() {
        return view('auth.register');
    }

    /**
     * Processa o registro de novo usuário.
     */
    public function register(Request $request) {
        $request->validate(
            [
                'nome' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:usuarios,email'],
                'senha' => ['required', 'string', 'min:8', 'confirmed'],
                'nivel_acesso' => ['required', 'in:professor,aluno,visitante'],
                'setor' => ['nullable', 'string'],
            ],
            [
                'nome.required' => 'O nome é obrigatório.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'Informe um e-mail válido.',
                'email.unique' => 'Este e-mail já está registrado.',
                'senha.required' => 'A senha é obrigatória.',
                'senha.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'senha.confirmed' => 'As senhas não conferem.',
                'nivel_acesso.required' => 'Selecione um nível de acesso.',
            ],
        );

        $user = User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'nivel_acesso' => $request->nivel_acesso,
            'setor' => $request->setor,
            'ativo' => true,
        ]);

        // Login automático após registro
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Conta criada com sucesso!');
    }

    /**
     * Faz o logout do usuário.
     */
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
