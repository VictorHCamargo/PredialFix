# Sistema de Níveis de Acesso - Alunos

## Visão Geral

O sistema de PredialFix foi atualizado com um sistema de níveis de acesso (`nivel_acesso`) para diferentes tipos de usuários. Este documento descreve as permissões para o nível **Aluno**.

## Níveis de Acesso Disponíveis

- **administrador**: Acesso completo ao sistema
- **gerente_manutencao**: Gerenciador de chamados e equipe
- **tecnico_manutencao**: Técnico que realiza o trabalho
- **professor**: Pode ver e avaliar chamados
- **aluno**: Novo nível - pode criar e avaliar chamados
- **visitante**: Sem privilégios (padrão)

## Permissões de Aluno

### Dashboard
- ✅ Pode acessar o dashboard
- ✅ Vê estatísticas dos seus chamados
- ✅ Vê lista dos 5 chamados mais recentes

### Criar Chamados
- ✅ Pode criar novos chamados
- ✅ Pode definir:
  - Descrição do problema
  - Tipo de chamado (interno/externo)
  - Local do problema
  - Tipo de problema
  - Equipamento afetado (opcional)
- ❌ NÃO pode definir:
  - Prioridade (sempre padrão: **baixa**)
  - Seção técnica
  - Complexidade
  - Tipo de trabalho

### Editar Chamados
- ✅ Pode editar APENAS seus próprios chamados
- ✅ Pode editar APENAS se o chamado estiver com status **aberto**
- ✅ Pode editar:
  - Descrição do problema
  - Tipo de chamado (interno/externo)
  - Local do problema
  - Tipo de problema
  - Equipamento afetado (opcional)
- ❌ NÃO pode editar:
  - Prioridade
  - Status (alteração de status)
  - Seção técnica
  - Complexidade
  - Tipo de trabalho

### Visualizar Chamados
- ✅ Pode ver seus próprios chamados
- ❌ NÃO pode ver chamados de outros alunos

### Alterar Status
- ❌ **NÃO pode** alterar o status de chamados
- Apenas gerentes, técnicos e admin podem fazer isso

### Avaliar Chamados
- ✅ Pode avaliar seus próprios chamados **concluídos**
- ✅ Pode dar nota e comentário
- ✅ Apenas um feedback por chamado

### Deletar Chamados
- ✅ Pode deletar seus próprios chamados
- ✅ Apenas se o chamado estiver com status **aberto**

## Fluxo Típico de um Aluno

1. **Criar Chamado**: Aluno cria um chamado descrevendo o problema
   - Status: `aberto`
   - Prioridade: `baixa` (definida automaticamente)

2. **Editar Chamado**: Se necessário, aluno pode editar as informações
   - Apenas enquanto status for `aberto`

3. **Acompanhar Status**: Aluno vê o chamado sendo processado
   - Gerente ou técnico muda para `em_andamento`
   - Técnico trabalha no problema
   - Técnico muda para `concluido`

4. **Avaliar**: Após conclusão, aluno pode avaliar o serviço
   - Nota de 1 a 5
   - Comentário opcional

## Implementação Técnica

### Model: User
```php
// Métodos helper no modelo User
$user->isAluno();           // Verifica se é aluno
$user->canEditTicket($chamado);    // Verifica se pode editar
$user->canRateTickets();    // Verifica se pode avaliar
$user->canSeeDashboard();   // Verifica se pode ver dashboard
$user->canManageTickets();  // Verifica se pode gerenciar (admin/gerente/técnico)
```

### Controller: ChamadoController
- `store()`: Validação diferenciada para alunos
- `update()`: Alunos só podem editar descricao, tipo_chamado, local, tipo, equipamento
- `updateStatus()`: Alunos não podem alterar status
- `edit()`: Usa `User::canEditTicket()` para verificação

### Policy: ChamadoPolicy
Centralizou a lógica de autorização para:
- `view`: Ver um chamado específico
- `create`: Criar novo chamado
- `update`: Editar um chamado
- `delete`: Deletar um chamado
- `updateStatus`: Alterar status
- `rate`: Avaliar um chamado

## Exemplo de Uso no Controller

```php
// Usando a Policy
if ($request->user()->cannot('update', $chamado)) {
    return back()->withErrors(['edit' => 'Não autorizado']);
}

// Usando o método do modelo
if (!$user->canEditTicket($chamado)) {
    return back()->withErrors(['edit' => 'Não autorizado']);
}

// Usando o método helper
if ($user->isAluno()) {
    // Aplicar restrições para aluno
}
```

## Atualizar Nível de Acesso de Usuário

Para alterar o nível de acesso de um usuário no banco de dados:

```php
// Via tinker ou seed
$user = User::find(1);
$user->nivel_acesso = 'aluno';
$user->save();

// Níveis válidos: 'administrador', 'gerente_manutencao', 'tecnico_manutencao', 'professor', 'aluno', 'visitante'
```

## Próximas Etapas (Futuro)

1. ✅ Sistema de roles implementado
2. ⏳ Adicionar interface para admin gerenciar níveis de acesso
3. ⏳ Dashboard customizado por nível de acesso
4. ⏳ Notificações para alunos quando chamado é atualizado
5. ⏳ Relatório de uso por nível de acesso
