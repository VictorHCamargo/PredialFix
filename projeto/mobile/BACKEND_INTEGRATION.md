# Integração Backend - Checklist para Laravel

Este arquivo lista o que precisa estar configurado no backend Laravel para o app móvel funcionar corretamente.

## ✅ Configuração Obrigatória

### 1. CORS (Cross-Origin Resource Sharing)

Edite `config/cors.php` para permitir requisições do móvel:

```php
'allowed_origins' => ['*'], // Ou específico: ['http://10.0.2.2:8000', 'http://localhost:8000']
'allowed_headers' => ['*'],
'allowed_methods' => ['*'],
'exposed_headers' => ['*'],
```

Ou adicione middleware no `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();
})
```

### 2. Endpoints de Autenticação

Implemente em `routes/api.php`:

```php
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
```

**AuthController - Exemplo:**

```php
public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($validated)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('mobile-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
}

public function register(Request $request)
{
    $validated = $request->validate([
        'nome' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
    ]);

    $user = User::create([
        'nome' => $validated['nome'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    $token = $user->createToken('mobile-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ], 201);
}

public function logout()
{
    Auth::user()->tokens()->delete();
    return response()->json(['message' => 'Logged out']);
}
```

### 3. Endpoints de Chamados

```php
Route::middleware('auth:sanctum')->group(function () {
    // Listar chamados do usuário
    Route::get('/chamados', [ChamadoController::class, 'index']);
    
    // Ver um chamado
    Route::get('/chamados/{id}', [ChamadoController::class, 'show']);
    
    // Criar chamado
    Route::post('/chamados', [ChamadoController::class, 'store']);
    
    // Atualizar chamado
    Route::put('/chamados/{id}', [ChamadoController::class, 'update']);
    
    // Deletar chamado
    Route::delete('/chamados/{id}', [ChamadoController::class, 'destroy']);
});
```

### 4. Endpoints de Referência

```php
Route::get('/locais', [LocalController::class, 'index']);
Route::get('/tipos-problema', [TipoProblemaController::class, 'index']);
```

### 5. Endpoints de Feedback/Avaliação

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/avaliar', [FeedbackController::class, 'index']);
    Route::post('/avaliar', [FeedbackController::class, 'store']);
});
```

## 🔐 Usando Sanctum (Recomendado)

1. Instale:
```bash
php artisan install:api
```

2. Configure o modelo User:
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    // ...
}
```

3. Configure `config/sanctum.php`:
```php
'expiration' => null, // Tokens não expiram
'middleware' => [
    'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
    'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
],
```

4. Use em rotas:
```php
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
```

## 📦 Estrutura de Resposta Esperada

### Login/Register
```json
{
  "token": "1|Hd3d7cVi...",
  "user": {
    "id": 1,
    "nome": "João Silva",
    "email": "joao@student.com",
    "cpf": null,
    "telefone": null,
    "role": "aluno"
  }
}
```

### Listar Chamados
```json
{
  "data": [
    {
      "id_chamado": 1,
      "descricao": "Tomada com defeito",
      "status": "pendente",
      "prioridade": "baixa",
      "data_abertura": "2026-05-18T10:30:00",
      "data_conclusao": null,
      "id_local": 1,
      "id_tipo": 1,
      "id_usuario": 1,
      "local": {
        "id_local": 1,
        "nome": "Bloco A, Sala 1"
      },
      "tipo_problema": {
        "id_tipo": 1,
        "nome": "Elétrica"
      }
    }
  ]
}
```

### Criar Feedback
```json
{
  "data": {
    "id_feedback": 1,
    "id_chamado": 1,
    "id_usuario": 1,
    "avaliacao": 5,
    "comentario": "Excelente serviço!",
    "created_at": "2026-05-18T10:30:00"
  }
}
```

## 🚀 Testes

Teste os endpoints com Postman/Insomnia:

1. **Login:**
   ```
   POST http://localhost:8000/api/login
   Body: {"email": "joao@student.com", "password": "aluno123"}
   ```

2. **Listar Chamados:**
   ```
   GET http://localhost:8000/api/chamados
   Header: Authorization: Bearer {token}
   ```

3. **Criar Chamado:**
   ```
   POST http://localhost:8000/api/chamados
   Header: Authorization: Bearer {token}
   Body: {
     "descricao": "Problema aqui",
     "id_local": 1,
     "id_tipo": 1
   }
   ```

## ⚠️ Problemas Comuns

### 401 Unauthorized
- Token expirado ou inválido
- Middleware não configurado
- User não autenticado

### 403 Forbidden
- CORS não configurado
- Acesso negado por policy

### 404 Not Found
- Rota não existe
- Middleware errado

### 422 Unprocessable Entity
- Validação falhou
- Dados obrigatórios faltando

## 💡 Dicas

- Sempre retorne JSON nas respostas
- Use status codes apropriados (200, 201, 400, 401, 404, 422)
- Implemente try-catch para erros
- Log todas as requisições para debugging
- Teste com dados reais do banco

---

Documentação completa em: `projeto/frontend/README.md`
