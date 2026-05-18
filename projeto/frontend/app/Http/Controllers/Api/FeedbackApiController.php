<?php

namespace App\Http\Controllers\Api;

use App\Models\Feedback;
use App\Models\Chamado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackApiController extends Controller
{
    /**
     * Listar feedbacks do usuário
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $feedbacks = Feedback::where('id_usuario', $user->id)
            ->with(['chamado', 'usuario'])
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $feedbacks,
        ]);
    }

    /**
     * Ver detalhes de um feedback
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback não encontrado',
            ], 404);
        }

        // Verificar permissão
        if ($feedback->id_usuario !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para acessar este feedback',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $feedback->load(['chamado', 'usuario']),
        ]);
    }

    /**
     * Criar novo feedback (Avaliar chamado)
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'id_chamado' => 'required|exists:chamados,id_chamado',
            'avaliacao' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        try {
            // Verificar se o chamado pertence ao usuário e está concluído
            $chamado = Chamado::find($validated['id_chamado']);

            if ($chamado->id_usuario !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para avaliar este chamado',
                ], 403);
            }

            if ($chamado->status !== 'concluido') {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas chamados concluídos podem ser avaliados',
                ], 422);
            }

            // Verificar se já não foi avaliado
            $existingFeedback = Feedback::where('id_chamado', $validated['id_chamado'])->first();

            if ($existingFeedback) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este chamado já foi avaliado',
                ], 422);
            }

            // Criar feedback
            $feedback = Feedback::create([
                'id_chamado' => $validated['id_chamado'],
                'id_usuario' => $user->id,
                'avaliacao' => $validated['avaliacao'],
                'comentario' => $validated['comentario'],
                'data_avaliacao' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback criado com sucesso',
                'data' => $feedback->load(['chamado', 'usuario']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar feedback: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Atualizar feedback
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback não encontrado',
            ], 404);
        }

        // Verificar permissão
        if ($feedback->id_usuario !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para atualizar este feedback',
            ], 403);
        }

        $validated = $request->validate([
            'avaliacao' => 'nullable|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        try {
            $feedback->update(array_filter($validated));

            return response()->json([
                'success' => true,
                'message' => 'Feedback atualizado com sucesso',
                'data' => $feedback->load(['chamado', 'usuario']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar feedback: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Deletar feedback
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback não encontrado',
            ], 404);
        }

        // Verificar permissão
        if ($feedback->id_usuario !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para deletar este feedback',
            ], 403);
        }

        try {
            $feedback->delete();

            return response()->json([
                'success' => true,
                'message' => 'Feedback deletado com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar feedback: ' . $e->getMessage(),
            ], 422);
        }
    }
}
