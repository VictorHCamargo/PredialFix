<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller {
    public function index() {
        $chamadosParaAvaliar = Chamado::with(['tipoProblema', 'usuario', 'local'])
            ->where('status', 'concluido')
            ->doesntHave('feedback')
            ->orderByDesc('data_conclusao')
            ->get();

        $feedbacksRegistrados = Feedback::with('chamado.tipoProblema')
            ->orderByDesc('data_feedback')
            ->get();

        return view('feedbacks.index', compact('chamadosParaAvaliar', 'feedbacksRegistrados'));
    }

    public function create(string $id) {
        $chamado = Chamado::with(['tipoProblema', 'local', 'usuario'])->findOrFail($id);

        if ($chamado->feedback) {
            return redirect()
                ->route('chamados.index')
                ->with('info', 'Este chamado ja foi avaliado.');
        }

        if ($chamado->status !== 'concluido') {
            return redirect()
                ->route('chamados.index')
                ->with('info', 'Apenas chamados concluidos podem ser avaliados.');
        }

        return view('feedbacks.create', compact('chamado'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'id_chamado' => 'required|exists:chamados,id_chamado',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        if (Feedback::where('id_chamado', $data['id_chamado'])->exists()) {
            return redirect()->back()->with('error', 'Este chamado ja foi avaliado.');
        }

        $data['data_feedback'] = now()->toDateString();

        Feedback::create($data);

        return redirect()
            ->route('chamados.index')
            ->with('success', 'Avaliacao registrada com sucesso!');
    }
}
