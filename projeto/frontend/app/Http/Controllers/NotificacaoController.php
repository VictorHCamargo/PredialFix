<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller {
    public function index() {
        $notificacoes = Notificacao::with('chamado')
            ->where('id_usuario', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notificacoes.index', compact('notificacoes'));
    }

    public function marcarLida(string $id) {
        $notificacao = Notificacao::where('id', $id)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        $notificacao->update(['lida' => true]);

        return back();
    }

    public function marcarTodasLidas() {
        Notificacao::where('id_usuario', Auth::id())->update(['lida' => true]);

        return back()->with('success', 'Todas as notificacoes marcadas como lidas.');
    }
}
