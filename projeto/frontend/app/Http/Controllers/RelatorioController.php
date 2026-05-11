<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relatorio;

class RelatorioController extends Controller
{
    public function index()
    {
        $relatorios = Relatorio::with('usuario')->get();
        return view('relatorios.index', compact('relatorios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string',
            'tipo_relatorio' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta',
            'status' => 'required|string',
        ]);

        $data['data_relatorio'] = now();
        $data['id_usuario'] = $request->user()->id_usuario;

        Relatorio::create($data);
        return redirect()->route('relatorios.index');
    }
}
