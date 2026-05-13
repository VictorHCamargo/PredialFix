# Implementação de Níveis de Acesso para Alunos - RESUMO

## 📋 O que foi implementado

Sistema completo de níveis de acesso com foco em **Alunos** que podem:
- ✅ Ver o dashboard
- ✅ Criar chamados (sem definir prioridade)
- ✅ Editar apenas seus próprios chamados abertos
- ✅ Avaliar chamados concluídos
- ❌ Não podem alterar status, prioridade ou campos técnicos

## 📁 Arquivos Criados/Modificados

### 1. **Models** 
- [app/Models/User.php](app/Models/User.php) - Adicionados métodos:
  - `isAluno()` - Verifica se é aluno
  - `isProfessor()` - Verifica se é professor
  - `canSeeDashboard()` - Pode ver dashboard
  - `canManageTickets()` - Pode gerenciar chamados
  - `canRateTickets()` - Pode avaliar
  - `canEditTicket(Chamado $chamado)` - Pode editar chamado específico

### 2. **Controllers**
- [app/Http/Controllers/ChamadoController.php](app/Http/Controllers/ChamadoController.php) - Modificado:
  - `store()` - Validação diferenciada para alunos (sem prioridade, secao_tecnica, complexidade, tipo_trabalho)
  - `update()` - Alunos só editam descricao, tipo_chamado, local, tipo, equipamento
  - `updateStatus()` - Alunos não podem alterar status
  - `validarTransicaoStatus()` - Alunos bloqueados de transições

### 3. **Policies (Authorization)**
- [app/Policies/ChamadoPolicy.php](app/Policies/ChamadoPolicy.php) - **NOVO**
  - Centraliza a lógica de autorização
  - Métodos: `viewAny`, `view`, `create`, `update`, `delete`, `updateStatus`, `rate`

### 4. **Providers**
- [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php) - Modificado:
  - Registra a ChamadoPolicy
  
- [app/Providers/AuthServiceProvider.php](app/Providers/AuthServiceProvider.php) - **NOVO**
  - Configuração de policies (backup/referência)

### 5. **Middleware**
- [app/Http/Middleware/CheckAccessLevel.php](app/Http/Middleware/CheckAccessLevel.php) - **NOVO**
  - Middleware para verificar nível de acesso em rotas
  - Uso: `Route::middleware('access.level:aluno,professor')->group(...)`

### 6. **Database**
- [bootstrap/app.php](bootstrap/app.php) - Modificado:
  - Registrado o middleware `CheckAccessLevel`

- [database/seeders/UsersSeeder.php](database/seeders/UsersSeeder.php) - **NOVO**
  - Seed com usuários de teste para cada nível:
    - admin@predialfix.com (senha: admin123)
    - gerente@predialfix.com (senha: gerente123)
    - tecnico@predialfix.com (senha: tecnico123)
    - professor@predialfix.com (senha: prof123)
    - joao@student.com (senha: aluno123)
    - maria@student.com (senha: aluno123)
    - pedro@student.com (senha: aluno123)
    - visitor@predialfix.com (senha: visitor123)

- [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php) - Modificado:
  - Agora chama UsersSeeder antes de ChamadosSeeder

### 7. **Documentação**
- [NIVEIS_ACESSO.md](NIVEIS_ACESSO.md) - **NOVO**
  - Documentação completa do sistema de níveis de acesso
  - Explicação de permissões por nível
  - Exemplos de uso

## 🔧 Como Usar

### Carregar os Seeders (Usuários de Teste)
```bash
php artisan db:seed --class=UsersSeeder
# ou para seed completo
php artisan db:seed
```

### Verificar Nível de Acesso no Código

```php
// No Controller
$user = Auth::user();
if ($user->isAluno()) {
    // Aplicar lógica específica para alunos
}

// Usando Policy
if ($request->user()->can('update', $chamado)) {
    // Permitir edição
}

// Usando Gate
if (Gate::allows('update', $chamado)) {
    // Permitir
}
```

### Proteger Rotas por Nível de Acesso

```php
// No routes/web.php

// Apenas para alunos
Route::middleware('access.level:aluno')->group(function () {
    Route::get('/alunos-only', ...);
});

// Para múltiplos níveis
Route::middleware('access.level:aluno,professor,administrador')->group(function () {
    Route::get('/formulario', ...);
});

// Usando Policy na rota (via RouteServiceProvider)
Route::resource('chamados', ChamadoController::class)->middleware('auth.custom');
```

### Alterar Nível de Acesso de Usuário

```bash
# Via Tinker
php artisan tinker
> $user = User::find(1);
> $user->nivel_acesso = 'aluno';
> $user->save();

# Valores válidos:
# - administrador
# - gerente_manutencao
# - tecnico_manutencao
# - professor
# - aluno
# - visitante
```

## 🔐 Fluxo de Permissões

### Aluno cria chamado:
```
1. user->isAluno() = true
2. store() valida apenas: descricao, tipo_chamado, id_local, id_tipo, id_equipamento
3. prioridade é automaticamente definida como 'baixa'
4. secao_tecnica, complexidade, tipo_trabalho são ignorados
5. Chamado criado com status 'aberto'
```

### Aluno edita chamado:
```
1. edit() verifica: user->canEditTicket($chamado)
2. Se aluno: pode editar APENAS se id_usuario === user.id e status === 'aberto'
3. update() valida apenas campos permitidos
4. Campos protegidos: prioridade, secao_tecnica, complexidade, tipo_trabalho
```

### Aluno tenta alterar status:
```
1. updateStatus() detecta user->isAluno() = true
2. Retorna erro: "Alunos não têm permissão para alterar o status"
3. Chamado NÃO é modificado
```

### Aluno avalia chamado:
```
1. FeedbackController checa user->canRateTickets()
2. Aluno só pode avaliar seus próprios chamados com status 'concluido'
3. Pode dar nota (1-5) e comentário
```

## 📊 Matriz de Permissões

| Ação | Admin | Gerente | Técnico | Professor | Aluno | Visitante |
|------|-------|---------|---------|-----------|-------|-----------|
| Ver Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Criar Chamado | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Editar Próprio | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| Editar Qualquer | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Alterar Status | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Avaliar Chamado | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| Deletar Chamado | ✅ | ❌ | ❌ | ❌ | ✅* | ❌ |
| Ver Todos Chamados | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |

*Aluno só pode deletar seus próprios chamados abertos

## ✨ Próximas Melhorias (Sugestões)

1. **Dashboard Customizado**
   - Mostrar diferentes informações baseado no nível de acesso

2. **Views Customizadas**
   - Ocultar/mostrar campos baseado no nível de acesso (prioridade, setor, etc)

3. **Notificações**
   - Email quando chamado é atualizado
   - SMS para casos urgentes

4. **Relatórios**
   - Relatório de uso por nível
   - Estatísticas de chamados por aluno
   - Avaliações agregadas

5. **Audit Log**
   - Registrar quem fez o quê e quando
   - Especialmente importante para mudanças de nível de acesso

6. **Interface Admin**
   - Tela para gerenciar níveis de acesso
   - Bulk operations para alterar múltiplos usuários

## 🐛 Testes Recomendados

```bash
# 1. Login como aluno
# 2. Tentar criar chamado
#    ✅ Não deve poder definir prioridade
# 3. Tentar editar chamado aberto
#    ✅ Deve permitir apenas campos básicos
# 4. Tentar editar chamado em andamento
#    ❌ Deve retornar erro
# 5. Tentar alterar status
#    ❌ Deve retornar erro
# 6. Tentar avaliar chamado concluído
#    ✅ Deve permitir
```

## 📞 Suporte

Para dúvidas sobre implementação, consulte:
- [NIVEIS_ACESSO.md](NIVEIS_ACESSO.md) - Documentação detalhada
- [app/Policies/ChamadoPolicy.php](app/Policies/ChamadoPolicy.php) - Lógica de autorização
- [app/Models/User.php](app/Models/User.php) - Métodos helper
