# ✅ CHECKLIST DE IMPLEMENTAÇÃO

## Pré-requisitos
- [ ] Laravel 11+ instalado
- [ ] Banco de dados MySQL/PostgreSQL configurado
- [ ] Composer atualizado
- [ ] Node.js instalado (se usar Vite)

## Passo 1: Preparação
- [ ] Fazer backup do banco de dados atual
- [ ] Fazer backup dos arquivos do projeto
- [ ] Verificar se não há conflitos com migrations existentes

## Passo 2: Atualizar Rotas
- [ ] Copiar rotas de `routes/web-example.php` para `routes/web.php`
- [ ] Verificar se os namespaces estão corretos
- [ ] Testar se as rotas foram registradas: `php artisan route:list`

## Passo 3: Executar Migrations
```bash
php artisan migrate
```
- [ ] Migrations executadas com sucesso
- [ ] Tabelas criadas no banco:
  - [ ] `usuarios` (atualizada)
  - [ ] `chamados` (atualizada)
  - [ ] `estoque_interno` (nova)
  - [ ] `historico_status_chamados` (nova)

## Passo 4: Limpar Cache
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:clear
```
- [ ] Cache limpo
- [ ] Rotas em cache

## Passo 5: Criar Usuários de Teste

### Admin (Acesso Total)
```php
php artisan tinker
> use App\Models\User;
> User::create([
    'nome' => 'Admin',
    'email' => 'admin@test.com',
    'senha' => bcrypt('password123'),
    'nivel_acesso' => 'administrador',
    'setor' => 'TI',
    'ativo' => true,
    'cod_entrada' => 12345
]);
```
- [ ] Admin criado

### Gerente de Manutenção
```php
> User::create([
    'nome' => 'Gerente',
    'email' => 'gerente@test.com',
    'senha' => bcrypt('password123'),
    'nivel_acesso' => 'gerente_manutencao',
    'setor' => 'Manutenção',
    'ativo' => true,
    'cod_entrada' => 12346
]);
```
- [ ] Gerente criado

### Professor
```php
> User::create([
    'nome' => 'Professor',
    'email' => 'professor@test.com',
    'senha' => bcrypt('password123'),
    'nivel_acesso' => 'professor',
    'setor' => 'Educação',
    'ativo' => true
]);
```
- [ ] Professor criado (sem cod_entrada = vê só seus chamados)

## Passo 6: Testar Login
- [ ] Acessar `/login`
- [ ] Tentar login com admin@test.com / password123
- [ ] Verificar se redireciona para `/chamados`
- [ ] Verificar se as informações de usuário aparecem na navbar

## Passo 7: Testar Registro
- [ ] Ir para `/register`
- [ ] Preencher formulário como novo usuário
- [ ] Selecionar nível de perfil
- [ ] Submeter e verificar se é criado no banco

## Passo 8: Testar Chamados
- [ ] Login como professor (sem código de entrada)
- [ ] [ ] Criar novo chamado
  - [ ] Selecionar tipo (interno/externo)
  - [ ] Preencher descrição
  - [ ] Selecionar local e tipo de problema
  - [ ] **NÃO deve aparecer campo de prioridade**
- [ ] [ ] Listar chamados
  - [ ] Deve mostrar apenas seus chamados
  - [ ] Deve ter paginação (10 por página)
  - [ ] Deve ter filtros
  - [ ] Deve estar ordenado por prioridade
- [ ] [ ] Ver detalhes do chamado
  - [ ] Clicar em "Ver" deve ir para página de detalhes
  - [ ] Deve mostrar histórico vazio
- [ ] [ ] Tentar alterar status
  - [ ] Clique em "Alterar Status"
  - [ ] Modal deve abrir
  - [ ] Selecione "Em Andamento"
  - [ ] **Campo de prioridade deve aparecer**
  - [ ] Selecione prioridade
  - [ ] Submeta
  - [ ] **Deve aparecer erro se falta descrição**
  - [ ] Adicione descrição e submeta
  - [ ] Status deve ter mudado
  - [ ] Histórico deve mostrar a mudança

## Passo 9: Testar Controle de Acesso
- [ ] Login como professor
  - [ ] [ ] Não deve ver estoque
  - [ ] [ ] Deve ver apenas seus chamados
  - [ ] [ ] Deve poder criar chamados
- [ ] [ ] Login como gerente (com cod_entrada)
  - [ ] [ ] Deve ver todos os chamados
  - [ ] [ ] Deve poder iniciar execução (Aberto→Em Andamento)
  - [ ] [ ] Deve poder acessar estoque
  - [ ] [ ] Deve poder ver estoque completo

## Passo 10: Testar Modals
- [ ] Modal de deletar chamado
  - [ ] [ ] Clique em "Deletar"
  - [ ] [ ] Modal deve aparecer
  - [ ] [ ] Confirmação deve deletar
  - [ ] [ ] Cancelamento deve fechar
- [ ] [ ] Modal de alterar status
  - [ ] [ ] Deve mostrar status atual
  - [ ] [ ] Deve mostrar campos dinâmicos
  - [ ] [ ] Deve validar
- [ ] [ ] Modal de logout
  - [ ] [ ] Ir para perfil
  - [ ] [ ] Clique "Sair da Conta"
  - [ ] [ ] Confirme logout
- [ ] [ ] Modal de deletar conta
  - [ ] [ ] Ir para perfil
  - [ ] [ ] Clique "Deletar Conta"
  - [ ] [ ] Insira senha
  - [ ] [ ] Confirme delete
  - [ ] [ ] Deve ser redirecionado e deslogado

## Passo 11: Testar Perfil
- [ ] Acessar `/profile`
- [ ] [ ] Deve mostrar dados pessoais
- [ ] [ ] Deve mostrar nível de acesso
- [ ] [ ] Deve mostrar últimos 5 chamados
- [ ] [ ] Deve mostrar últimas 5 avaliações
- [ ] [ ] Botão "Editar Perfil" deve funcionar
- [ ] [ ] Botão "Alterar Senha" deve abrir modal
  - [ ] [ ] Insira senha atual incorreta → erro
  - [ ] [ ] Insira senha atual correta
  - [ ] [ ] Insira nova senha e confirmação
  - [ ] [ ] Submeta
  - [ ] [ ] Tente fazer login com nova senha

## Passo 12: Testa Estoque
- [ ] Login como Admin
- [ ] [ ] Acessar `/estoque`
- [ ] [ ] Deve listar itens (vazio se novo)
- [ ] [ ] Criar novo item
  - [ ] [ ] Preencher formulário
  - [ ] [ ] Submeta
  - [ ] [ ] Deve aparecer na lista
- [ ] [ ] Ver detalhes do item
- [ ] [ ] Editar item
- [ ] [ ] Deletar item (com confirmação)

## Passo 13: Verificar Banco de Dados
```bash
php artisan tinker
> DB::table('usuarios')->count()  // Deve ter seus usuários
> DB::table('chamados')->count()  // Deve ter seus chamados
> DB::table('historico_status_chamados')->count()  // Deve ter mudanças
> DB::table('estoque_interno')->count()  // Pode estar vazio
```
- [ ] Todos os dados estão no banco

## Passo 14: Verificar Logs
```bash
tail -f storage/logs/laravel.log
```
- [ ] Nenhum erro nos logs
- [ ] Nenhum warning

## Passo 15: Teste Final Integrado
- [ ] [ ] Admin cria tipo de problema
- [ ] [ ] Admin cria local
- [ ] [ ] Admin cria equipamento
- [ ] [ ] Professor cria chamado com tipo, local, equipamento
- [ ] [ ] Professor não consegue iniciar execução (erro)
- [ ] [ ] Gerente consegue iniciar execução e define prioridade
- [ ] [ ] Professor consegue ver o chamado com prioridade
- [ ] [ ] Gerente consegue concluir chamado (pede descrição)
- [ ] [ ] Professor consegue avaliar chamado
- [ ] [ ] Histórico registra todas as mudanças

## Passo 16: Performance
- [ ] [ ] Listar 100+ chamados é rápido (paginação)
- [ ] [ ] Filtros funcionam rapidamente
- [ ] [ ] Histórico carrega rápido
- [ ] [ ] Banco de dados está otimizado (índices)

## Passo 17: Segurança
- [ ] [ ] Não é possível acessar URL diretamente sem login
- [ ] [ ] Não é possível ver chamados de outros usuários (se visitante)
- [ ] [ ] Não é possível deletar chamado sem permissão
- [ ] [ ] Senhas não aparecem em logs
- [ ] [ ] CSRF token está em todos os forms

## Documentação Gerada
- [ ] [ ] `RESUMO.md` - Leia primeiro
- [ ] [ ] `IMPLEMENTACOES.md` - Detalhes técnicos
- [ ] [ ] `GUIA_IMPLEMENTACAO.md` - Passo a passo
- [ ] [ ] `routes/web-example.php` - Referência de rotas

## Problemas Encontrados?

Se encontrar erros, verifique:
- [ ] [ ] Migrations executaram com sucesso
- [ ] [ ] Rotas foram adicionadas corretamente
- [ ] [ ] Cache foi limpo
- [ ] [ ] Permissões de arquivo (755 para diretórios)
- [ ] [ ] Arquivo `.env` está correto
- [ ] [ ] Banco de dados está acessível

---

## ✅ CONCLUSÃO

Se todos os itens acima estão marcados, a implementação foi bem-sucedida!

**Parabéns! Seu PredialFix está atualizado com todas as novas funcionalidades! 🎉**

---

## 📞 SUPORTE

Se precisar de ajuda:
1. Verifique o arquivo `GUIA_IMPLEMENTACAO.md`
2. Verifique a seção de troubleshooting
3. Verifique os logs em `storage/logs/laravel.log`
4. Teste com `php artisan tinker`

---

**Última atualização: 12 de Maio de 2026**
