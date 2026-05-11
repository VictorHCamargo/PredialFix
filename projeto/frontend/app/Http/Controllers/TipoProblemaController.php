<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoProblema;
class TipoProblemaController extends Controller
{
    public function index()
    {
        $tipos = TipoProblema::all();
        return view('tipos.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria' => 'required|string',
            'prazo_estimado' => 'required|integer',
        ]);

        TipoProblema::create($data);
        return redirect()->route('tipos.index');
    }
}
