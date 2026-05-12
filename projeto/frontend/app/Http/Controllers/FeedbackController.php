<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Chamado;

class FeedbackController extends Controller
{
    public function index()
    {
        // Chamados concluídos que ainda não têm feedback
        $chamadosParaAvaliar = Chamado::with(['tipoProblema', 'usuario', 'local'])
            ->where('status', 'concluido')
            ->doesntHave('feedback')
            ->orderByDesc('data_conclusao')
            ->get();

        // Feedbacks já registrados
        $feedbacksRegistrados = Feedback::with('chamado.tipoProblema')
            ->orderByDesc('data_feedback')
            ->get();
        
        return view('feedbacks.index', compact('chamadosParaAvaliar', 'feedbacksRegistrados'));
    }

    public function create(string $id)
    {
        $chamado = Chamado::with(['tipoProblema', 'local', 'usuario'])->findOrFail($id);
        
        // Verifica se o chamado já tem feedback
        if ($chamado->feedback) {
            return redirect()->route('chamados.index')->with('info', 'Este chamado já foi avaliado.');
        }

        // Verifica se o chamado está concluído
        if ($chamado->status !== 'concluido') {
            return redirect()->route('chamados.index')->with('info', 'Apenas chamados concluídos podem ser avaliados.');
        }

        return view('feedbacks.create', compact('chamado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_chamado' => 'required|exists:chamados,id_chamado',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        // Verifica se já existe feedback para este chamado
        if (Feedback::where('id_chamado', $data['id_chamado'])->exists()) {
            return redirect()->back()->with('error', 'Este chamado já foi avaliado.');
        }

        $data['data_feedback'] = now()->toDateString();

        Feedback::create($data);
        return redirect()->route('chamados.index')->with('success', 'Avaliação registrada com sucesso!');
    }
}
