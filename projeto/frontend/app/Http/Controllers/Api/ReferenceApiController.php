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
            $locais = Local::all(['id_local', 'nome', 'descricao']);

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
            $tipos = TipoProblema::all(['id_tipo', 'nome', 'descricao']);

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
