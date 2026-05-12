<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller {
    /**
     * Display the user's profile.
     */
    public function show(Request $request): View {
        $user = $request->user();
        
        // Obter chamados do usuário
        $chamadosCriados = $user->chamadosCriados()
            ->latest()
            ->limit(5)
            ->get();
        
        // Obter feedbacks do usuário
        $feedbacks = $user->feedbacks()
            ->with('chamado')
            ->latest()
            ->limit(5)
            ->get();

        return view('profile.show', compact('user', 'chamadosCriados', 'feedbacks'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request): RedirectResponse {
        $request->validate([
            'senha_atual' => ['required', 'string'],
            'senha_nova' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'senha_atual.required' => 'A senha atual é obrigatória.',
            'senha_nova.required' => 'A nova senha é obrigatória.',
            'senha_nova.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
            'senha_nova.confirmed' => 'As senhas não conferem.',
        ]);

        $user = $request->user();

        // Verificar se a senha atual está correta
        if (!Hash::check($request->senha_atual, $user->senha)) {
            return back()->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
        }

        // Atualizar senha
        $user->update(['senha' => Hash::make($request->senha_nova)]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse {
        $request->validateWithBag('userDeletion', [
            'senha' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Verificar se a senha está correta
        if (!Hash::check($request->senha, $user->senha)) {
            return back()->withErrors(['senha' => 'Senha incorreta.']);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
