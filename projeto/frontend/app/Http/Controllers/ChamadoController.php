<?php

namespace App\Http\Controllers;

use App\Helpers\NotificacaoHelper;
use App\Models\Chamado;
use App\Models\Equipamento;
use App\Models\HistoricoStatusChamado;
use App\Models\Local;
use App\Models\TipoProblema;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChamadoController extends Controller {
    use AuthorizesRequests;

    public function index(Request $request) {
        $query = Chamado::with(['usuario', 'local', 'tipoProblema', 'feedback']);
        $user = Auth::user();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        if (!$user->canViewAllTickets()) {
            $query->where('id_usuario', $user->id_usuario);
        }

        $chamados = $query
            ->orderByRaw(
                "CASE WHEN prioridade='alta' THEN 1 WHEN prioridade='media' THEN 2 WHEN prioridade='baixa' THEN 3 ELSE 4 END",
            )
            ->orderBy('data_abertura', 'desc')
            ->paginate(10);

        $filtros = [
            'status' => $request->status ?? '',
            'prioridade' => $request->prioridade ?? '',
        ];

        $statusCounts = [
            'em_andamento' => Chamado::where('status', 'em_andamento')->count(),
            'concluido' => Chamado::where('status', 'concluido')->count(),
            'cancelado' => Chamado::where('status', 'cancelado')->count(),
        ];

        return view('chamados.index', compact('chamados', 'filtros', 'statusCounts'));
    }

    public function create() {
        /** @var User $user */
        $user = Auth::user();

        $locais = Local::all();
        $tipos = TipoProblema::all();
        $equipamentos = Equipamento::where('status', 'ativo')->get();

        return view('chamados.create', compact('locais', 'tipos', 'equipamentos'));
    }

    public function store(Request $request) {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'descricao' => 'required|string',
            'id_patrimonio' => 'nullable|string|max:100',
            'tipo_chamado' => 'nullable|in:interno',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problemas,id_tipo',
            'id_equipamento' => 'nullable|exists:equipamentos,id_equipamento',
            'secao_tecnica' => 'nullable|in:eletrica,hidraulica,civil,mecanica',
            'complexidade' => 'nullable|in:simples,media,complexa',
            'tipo_trabalho' => 'nullable|in:preventiva,corretiva,melhoria',
        ]);

        $data['tipo_chamado'] = 'interno';
        $data['id_usuario'] = $user->id_usuario;
        $data['status'] = 'aberto';
        $data['data_abertura'] = now();
        $data['data_ultimo_status'] = now();

        if (!$user->isAdmin() && !$user->isEquipeManutencao()) {
            unset($data['prioridade'], $data['secao_tecnica'], $data['complexidade'], $data['tipo_trabalho']);
        }

        $idPatrimonio = trim((string) ($data['id_patrimonio'] ?? ''));
        if ($idPatrimonio !== '') {
            $chamadoExistente = Chamado::where('id_patrimonio', $idPatrimonio)
                ->whereIn('status', ['aberto', 'em_andamento'])
                ->first();

            if ($chamadoExistente && !$request->boolean('confirmar_duplicado')) {
                return back()
                    ->withInput()
                    ->with('alerta_duplicado', $chamadoExistente->id_chamado)
                    ->withErrors([
                        'id_patrimonio' => 'Ja existe um chamado ativo para este patrimonio.',
                    ]);
            }
        }

        $data['id_patrimonio'] = $idPatrimonio !== '' ? $idPatrimonio : null;

        $chamado = Chamado::create($data);

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

        return redirect()->route('chamados.index')->with('success', 'Chamado criado com sucesso!');
    }

    public function show(string $id) {
        $chamado = Chamado::with([
            'usuario',
            'usuarioResponsavel',
            'local',
            'tipoProblema',
            'equipamento',
            'feedback',
            'historicoStatus.usuario',
        ])->findOrFail($id);

        /** @var User $user */
        $user = Auth::user();
        $tecnicos = $user->isAdmin() ? User::where('nivel_acesso', 'tecnico_manutencao')->get() : collect([]);

        return view('chamados.show', compact('chamado', 'tecnicos'));
    }

    public function edit(string $id) {
        /** @var User $user */
        $user = Auth::user();

        $chamado = Chamado::findOrFail($id);

        if (!$user->canEditTicket($chamado)) {
            return redirect()->route('chamados.show', $id)->withErrors([
                'edit' => 'Voce nao pode editar este chamado.',
            ]);
        }

        $locais = Local::all();
        $tipos = TipoProblema::all();
        $equipamentos = Equipamento::where('status', 'ativo')->get();

        return view('chamados.edit', compact('chamado', 'locais', 'tipos', 'equipamentos'));
    }

    public function updateStatus(Request $request, string $id) {
        $chamado = Chamado::findOrFail($id);
        /** @var User $user */
        $user = Auth::user();

        if ($user->isProfessor()) {
            return back()->withErrors([
                'status' => 'Seu nivel de acesso nao permite alterar o status do chamado.',
            ]);
        }

        if ($request->filled('nomeTecnicoResponsavel') && !$request->filled('nome_tecnico_responsavel')) {
            $request->merge([
                'nome_tecnico_responsavel' => $request->input('nomeTecnicoResponsavel'),
            ]);
        }

        $rules = [
            'status' => 'required|in:aberto,em_andamento,concluido,cancelado',
            'status_descricao' => 'nullable|string',
            'prioridade' => 'nullable|in:baixa,media,alta',
        ];

        // Se admin, pode escolher técnico via select; se técnico, é obrigatório mas preenchido automaticamente
        if ($user->isAdmin()) {
            $rules['id_usuario_responsavel'] = 'required_if:status,concluido|nullable|exists:usuarios,id_usuario';
        } else if ($user->isTecnico()) {
            $rules['id_usuario_responsavel'] = 'required_if:status,concluido';
        }

        // Manter compatibilidade com campo antigo
        $rules['nome_tecnico_responsavel'] = 'nullable|string|min:3|max:100';

        $request->validate($rules);

        $novoStatus = $request->status;
        $statusAtual = $chamado->status;
        $descricao = trim((string) $request->status_descricao);
        $responsavelAnterior = $chamado->id_usuario_responsavel;

        if (!$this->validarTransicaoStatus($user, $statusAtual, $novoStatus)) {
            return back()->withErrors([
                'status' => 'Voce nao tem permissao para realizar esta alteracao de status.',
            ]);
        }

        if ($novoStatus === 'cancelado' && mb_strlen($descricao) < 10) {
            return back()->withErrors([
                'status_descricao' => 'A justificativa de cancelamento precisa ter pelo menos 10 caracteres.',
            ]);
        }

        if ($novoStatus === 'em_andamento' && $request->filled('prioridade')) {
            $chamado->prioridade = $request->prioridade;
        }

        $chamado->status = $novoStatus;
        $chamado->status_descricao = $descricao !== '' ? $descricao : null;

        // Definir responsável baseado no tipo de usuário
        if ($novoStatus === 'concluido') {
            if ($user->isAdmin() && $request->filled('id_usuario_responsavel')) {
                // Admin selecionou um técnico via select
                $chamado->id_usuario_responsavel = $request->id_usuario_responsavel;
                $tecnicoSelecionado = User::find($request->id_usuario_responsavel);
                $chamado->nome_tecnico_responsavel = $tecnicoSelecionado->nome ?? null;
            } elseif ($user->isTecnico()) {
                // Técnico auto-preenche seu próprio ID
                $chamado->id_usuario_responsavel = $user->id_usuario;
                $chamado->nome_tecnico_responsavel = $user->nome;
            }
        } else {
            $chamado->id_usuario_responsavel = null;
            $chamado->nome_tecnico_responsavel = null;
        }
        $chamado->data_ultimo_status = now();
        $chamado->data_conclusao = $novoStatus === 'concluido' ? now() : null;

        if ($novoStatus === 'cancelado') {
            $chamado->data_conclusao = null;
            $chamado->nome_tecnico_responsavel = null;
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
            'prioridade' => $request->filled('prioridade') ? $request->prioridade : null,
        ]);

        $tipoNotificacao = $novoStatus === 'cancelado' ? 'cancelamento' : 'status';
        $destinatarios = NotificacaoHelper::obterDestinatarios($tipoNotificacao, $chamado, $user);
        $mensagem = 'Chamado #' . $chamado->id_chamado . ' teve o status alterado para ' . str_replace('_', ' ', $novoStatus) . '.';

        if ($novoStatus === 'cancelado' && $descricao !== '') {
            $mensagem = 'Chamado #' . $chamado->id_chamado . ' foi cancelado: ' . $descricao;
        }

        NotificacaoHelper::disparar($mensagem, $tipoNotificacao, $chamado->id_chamado, $destinatarios);

        if ($responsavelAnterior !== $chamado->id_usuario_responsavel) {
            NotificacaoHelper::disparar(
                'Responsavel definido para o chamado #' . $chamado->id_chamado . '.',
                'atribuicao',
                $chamado->id_chamado,
                NotificacaoHelper::obterDestinatarios('atribuicao', $chamado, $user),
            );
        }

        return redirect()->route('chamados.show', $id)->with('success', 'Status atualizado com sucesso!');
    }

    public function update(Request $request, string $id) {
        /** @var User $user */
        $user = $request->user();

        $chamado = Chamado::findOrFail($id);

        if (!$user->canEditTicket($chamado)) {
            return redirect()->route('chamados.show', $id)->withErrors([
                'edit' => 'Voce nao pode editar este chamado.',
            ]);
        }

        if ($user->isProfessor()) {
            $data = $request->validate([
                'descricao' => 'required|string',
            ]);

            $chamado->update($data);

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

            return redirect()->route('chamados.show', $id)->with('success', 'Chamado atualizado.');
        }

        $data = $request->validate([
            'descricao' => 'required|string',
            'id_patrimonio' => 'nullable|string|max:100',
            'tipo_chamado' => 'nullable|in:interno',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problemas,id_tipo',
            'id_equipamento' => 'nullable|exists:equipamentos,id_equipamento',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'secao_tecnica' => 'nullable|in:eletrica,hidraulica,civil,mecanica',
            'complexidade' => 'nullable|in:simples,media,complexa',
            'tipo_trabalho' => 'nullable|in:preventiva,corretiva,melhoria',
        ]);

        $data['tipo_chamado'] = 'interno';
        $data['id_patrimonio'] = isset($data['id_patrimonio']) ? trim((string) $data['id_patrimonio']) : null;
        if ($data['id_patrimonio'] === '') {
            $data['id_patrimonio'] = null;
        }

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

        $chamado->update($data);

        $mudancas = [];
        foreach ($original as $campo => $valorAnterior) {
            if ($chamado->$campo != $valorAnterior) {
                $mudancas[] = $campo;
            }
        }

        $descricaoMudanca = $mudancas
            ? 'Campos alterados: ' . implode(', ', $mudancas) . '.'
            : 'Chamado atualizado por ' . $user->nome . '.';

        HistoricoStatusChamado::create([
            'id_chamado' => $chamado->id_chamado,
            'status_anterior' => $chamado->status,
            'status_novo' => $chamado->status,
            'descricao_mudanca' => $descricaoMudanca,
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

        return redirect()->route('chamados.show', $id)->with('success', 'Chamado atualizado com sucesso!');
    }

    public function destroy(Request $request, string $id) {
        /** @var User $user */
        $user = Auth::user();
        $chamado = Chamado::findOrFail($id);

        // Apenas Admins podem cancelar chamados
        if (!$user->isAdmin()) {
            return back()->withErrors([
                'delete' => 'Apenas administradores podem cancelar chamados.',
            ]);
        }

        if ($chamado->status === 'cancelado') {
            return back()->withErrors([
                'delete' => 'Este chamado ja esta cancelado.',
            ]);
        }

        $request->validate([
            'justificativa_cancelamento' => 'required|string|min:10',
        ]);

        $this->cancelarChamado($chamado, $request->justificativa_cancelamento, $user);

        return redirect()->route('chamados.index')->with('success', 'Chamado cancelado com justificativa registrada.');
    }

    private function cancelarChamado(Chamado $chamado, string $justificativa, User $user): void {
        $statusAnterior = $chamado->status;

        $chamado->status = 'cancelado';
        $chamado->status_descricao = $justificativa;
        $chamado->data_ultimo_status = now();
        $chamado->data_conclusao = null;
        $chamado->nome_tecnico_responsavel = null;
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
            return true; // Admins podem fazer qualquer alteração de status
        }

        if (!$user->isEquipeManutencao()) {
            return false; // Apenas Admins e Técnicos podem alterar status
        }

        // Técnicos NÃO podem alterar status de chamados cancelados
        if ($statusAtual === 'cancelado') {
            return false;
        }

        // Técnicos NÃO podem cancelar chamados
        if ($novoStatus === 'cancelado') {
            return false;
        }

        // Técnicos podem alterar para 'em andamento' ou 'concluído' de qualquer status (exceto cancelado)
        if ($novoStatus === 'em_andamento' || $novoStatus === 'concluido') {
            return true;
        }

        return false;
    }
}
