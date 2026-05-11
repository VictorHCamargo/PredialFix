<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Local;
class LocalController extends Controller
{
    public function index()
    {
        $locais = Local::all();
        return view('locais.index', compact('locais'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sala_setor' => 'required|string',
            'andar' => 'required|integer',
            'bloco' => 'required|string|max:50',
        ]);

        Local::create($data);
        return redirect()->route('locais.index');
    }

    public function update(Request $request,string $id)
    {
        $local = Local::findOrFail($id);
        $data = $request->validate([
            'sala_setor' => 'required|string',
            'andar' => 'required|integer',
            'bloco' => 'required|string|max:50',
        ]);

        $local->update($data);
        return redirect()->route('locais.index');
    }

    public function destroy(string $id)
    {
        Local::findOrFail($id)->delete();
        return redirect()->route('locais.index');
    }
}
