<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    /**
     * Login - Retorna token de autenticação
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Busca o usuário
        $user = User::where('email', $validated['email'])->first();

        // Verifica senha
        if (!$user || !Hash::check($validated['password'], $user->senha)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou senha incorretos',
            ], 401);
        }

        // Verifica se está ativo
        if (!$user->ativo) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário inativo',
            ], 403);
        }

        // Gera token Sanctum
        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id_usuario,
                    'nome' => $user->nome,
                    'email' => $user->email,
                    'nivel_acesso' => $user->nivel_acesso,
                    'setor' => $user->setor,
                ],
            ],
        ]);
    }

    /**
     * Register - Cria novo usuário
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
            'nivel_acesso' => 'nullable|in:professor',
            'setor' => 'nullable|string',
        ]);

        try {
            $user = User::create([
                'nome' => $validated['nome'],
                'email' => $validated['email'],
                'senha' => Hash::make($validated['password']),
                'nivel_acesso' => $validated['nivel_acesso'] ?? 'professor',
                'setor' => $validated['setor'] ?? null,
                'ativo' => true,
            ]);

            // Gera token automaticamente
            $token = $user->createToken('mobile-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Usuário registrado com sucesso',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id_usuario,
                        'nome' => $user->nome,
                        'email' => $user->email,
                        'nivel_acesso' => $user->nivel_acesso,
                    ],
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar usuário: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Logout - Invalida o token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso',
        ]);
    }

    /**
     * Profile - Dados do usuário autenticado
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id_usuario,
                'nome' => $user->nome,
                'email' => $user->email,
                'nivel_acesso' => $user->nivel_acesso,
                'setor' => $user->setor,
                'ativo' => $user->ativo,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }
}
