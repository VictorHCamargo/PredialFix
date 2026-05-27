<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chamado;
use App\Models\Local;
use App\Models\TipoProblema;
use App\Models\Equipamento;
use App\Models\HistoricoStatusChamado;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChamadoController extends Controller {
    use AuthorizesRequests;
    /**
     * Mostrar lista de chamados com filtros e paginação
     */
    public function index(Request $request) {
        $query = Chamado::with(['usuario', 'local', 'tipoProblema']);
        $user = Auth::user();

        // Filtrar por status se fornecido
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrar por tipo de chamado se fornecido
        if ($request->filled('tipo_chamado')) {
            $query->where('tipo_chamado', $request->tipo_chamado);
        }

        // Filtrar por prioridade se fornecido
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        // Usuários sem código de entrada veem apenas seus próprios chamados
        $user = Auth::user();
        if (!$user->cod_entrada) {
            $query->where('id_usuario', $user->id_usuario);
        }

        // Ordenar por prioridade (maior primeiro) e depois por data
        $chamados = $query
            ->orderByRaw(
                "CASE WHEN prioridade='alta' THEN 1 WHEN prioridade='media' THEN 2 WHEN prioridade='baixa' THEN 3 ELSE 4 END",
            )
            ->orderBy('data_abertura', 'desc')
            ->paginate(10); // 10 chamados por página

        $filtros = [
            'status' => $request->status ?? '',
            'tipo_chamado' => $request->tipo_chamado ?? '',
            'prioridade' => $request->prioridade ?? '',
        ];

        // Contar chamados por status para os cards de estatísticas
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
        
        // Alunos não podem criar chamados
        if ($user->isAluno()) {
            return redirect()->route('chamados.index')->withErrors(
                ['create' => 'Alunos não podem criar chamados. Entre em contato com a equipe de manutenção.']
            );
        }
        
        $locais = Local::all();
        $tipos = TipoProblema::all();
        $equipamentos = Equipamento::where('status', 'ativo')->get();
        return view('chamados.create', compact('locais', 'tipos', 'equipamentos'));
    }

    public function store(Request $request) {
        /** @var User $user */
        $user = $request->user();

        // Alunos não podem criar chamados
        if ($user->isAluno()) {
            return redirect()->route('chamados.index')->withErrors(
                ['create' => 'Alunos não podem criar chamados.']
            );
        }

        // Validação completa para usuários autorizados
        $data = $request->validate([
            'descricao' => 'required|string',
            'tipo_chamado' => 'required|in:interno,externo',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problemas,id_tipo',
            'id_equipamento' => 'nullable|exists:equipamentos,id_equipamento',
            'secao_tecnica' => 'nullable|string',
            'complexidade' => 'nullable|string',
            'tipo_trabalho' => 'nullable|string',
        ]);

        $data['id_usuario'] = $user->id_usuario;
        $data['status'] = 'aberto';
        $data['data_abertura'] = now();
        $data['data_ultimo_status'] = now();

        Chamado::create($data);

        return redirect()->route('chamados.index')->with('success', 'Chamado criado com sucesso!');
    }

    /**
     * Mostrar detalhes de um chamado
     */
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

        return view('chamados.show', compact('chamado'));
    }

    /**
     * Exibir formulário de edição de um chamado
     */
    public function edit(string $id) {
        /** @var User $user */
        $user = Auth::user();
        
        // Alunos não podem editar chamados
        if ($user->isAluno()) {
            return redirect()->route('chamados.show', $id)->withErrors(
                ['edit' => 'Alunos não podem editar chamados.']
            );
        }
        
        $chamado = Chamado::findOrFail($id);

        // Apenas o criador do chamado pode editar (e apenas se estiver aberto)
        if ($chamado->id_usuario !== $user->id_usuario || $chamado->status !== 'aberto') {
            return redirect()
                ->route('chamados.show', $id)
                ->withErrors(['edit' => 'Você não pode editar este chamado.']);
        }

        $locais = Local::all();
        $tipos = TipoProblema::all();
        $equipamentos = Equipamento::where('status', 'ativo')->get();

        return view('chamados.edit', compact('chamado', 'locais', 'tipos', 'equipamentos'));
    }

    /**
     * Atualizar status do chamado com validações baseadas no nível de acesso
     */
    public function updateStatus(Request $request, string $id) {
        $chamado = Chamado::findOrFail($id);
        /** @var User $user */
        $user = Auth::user();

        // Alunos não podem alterar status
        if ($user->isAluno()) {
            return back()->withErrors([
                'status' => 'Alunos não têm permissão para alterar o status de chamados.',
            ]);
        }

        $request->validate([
            'status' => 'required|in:aberto,em_andamento,concluido,cancelado',
            'status_descricao' => 'nullable|string',
            'prioridade' => 'nullable|in:baixa,media,alta',
        ]);

        $novoStatus = $request->status;
        $statusAtual = $chamado->status;
        $descricao = $request->status_descricao;

        // Validar transições de status
        if (!$this->validarTransicaoStatus($user, $statusAtual, $novoStatus)) {
            return back()->withErrors([
                'status' => 'Você não tem permissão para realizar esta alteração de status.',
            ]);
        }

        // Validar descrição obrigatória para certas transições
        if ($novoStatus === 'concluido' && empty($descricao)) {
            return back()->withErrors([
                'status_descricao' => 'Descrição obrigatória ao concluir um chamado.',
            ]);
        }

        if ($novoStatus === 'cancelado' && empty($descricao)) {
            return back()->withErrors([
                'status_descricao' => 'Descrição obrigatória ao cancelar um chamado.',
            ]);
        }

        // Validar prioridade apenas na transição para em_andamento
        if ($novoStatus === 'em_andamento' && $request->filled('prioridade')) {
            $chamado->prioridade = $request->prioridade;
        }

        // Atualizar dados do chamado
        $chamado->status = $novoStatus;
        $chamado->status_descricao = $descricao;
        $chamado->id_usuario_responsavel = $user->id_usuario;
        $chamado->data_ultimo_status = now();

        if ($novoStatus === 'concluido') {
            $chamado->data_conclusao = now();
        }

        $chamado->save();

        // Registrar no histórico
        HistoricoStatusChamado::create([
            'id_chamado' => $chamado->id_chamado,
            'status_anterior' => $statusAtual,
            'status_novo' => $novoStatus,
            'descricao_mudanca' => $descricao,
            'id_usuario' => $user->id_usuario,
            'prioridade' => $request->prioridade ?? null,
        ]);

        return redirect()
            ->route('chamados.show', $id)
            ->with('success', 'Status atualizado com sucesso!');
    }

    /**
     * Validar transições de status baseadas no nível de acesso
     */
    private function validarTransicaoStatus($user, $statusAtual, $novoStatus) {
        // Alunos não podem alterar status
        if ($user->isAluno()) {
            return false;
        }

        // Admin pode fazer qualquer transição
        if ($user->isAdmin()) {
            return true;
        }

        // De aberto para em_andamento: apenas gerente ou admin
        if ($statusAtual === 'aberto' && $novoStatus === 'em_andamento') {
            return $user->isGerenteManutenacao() || $user->isAdmin();
        }

        // De em_andamento para concluído: qualquer um pode
        if ($statusAtual === 'em_andamento' && $novoStatus === 'concluido') {
            return true;
        }

        // Para cancelado: apenas equipe de manutenção ou admin
        if ($novoStatus === 'cancelado') {
            return $user->isEquipeManutenacao() || $user->isAdmin();
        }

        return false;
    }

    public function update(Request $request, string $id) {
        $user = $request->user();
        
        // Alunos não podem editar chamados
        if ($user->isAluno()) {
            return redirect()->route('chamados.show', $id)->withErrors(
                ['edit' => 'Alunos não podem editar chamados.']
            );
        }
        
        $chamado = Chamado::findOrFail($id);

        // Verificar permissão para editar
        if (!$user->canEditTicket($chamado)) {
            return redirect()
                ->route('chamados.show', $id)
                ->withErrors(['edit' => 'Você não pode editar este chamado.']);
        }

        // Validação completa
        $data = $request->validate([
            'descricao' => 'required|string',
            'tipo_chamado' => 'required|in:interno,externo',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problemas,id_tipo',
            'id_equipamento' => 'nullable|exists:equipamentos,id_equipamento',
        ]);

        $chamado->update($data);
        return redirect()
            ->route('chamados.show', $id)
            ->with('success', 'Chamado atualizado com sucesso!');
    }

    public function destroy(string $id) {
        /** @var User $user */
        $user = Auth::user();
        
        $chamado = Chamado::findOrFail($id);

        // Alunos não podem deletar
        if ($user->isAluno()) {
            return back()->withErrors([
                'delete' => 'Alunos não podem deletar chamados.',
            ]);
        }

        // Verificar permissão para deletar
        if (!$this->authorize('delete', $chamado)) {
            return back()->withErrors([
                'delete' => 'Você não tem permissão para deletar este chamado.',
            ]);
        }

        // Deletar histórico associado
        HistoricoStatusChamado::where('id_chamado', $id)->delete();

        // Deletar feedback associado se existir
        if ($chamado->feedback) {
            $chamado->feedback->delete();
        }

        $chamado->delete();
        return redirect()
            ->route('chamados.index')
            ->with('success', 'Chamado deletado com sucesso!');
    }
}
