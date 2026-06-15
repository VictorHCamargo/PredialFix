<?php

namespace App\Http\Controllers\Api;

use App\Models\Local;
use App\Models\TipoProblema;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ReferenceApiController extends Controller
{
    /**
     * Listar locais
     */
    public function getLocais(): JsonResponse
    {
        try {
            $locais = Local::query()
                ->selectRaw('id_local, id_local as id, sala_setor, sala_setor as nome, sala_setor as descricao, bloco, andar')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $locais,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar locais: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Listar tipos de problema
     */
    public function getTiposProblema(): JsonResponse
    {
        try {
            $tipos = TipoProblema::query()
                ->selectRaw('id_tipo, id_tipo as id, categoria, categoria as nome')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tipos,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar tipos de problema: ' . $e->getMessage(),
            ], 500);
        }
    }
}
