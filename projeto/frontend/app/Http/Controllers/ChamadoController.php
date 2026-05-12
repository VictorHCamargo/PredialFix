<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chamado;
use App\Models\Local;
use App\Models\TipoProblema;
use App\Models\Equipamento;
class ChamadoController extends Controller
{
    public function index()
    {
        $chamados = Chamado::with(['usuario', 'local', 'tipoProblema'])->get();
        return view('chamados.index', compact('chamados'));
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
            'prioridade' => 'required|in:baixa,media,alta',
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

        Chamado::create($data);

        return redirect()->route('chamados.index');
    }

    public function show(string $id)
    {
        $chamado = Chamado::with(['usuario', 'local', 'tipoProblema', 'equipamento', 'feedback'])->findOrFail($id);
        return view('chamados.show', compact('chamado'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $chamado = Chamado::findOrFail($id);
        
        $data = $request->validate([
            'status' => 'required|in:aberto,em_andamento,concluido,cancelado',
        ]);

        if ($data['status'] === 'concluido' && !$chamado->data_conclusao) {
            $chamado->data_conclusao = now();
        }

        $chamado->update($data);
        
        return redirect()->route('chamados.index')->with('success', 'Status atualizado com sucesso!');
    }

    public function update(Request $request, string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|in:aberto,em_andamento,concluido,cancelado',
            'data_conclusao' => 'nullable|date',
        ]);

        $chamado->update($data);
        return redirect()->route('chamados.index');
    }

    public function destroy(string $id)
    {
        $chamado = Chamado::findOrFail($id);
        
        // Deletar feedback associado se existir
        if ($chamado->feedback) {
            $chamado->feedback->delete();
        }
        
        $chamado->delete();
        return redirect()->route('chamados.index')->with('success', 'Chamado deletado com sucesso!');
    }
}
