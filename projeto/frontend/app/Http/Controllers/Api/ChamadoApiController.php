<?php

namespace App\Http\Controllers\Api;

use App\Helpers\NotificacaoHelper;
use App\Http\Controllers\Controller;
use App\Models\Chamado;
use App\Models\HistoricoStatusChamado;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChamadoApiController extends Controller {
    public function index(Request $request): JsonResponse {
        $user = $request->user();
        $query = Chamado::query()->with(['local', 'tipoProblema', 'usuario', 'usuarioResponsavel']);

        if (!$user->cod_entrada) {
            $query->where('id_usuario', $user->id_usuario);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        $perPage = (int) $request->get('per_page', 10);
        $chamados = $query
            ->orderByRaw(
                "CASE WHEN prioridade='alta' THEN 1 WHEN prioridade='media' THEN 2 WHEN prioridade='baixa' THEN 3 ELSE 4 END",
            )
            ->orderByDesc('data_abertura')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $chamados,
        ]);
    }

    public function show(Request $request, $id): JsonResponse {
        $user = $request->user();
        $chamado = Chamado::with([
            'local',
            'tipoProblema',
            'usuario',
            'usuarioResponsavel',
            'historicoStatus.usuario',
            'feedback',
        ])->find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado nao encontrado',
            ], 404);
        }

        if (!$user->cod_entrada && $chamado->id_usuario !== $user->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissao para acessar este chamado',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $chamado,
        ]);
    }

    public function store(Request $request): JsonResponse {
        $user = $request->user();

        if ($user->isAluno()) {
            return response()->json([
                'success' => false,
                'message' => 'Alunos nao podem criar chamados',
            ], 403);
        }

        $validated = $request->validate([
            'descricao' => 'required|string',
            'id_patrimonio' => 'nullable|string|max:100',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problemas,id_tipo',
            'id_equipamento' => 'nullable|exists:equipamentos,id_equipamento',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'secao_tecnica' => 'nullable|in:eletrica,hidraulica,civil,mecanica',
            'complexidade' => 'nullable|in:simples,media,complexa',
            'tipo_trabalho' => 'nullable|in:preventiva,corretiva,melhoria',
            'tipo_chamado' => 'nullable|in:interno',
            'confirmar_duplicado' => 'nullable|boolean',
        ]);

        $idPatrimonio = trim((string) ($validated['id_patrimonio'] ?? ''));
        if ($idPatrimonio !== '') {
            $chamadoExistente = Chamado::where('id_patrimonio', $idPatrimonio)
                ->whereIn('status', ['aberto', 'em_andamento'])
                ->first();

            if ($chamadoExistente && !$request->boolean('confirmar_duplicado')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ja existe um chamado ativo para este patrimonio.',
                    'alerta_duplicado' => $chamadoExistente->id_chamado,
                ], 409);
            }
        }

        try {
            if (!$user->isAdmin() && !$user->isEquipeManutencao()) {
                unset($validated['prioridade'], $validated['secao_tecnica'], $validated['complexidade'], $validated['tipo_trabalho']);
            }

            $chamado = Chamado::create([
                'id_usuario' => $user->id_usuario,
                'descricao' => $validated['descricao'],
                'id_patrimonio' => $idPatrimonio !== '' ? $idPatrimonio : null,
                'id_local' => $validated['id_local'],
                'id_tipo' => $validated['id_tipo'],
                'id_equipamento' => $validated['id_equipamento'] ?? null,
                'prioridade' => $validated['prioridade'] ?? null,
                'secao_tecnica' => $validated['secao_tecnica'] ?? null,
                'complexidade' => $validated['complexidade'] ?? null,
                'tipo_trabalho' => $validated['tipo_trabalho'] ?? null,
                'tipo_chamado' => 'interno',
                'status' => 'aberto',
                'data_abertura' => now(),
                'data_ultimo_status' => now(),
            ]);

            HistoricoStatusChamado::create([
                'id_chamado' => $chamado->id_chamado,
                'status_anterior' => 'aberto',
                'status_novo' => 'aberto',
                'descricao_mudanca' => 'Chamado criado por ' . $user->nome,
                'id_usuario' => $user->id_usuario,
                'prioridade' => $chamado->prioridade,
            ]);

            NotificacaoHelper::disparar(
                'Novo chamado #' . $chamado->id_chamado . ' aberto por ' . $user->nome . '.',
                'criacao',
                $chamado->id_chamado,
                NotificacaoHelper::obterDestinatarios('criacao', $chamado, $user),
            );

            return response()->json([
                'success' => true,
                'message' => 'Chamado criado com sucesso',
                'data' => $chamado->load(['local', 'tipoProblema', 'usuario']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar chamado: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, $id): JsonResponse {
        $user = $request->user();
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado nao encontrado',
            ], 404);
        }

        if ($user->isAluno()) {
            return response()->json([
                'success' => false,
                'message' => 'Sua funcao nao tem permissao para atualizar este chamado',
            ], 403);
        }

        if ($user->isProfessor()) {
            if ($chamado->id_usuario !== $user->id_usuario || !in_array($chamado->status, ['aberto', 'em_andamento'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voce so pode editar seus proprios chamados enquanto estiverem abertos ou em andamento',
                ], 403);
            }

            $validated = $request->validate([
                'descricao' => 'required|string',
            ]);

            $chamado->update($validated);

            HistoricoStatusChamado::create([
                'id_chamado' => $chamado->id_chamado,
                'status_anterior' => $chamado->status,
                'status_novo' => $chamado->status,
                'descricao_mudanca' => 'Descricao editada pelo professor: ' . $user->nome,
                'id_usuario' => $user->id_usuario,
            ]);

            NotificacaoHelper::disparar(
                'Chamado #' . $chamado->id_chamado . ' foi atualizado por ' . $user->nome . '.',
                'edicao',
                $chamado->id_chamado,
                NotificacaoHelper::obterDestinatarios('edicao', $chamado, $user),
            );

            return response()->json([
                'success' => true,
                'message' => 'Chamado atualizado com sucesso',
                'data' => $chamado->load(['local', 'tipoProblema', 'usuario']),
            ]);
        }

        if (!$user->canEditTicket($chamado)) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissao para atualizar este chamado',
            ], 403);
        }

        $validated = $request->validate([
            'descricao' => 'required|string',
            'id_patrimonio' => 'nullable|string|max:100',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problemas,id_tipo',
            'id_equipamento' => 'nullable|exists:equipamentos,id_equipamento',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'secao_tecnica' => 'nullable|in:eletrica,hidraulica,civil,mecanica',
            'complexidade' => 'nullable|in:simples,media,complexa',
            'tipo_trabalho' => 'nullable|in:preventiva,corretiva,melhoria',
        ]);

        $validated['tipo_chamado'] = 'interno';
        $original = $chamado->only([
            'descricao',
            'id_patrimonio',
            'tipo_chamado',
            'id_local',
            'id_tipo',
            'id_equipamento',
            'prioridade',
            'secao_tecnica',
            'complexidade',
            'tipo_trabalho',
        ]);

        try {
            $validated['id_patrimonio'] = isset($validated['id_patrimonio']) ? trim((string) $validated['id_patrimonio']) : null;
            if ($validated['id_patrimonio'] === '') {
                $validated['id_patrimonio'] = null;
            }

            $chamado->update($validated);

            $mudancas = [];
            foreach ($original as $campo => $valorAnterior) {
                if ($chamado->$campo != $valorAnterior) {
                    $mudancas[] = $campo;
                }
            }

            HistoricoStatusChamado::create([
                'id_chamado' => $chamado->id_chamado,
                'status_anterior' => $chamado->status,
                'status_novo' => $chamado->status,
                'descricao_mudanca' => $mudancas
                    ? 'Campos alterados: ' . implode(', ', $mudancas) . '.'
                    : 'Chamado atualizado por ' . $user->nome . '.',
                'id_usuario' => $user->id_usuario,
                'prioridade' => $chamado->prioridade,
            ]);

            if (in_array('prioridade', $mudancas, true)) {
                NotificacaoHelper::disparar(
                    'Prioridade do chamado #' . $chamado->id_chamado . ' foi alterada para ' . ucfirst((string) $chamado->prioridade) . '.',
                    'prioridade',
                    $chamado->id_chamado,
                    NotificacaoHelper::obterDestinatarios('prioridade', $chamado, $user),
                );
            }

            if (in_array('complexidade', $mudancas, true)) {
                NotificacaoHelper::disparar(
                    'Complexidade do chamado #' . $chamado->id_chamado . ' foi atualizada.',
                    'complexidade',
                    $chamado->id_chamado,
                    NotificacaoHelper::obterDestinatarios('complexidade', $chamado, $user),
                );
            }

            if (!empty(array_diff($mudancas, ['prioridade', 'complexidade']))) {
                NotificacaoHelper::disparar(
                    'Chamado #' . $chamado->id_chamado . ' foi atualizado.',
                    'edicao',
                    $chamado->id_chamado,
                    NotificacaoHelper::obterDestinatarios('edicao', $chamado, $user),
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Chamado atualizado com sucesso',
                'data' => $chamado->load(['local', 'tipoProblema', 'usuario']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar chamado: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function destroy(Request $request, $id): JsonResponse {
        $user = $request->user();
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado nao encontrado',
            ], 404);
        }

        if (!$user->isAdmin() && !$user->isEquipeManutencao()) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissao para cancelar este chamado',
            ], 403);
        }

        if ($chamado->status === 'cancelado') {
            return response()->json([
                'success' => false,
                'message' => 'Este chamado ja esta cancelado',
            ], 422);
        }

        $validated = $request->validate([
            'justificativa_cancelamento' => 'required|string|min:10',
        ]);

        try {
            $this->cancelarChamado($chamado, $validated['justificativa_cancelamento'], $user);

            return response()->json([
                'success' => true,
                'message' => 'Chamado cancelado com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cancelar chamado: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function updateStatus(Request $request, $id): JsonResponse {
        $user = $request->user();
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json([
                'success' => false,
                'message' => 'Chamado nao encontrado',
            ], 404);
        }

        if ($user->isProfessor() || $user->isAluno()) {
            return response()->json([
                'success' => false,
                'message' => 'Sua funcao nao tem permissao para alterar o status de chamados',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:aberto,em_andamento,concluido,cancelado',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'status_descricao' => 'nullable|string',
        ]);

        $statusAtual = $chamado->status;
        $novoStatus = $validated['status'];
        $descricao = trim((string) ($validated['status_descricao'] ?? ''));
        $responsavelAnterior = $chamado->id_usuario_responsavel;

        if (!$this->validarTransicaoStatus($user, $statusAtual, $novoStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Voce nao tem permissao para realizar esta alteracao de status',
            ], 403);
        }

        if ($novoStatus === 'cancelado' && mb_strlen($descricao) < 10) {
            return response()->json([
                'success' => false,
                'message' => 'A justificativa de cancelamento precisa ter pelo menos 10 caracteres',
            ], 422);
        }

        if ($novoStatus === 'em_andamento' && !empty($validated['prioridade'])) {
            $chamado->prioridade = $validated['prioridade'];
        }

        try {
            $chamado->status = $novoStatus;
            $chamado->status_descricao = $descricao !== '' ? $descricao : null;
            $chamado->id_usuario_responsavel = $user->id_usuario;
            $chamado->data_ultimo_status = now();
            $chamado->data_conclusao = $novoStatus === 'concluido' ? now() : null;

            if ($novoStatus === 'cancelado') {
                $chamado->data_conclusao = null;
            }

            $chamado->save();

            HistoricoStatusChamado::create([
                'id_chamado' => $chamado->id_chamado,
                'status_anterior' => $statusAtual,
                'status_novo' => $novoStatus,
                'descricao_mudanca' => $descricao !== ''
                    ? $descricao
                    : 'Status alterado de ' . $statusAtual . ' para ' . $novoStatus,
                'id_usuario' => $user->id_usuario,
                'prioridade' => $validated['prioridade'] ?? null,
            ]);

            $mensagem = 'Chamado #' . $chamado->id_chamado . ' teve o status alterado para ' . str_replace('_', ' ', $novoStatus) . '.';
            if ($novoStatus === 'cancelado' && $descricao !== '') {
                $mensagem = 'Chamado #' . $chamado->id_chamado . ' foi cancelado: ' . $descricao;
            }

            $tipoNotificacao = $novoStatus === 'cancelado' ? 'cancelamento' : 'status';

            NotificacaoHelper::disparar(
                $mensagem,
                $tipoNotificacao,
                $chamado->id_chamado,
                NotificacaoHelper::obterDestinatarios($tipoNotificacao, $chamado, $user),
            );

            if ($responsavelAnterior !== $chamado->id_usuario_responsavel) {
                NotificacaoHelper::disparar(
                    'Responsavel definido para o chamado #' . $chamado->id_chamado . '.',
                    'atribuicao',
                    $chamado->id_chamado,
                    NotificacaoHelper::obterDestinatarios('atribuicao', $chamado, $user),
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso',
                'data' => $chamado->load(['local', 'tipoProblema', 'usuario']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function cancelarChamado(Chamado $chamado, string $justificativa, User $user): void {
        $statusAnterior = $chamado->status;

        $chamado->status = 'cancelado';
        $chamado->status_descricao = $justificativa;
        $chamado->data_ultimo_status = now();
        $chamado->data_conclusao = null;
        $chamado->id_usuario_responsavel = $user->id_usuario;
        $chamado->save();

        HistoricoStatusChamado::create([
            'id_chamado' => $chamado->id_chamado,
            'status_anterior' => $statusAnterior,
            'status_novo' => 'cancelado',
            'descricao_mudanca' => $justificativa,
            'id_usuario' => $user->id_usuario,
        ]);

        NotificacaoHelper::disparar(
            'Chamado #' . $chamado->id_chamado . ' foi cancelado: ' . $justificativa,
            'cancelamento',
            $chamado->id_chamado,
            NotificacaoHelper::obterDestinatarios('cancelamento', $chamado, $user),
        );
    }

    private function validarTransicaoStatus(User $user, string $statusAtual, string $novoStatus): bool {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->isEquipeManutencao()) {
            return false;
        }

        if ($novoStatus === 'cancelado') {
            return true;
        }

        if ($statusAtual === 'aberto' && $novoStatus === 'em_andamento') {
            return true;
        }

        if ($statusAtual === 'em_andamento' && $novoStatus === 'concluido') {
            return true;
        }

        return false;
    }
}
