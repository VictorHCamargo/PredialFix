<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_chamado' => 'required|exists:chamados,id_chamado',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
        ]);

        $data['data_feedback'] = now();

        Feedback::create($data);
        return redirect()->back();
    }
}
