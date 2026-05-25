<?php

namespace App\Http\Controllers\Api;

use App\Models\Chamado;
use App\Models\HistoricoStatusChamado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChamadoApiController extends Controller
{
    /**
     * Listar chamados do usuário
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Se não tem código de entrada, vê só seus chamados
        $query = Chamado::query();

        if (!$user->cod_entrada) {
            $query->where('id_usuario', $user->id);
        }

        // Filtros opcionais
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('tipo_chamado')) {
            $query->where('tipo_chamado', $request->tipo_chamado);
        }

        if ($request->has('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        // Paginação
        $perPage = $request->get('per_page', 10);
        $chamados = $query->with(['local', 'tipoProblema', 'usuario'])
            ->orderBy('prioridade', 'asc')
            ->orderBy('data_abertura', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $chamados,
        ]);
    }

    /**
     * Ver detalhes de um chamado
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $chamado = Chamado::with(['local', 'tipoProblema', 'usuario', 'historico', 'feedback'])->find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado não encontrado',
            ], 404);
        }

        // Verificar permissão
        if (!$user->cod_entrada && $chamado->id_usuario !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para acessar este chamado',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $chamado,
        ]);
    }

    /**
     * Criar novo chamado
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'descricao' => 'required|string',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problema,id_tipo',
            'tipo_chamado' => 'nullable|in:interno,externo',
        ]);

        try {
            $chamado = Chamado::create([
                'id_usuario' => $request->user()->id,
                'descricao' => $validated['descricao'],
                'id_local' => $validated['id_local'],
                'id_tipo' => $validated['id_tipo'],
                'tipo_chamado' => $validated['tipo_chamado'] ?? 'interno',
                'status' => 'pendente',
                'data_abertura' => now(),
            ]);

            // Registrar no histórico
            HistoricoStatusChamado::create([
                'id_chamado' => $chamado->id_chamado,
                'status_anterior' => null,
                'status_novo' => 'pendente',
                'descricao' => 'Chamado criado',
                'id_usuario' => $request->user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chamado criado com sucesso',
                'data' => $chamado->load(['local', 'tipoProblema']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar chamado: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Atualizar chamado
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado não encontrado',
            ], 404);
        }

        // Verificar permissão
        if (!$user->cod_entrada && $chamado->id_usuario !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para atualizar este chamado',
            ], 403);
        }

        $validated = $request->validate([
            'descricao' => 'nullable|string',
            'id_local' => 'nullable|exists:locais,id_local',
            'id_tipo' => 'nullable|exists:tipo_problema,id_tipo',
        ]);

        try {
            $chamado->update(array_filter($validated));

            return response()->json([
                'success' => true,
                'message' => 'Chamado atualizado com sucesso',
                'data' => $chamado->load(['local', 'tipoProblema']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar chamado: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Deletar chamado
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado não encontrado',
            ], 404);
        }

        // Apenas usuário que criou pode deletar
        if ($chamado->id_usuario !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para deletar este chamado',
            ], 403);
        }

        try {
            $chamado->delete();

            return response()->json([
                'success' => true,
                'message' => 'Chamado deletado com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar chamado: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Alterar status do chamado
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado não encontrado',
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pendente,em_andamento,concluido,cancelado',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'descricao' => 'nullable|string',
        ]);

        try {
            $statusAnterior = $chamado->status;

            // Atualizar status
            $chamado->update([
                'status' => $validated['status'],
                'prioridade' => $validated['prioridade'] ?? $chamado->prioridade,
                'data_ultimo_status' => now(),
                'id_usuario_responsavel' => $user->id,
            ]);

            // Registrar no histórico
            HistoricoStatusChamado::create([
                'id_chamado' => $chamado->id_chamado,
                'status_anterior' => $statusAnterior,
                'status_novo' => $validated['status'],
                'descricao' => $validated['descricao'] ?? "Status alterado de {$statusAnterior} para {$validated['status']}",
                'id_usuario' => $user->id,
                'prioridade_definida' => $validated['prioridade'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso',
                'data' => $chamado->load(['local', 'tipoProblema']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage(),
            ], 422);
        }
    }
}
