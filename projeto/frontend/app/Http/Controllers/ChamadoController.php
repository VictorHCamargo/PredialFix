<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chamado;
use App\Models\Local;
use App\Models\TipoProblema;
use App\Models\Equipamento;
use App\Models\HistoricoStatusChamado;
use Illuminate\Support\Facades\Auth;

class ChamadoController extends Controller
{
    /**
     * Mostrar lista de chamados com filtros e paginação
     */
    public function index(Request $request)
    {
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

        // Usuários normais (sem código de entrada) veem apenas seus próprios chamados
        if (!$user->temCodigoEntrada()) {
            $query->where('id_usuario', $user->id_usuario);
        }

        // Ordenar por prioridade (maior primeiro) e depois por data
        $chamados = $query
            ->orderByRaw("CASE WHEN prioridade='alta' THEN 1 WHEN prioridade='media' THEN 2 WHEN prioridade='baixa' THEN 3 ELSE 4 END")
            ->orderBy('data_abertura', 'desc')
            ->paginate(10); // 10 chamados por página

        $filtros = [
            'status' => $request->status ?? '',
            'tipo_chamado' => $request->tipo_chamado ?? '',
            'prioridade' => $request->prioridade ?? ''
        ];

        return view('chamados.index', compact('chamados', 'filtros'));
    }

    public function create()
    {
        $locais = Local::all();
        $tipos = TipoProblema::all();
        $equipamentos = Equipamento::where('status', 'ativo')->get();
        return view('chamados.create', compact('locais', 'tipos', 'equipamentos'));
    }

    public function store(Request $request)
    {
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

        $data['id_usuario'] = $request->user()->id_usuario;
        $data['status'] = 'aberto';
        $data['data_abertura'] = now();
        $data['data_ultimo_status'] = now();

        Chamado::create($data);

        return redirect()->route('chamados.index')->with('success', 'Chamado criado com sucesso!');
    }

    /**
     * Mostrar detalhes de um chamado
     */
    public function show(string $id)
    {
        $chamado = Chamado::with([
            'usuario',
            'usuarioResponsavel',
            'local',
            'tipoProblema',
            'equipamento',
            'feedback',
            'historicoStatus.usuario'
        ])->findOrFail($id);

        return view('chamados.show', compact('chamado'));
    }

    /**
     * Exibir formulário de edição de um chamado
     */
    public function edit(string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $user = Auth::user();

        // Apenas o criador do chamado pode editar (e apenas se estiver aberto)
        if ($chamado->id_usuario !== $user->id_usuario || $chamado->status !== 'aberto') {
            return redirect()->route('chamados.show', $id)->withErrors(['edit' => 'Você não pode editar este chamado.']);
        }

        $locais = Local::all();
        $tipos = TipoProblema::all();
        $equipamentos = Equipamento::where('status', 'ativo')->get();

        return view('chamados.edit', compact('chamado', 'locais', 'tipos', 'equipamentos'));
    }

    /**
     * Atualizar status do chamado com validações baseadas no nível de acesso
     */
    public function updateStatus(Request $request, string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'status' => 'required|in:aberto,em_andamento,concluido,cancelado',
            'status_descricao' => 'nullable|string',
            'prioridade' => 'nullable|in:baixa,media,alta'
        ]);

        $novoStatus = $request->status;
        $statusAtual = $chamado->status;
        $descricao = $request->status_descricao;

        // Validar transições de status
        if (!$this->validarTransicaoStatus($user, $statusAtual, $novoStatus)) {
            return back()->withErrors(['status' => 'Você não tem permissão para realizar esta alteração de status.']);
        }

        // Validar descrição obrigatória para certas transições
        if ($novoStatus === 'concluido' && empty($descricao)) {
            return back()->withErrors(['status_descricao' => 'Descrição obrigatória ao concluir um chamado.']);
        }

        if ($novoStatus === 'cancelado' && empty($descricao)) {
            return back()->withErrors(['status_descricao' => 'Descrição obrigatória ao cancelar um chamado.']);
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
            'prioridade' => $request->prioridade ?? null
        ]);

        return redirect()->route('chamados.show', $id)->with('success', 'Status atualizado com sucesso!');
    }

    /**
     * Validar transições de status baseadas no nível de acesso
     */
    private function validarTransicaoStatus($user, $statusAtual, $novoStatus)
    {
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

    public function update(Request $request, string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $data = $request->validate([
            'descricao' => 'required|string',
            'tipo_chamado' => 'required|in:interno,externo',
            'id_local' => 'required|exists:locais,id_local',
            'id_tipo' => 'required|exists:tipo_problemas,id_tipo',
            'id_equipamento' => 'nullable|exists:equipamentos,id_equipamento',
        ]);

        $chamado->update($data);
        return redirect()->route('chamados.show', $id)->with('success', 'Chamado atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $user = Auth::user();

        // Apenas admin ou o criador do chamado podem deletar
        if (!$user->isAdmin() && $chamado->id_usuario !== $user->id_usuario) {
            return back()->withErrors(['delete' => 'Você não tem permissão para deletar este chamado.']);
        }

        // Deletar histórico associado
        HistoricoStatusChamado::where('id_chamado', $id)->delete();

        // Deletar feedback associado se existir
        if ($chamado->feedback) {
            $chamado->feedback->delete();
        }

        $chamado->delete();
        return redirect()->route('chamados.index')->with('success', 'Chamado deletado com sucesso!');
    }
}
