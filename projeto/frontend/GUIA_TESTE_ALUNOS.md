# 🧪 Guia de Teste - Sistema de Níveis de Acesso para Alunos

## 📋 Preparação

### 1. Execute as Seeders
```bash
# Navegue até a pasta frontend
cd projeto/frontend

# Execute as seeders para popular o banco com usuários de teste
php artisan db:seed --class=UsersSeeder

# Ou para seed completo (recomendado)
php artisan db:seed
```

### 2. Usuários de Teste Disponíveis

| Email | Senha | Nível | Setor |
|-------|-------|-------|-------|
| admin@predialfix.com | admin123 | Administrador | TI |
| gerente@predialfix.com | gerente123 | Gerente Manutenção | Manutenção |
| tecnico@predialfix.com | tecnico123 | Técnico Manutenção | Manutenção |
| professor@predialfix.com | prof123 | Professor | Docência |
| joao@student.com | aluno123 | **Aluno** | Classe 1 |
| maria@student.com | aluno123 | **Aluno** | Classe 1 |
| pedro@student.com | aluno123 | **Aluno** | Classe 2 |
| visitor@predialfix.com | visitor123 | Visitante | - |

## 🧬 Testes de Funcionalidade

### Teste 1: Aluno Criando Chamado
**Objetivo**: Verificar se aluno pode criar chamado SEM campos técnicos

#### Passos:
1. Login como `joao@student.com` / `aluno123`
2. Clique em "Novo Chamado" ou vá para `/chamados/create`
3. **Observar**:
   - ✅ Campos visíveis: Descrição, Tipo de Chamado, Tipo Incidente, Local, Equipamento
   - ❌ Campos **NÃO** visíveis: Seção Técnica, Prioridade, Complexidade, Tipo de Trabalho
   - 📝 Mensagem informativa sobre restrições de aluno

#### Resultado Esperado:
```
✅ PASS: Aluno vê apenas campos básicos
✅ PASS: Mensagem informativa exibida
✅ PASS: Chamado criado com prioridade "baixa" automaticamente
```

---

### Teste 2: Aluno Editando Chamado Próprio
**Objetivo**: Verificar se aluno pode editar apenas seus chamados abertos

#### Passos:
1. Login como `joao@student.com` / `aluno123`
2. Acesse um chamado criado por ele (status: **aberto**)
3. Clique em "Editar"
4. **Tentar editar campos**:
   - ✅ Descrição
   - ✅ Tipo de Chamado
   - ✅ Local
   - ✅ Tipo Incidente
   - ✅ Equipamento

#### Resultado Esperado:
```
✅ PASS: Aluno consegue editar chamado aberto
✅ PASS: Campos técnicos não são salvos se enviados
✅ PASS: Chamado atualizado com sucesso
```

---

### Teste 3: Aluno NÃO Pode Editar Chamado de Outro
**Objetivo**: Garantir isolamento de dados entre alunos

#### Passos:
1. Login como `joao@student.com` / `aluno123`
2. Tente acessar URL de edição de chamado de `maria@student.com`
   - Exemplo: `/chamados/2/edit`
3. **Esperado**: Redirecionamento com erro

#### Resultado Esperado:
```
❌ PASS: Erro "Você não pode editar este chamado"
❌ PASS: NÃO redireciona para edição
```

---

### Teste 4: Aluno NÃO Pode Alterar Status
**Objetivo**: Verificar proteção de transições de status

#### Passos:
1. Login como `joao@student.com` / `aluno123`
2. Abra um chamado (seu)
3. **Tentar alterar status**:
   - Via URL: `/chamados/{id}/updateStatus`
   - Via API (se houver botão)
4. **Enviar request com novo status**

#### Resultado Esperado:
```
❌ PASS: Erro "Alunos não têm permissão para alterar status"
❌ PASS: Status não é alterado
```

---

### Teste 5: Aluno Pode Avaliar Chamado Concluído
**Objetivo**: Verificar feedback de aluno

#### Passos:
1. Login como `joao@student.com` / `aluno123`
2. Navegue para um chamado dele com status **concluído**
3. Clique em "Avaliar"
4. Preencha nota e comentário
5. Envie avaliação

#### Resultado Esperado:
```
✅ PASS: Aluno consegue avaliar chamado concluído
✅ PASS: Feedback é salvo
✅ PASS: Avaliação aparece no chamado
```

---

### Teste 6: Gerente Pode Ver Todos os Campos
**Objetivo**: Garantir que gerentes/técnicos têm acesso completo

#### Passos:
1. Login como `gerente@predialfix.com` / `gerente123`
2. Vá para criar novo chamado
3. **Observar**:
   - ✅ Todos os campos visíveis
   - ✅ Seção Técnica, Prioridade, Complexidade, Tipo de Trabalho

#### Resultado Esperado:
```
✅ PASS: Gerente vê todos os campos
✅ PASS: Pode definir todos os campos
```

---

### Teste 7: Técnico Pode Alterar Status
**Objetivo**: Verificar permissão de técnico

#### Passos:
1. Login como `tecnico@predialfix.com` / `tecnico123`
2. Abra um chamado com status **aberto**
3. Altere status para **em_andamento**
4. Salve

#### Resultado Esperado:
```
✅ PASS: Status alterado com sucesso
✅ PASS: Histórico registrado
```

---

### Teste 8: Visitante NÃO Pode Fazer Nada
**Objetivo**: Garantir isolamento de visitante

#### Passos:
1. Login como `visitor@predialfix.com` / `visitor123`
2. Tente acessar `/dashboard`
3. Tente acessar `/chamados/create`

#### Resultado Esperado:
```
❌ PASS: Acesso negado ao dashboard
❌ PASS: Não consegue criar chamado (se houver proteção)
```

---

## 🔍 Testes de Banco de Dados

### Verificar Níveis de Acesso no BD

```bash
# Login no Tinker
php artisan tinker

# Verificar usuário aluno
> $aluno = User::where('email', 'joao@student.com')->first();
> $aluno->nivel_acesso;
// Deve retornar: "aluno"

# Verificar métodos helper
> $aluno->isAluno();
// true
> $aluno->canManageTickets();
// false
> $aluno->canRateTickets();
// true

# Verificar chamado
> $chamado = Chamado::first();
> $aluno->canEditTicket($chamado);
// Depende do id_usuario e status
```

---

## 📊 Teste de Permissões Matiz

Execute este script para verificar todas as permissões:

```bash
php artisan tinker
```

```php
// Teste de todas as combinações
$users = User::all();
$chamado = Chamado::first();

foreach ($users as $user) {
    echo "\n=== {$user->nome} ({$user->nivel_acesso}) ===\n";
    echo "canSeeDashboard: " . ($user->canSeeDashboard() ? '✅' : '❌') . "\n";
    echo "canManageTickets: " . ($user->canManageTickets() ? '✅' : '❌') . "\n";
    echo "canRateTickets: " . ($user->canRateTickets() ? '✅' : '❌') . "\n";
    echo "canEditTicket: " . ($user->canEditTicket($chamado) ? '✅' : '❌') . "\n";
}
```

---

## 🐛 Verificação de Logs

Procure por erros em:

```bash
# Terminal rodando o Laravel
# Você verá logs de autorização negada

# Arquivo de log
tail -f storage/logs/laravel.log

# Procurar por:
# - "Alunos não têm permissão"
# - "Você não pode editar este chamado"
# - Policy rejections
```

---

## ✅ Checklist de Validação

Marque cada item conforme for testado:

- [ ] Aluno vê apenas campos básicos em criar
- [ ] Aluno não vê Seção Técnica
- [ ] Aluno não vê Prioridade
- [ ] Aluno não vê Complexidade
- [ ] Aluno não vê Tipo de Trabalho
- [ ] Aluno consegue criar chamado
- [ ] Aluno consegue editar seu próprio chamado aberto
- [ ] Aluno não consegue editar chamado de outro
- [ ] Aluno não consegue editar chamado não-aberto
- [ ] Aluno não consegue alterar status
- [ ] Aluno consegue avaliar chamado concluído
- [ ] Aluno não consegue avaliar chamado de outro
- [ ] Gerente vê todos os campos
- [ ] Gerente consegue alterar status
- [ ] Técnico consegue alterar status
- [ ] Visitante não consegue fazer nada
- [ ] Mensagem informativa para aluno é exibida
- [ ] Seeders criaram todos os usuários
- [ ] Senhas estão corretas

---

## 🚀 Próximos Testes

Uma vez passado por todos estes testes, considere:

1. **Teste de Carga**: Múltiplos alunos criando chamados simultaneamente
2. **Teste de Segurança**: Tentar escapar permissões via request direto
3. **Teste de UI**: Ocultar/mostrar botões baseado em permissão
4. **Teste de Email**: Notificações quando chamado é atualizado
5. **Teste de Relatórios**: Diferentes visualizações por nível

---

## 📞 Troubleshooting

### Problema: Seeders não funcionam
```bash
# Reset e reseed
php artisan migrate:refresh --seed
```

### Problema: Usuário não consegue fazer login
```bash
# Verificar se usuário existe
php artisan tinker
> User::where('email', 'joao@student.com')->first();

# Se não existir, rodar seeder novamente
> exit()
php artisan db:seed --class=UsersSeeder
```

### Problema: Campos técnicos aparecem para aluno
```blade
# Verificar view chamados.create
# Procurar por @unless(Auth::user()->isAluno())
```

---

## 📝 Notas

- Todos os testes devem ser executados em um ambiente de **teste** ou **development**
- Não execute em produção sem aprovação
- Limpe os dados de teste após conclusão: `php artisan migrate:refresh`
- Documente qualquer comportamento inesperado
