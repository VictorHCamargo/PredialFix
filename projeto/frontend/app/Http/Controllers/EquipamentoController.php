<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento;
class EquipamentoController extends Controller
{
    public function index()
    {
        $equipamentos = Equipamento::all();
        return view('equipamentos.index', compact('equipamentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tag_identificacao' => 'required|unique:equipamentos,tag_identificacao',
            'nome_equipamento' => 'required|string',
            'marca' => 'required|string',
            'status' => 'required|in:ativo,manutencao,inativo',
        ]);

        Equipamento::create($data);
        return redirect()->route('equipamentos.index');
    }

    public function update(Request $request, string $id)
    {
        $equipamento = Equipamento::findOrFail($id);
        $data = $request->validate([
            'nome_equipamento' => 'required|string',
            'marca' => 'required|string',
            'status' => 'required|in:ativo,manutencao,inativo',
        ]);

        $equipamento->update($data);
        return redirect()->route('equipamentos.index');
    }

    public function destroy(string $id)
    {
        Equipamento::findOrFail($id)->delete();
        return redirect()->route('equipamentos.index');
    }
}
