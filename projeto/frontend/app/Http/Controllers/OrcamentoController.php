<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orcamento;
use App\Models\Chamado;

class OrcamentoController extends Controller
{
    public function store(Request $request, string $id_chamado)
    {
        $data = $request->validate([
            'valor' => 'required|numeric',
            'descricao' => 'nullable|string',
            'data_verificacao' => 'required|date',
        ]);

        $orcamento = Orcamento::create($data);

        $chamado = Chamado::findOrFail($id_chamado);
        $chamado->update(['id_orcamento' => $orcamento->id_orcamento]);

        return redirect()->back();
    }

    public function aprovar(string $id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $orcamento->update(['aprovacao' => true]);
        return redirect()->back();
    }
}
