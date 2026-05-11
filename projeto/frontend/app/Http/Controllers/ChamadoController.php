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
        Chamado::findOrFail($id)->delete();
        return redirect()->route('chamados.index');
    }
}
