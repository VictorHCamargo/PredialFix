# PredialFix - App Móvel

App Flutter totalmente funcional para gerenciamento de chamados de manutenção.

## ✨ Funcionalidades Implementadas

✅ **Autenticação**
- Login com email/senha
- Registro de novos usuários
- Recuperação de sessão
- Logout

✅ **Dashboard**
- Estatísticas de chamados
- Ações rápidas
- Boas-vindas personalizado

✅ **Gerenciamento de Chamados**
- Criar novo chamado
- Listar chamados com filtros por status
- Ver detalhes do chamado

✅ **Avaliações**
- Avaliar chamados concluídos
- Sistema de estrelas (1-5)
- Comentários opcionais

✅ **Perfil**
- Visualizar dados do usuário
- Logout com confirmação

✅ **Suporte**
- Contato e informações
- Formulário para enviar mensagens

✅ **Navegação**
- Menu drawer funcional
- Roteamento entre telas
- Estado de carregamento

## 🔧 Configuração

### 1. Dependências
O arquivo `pubspec.yaml` foi atualizado com:
- `http` - Requisições HTTP
- `provider` - Gerenciamento de estado
- `shared_preferences` - Armazenamento local
- `intl` - Formatação internacional
- `dio` - Cliente HTTP avançado

### 2. Instalação
```bash
cd projeto/mobile
flutter pub get
```

### 3. Configuração da API

Edite `lib/services/api_service.dart` e altere a URL base:

```dart
static const String baseUrl = 'http://SEU_IP:8000/api';
```

**Opções:**
- **Emulador Android**: `http://10.0.2.2:8000/api`
- **Dispositivo Físico**: `http://<seu-ip>:8000/api`
- **Localhost**: `http://localhost:8000/api` (apenas web)

### 4. Executar

**Emulador Android:**
```bash
flutter run
```

**Dispositivo Físico:**
```bash
flutter run -d <device_id>
```

## 📋 Fluxo de Uso

### 1. Fazer Login
- Email: `joao@student.com`
- Senha: `aluno123`

(Você precisa ter criado esses usuários no backend Laravel)

### 2. Dashboard
- Vê estatísticas de chamados
- Acessa ações rápidas

### 3. Criar Chamado
- Seleciona Local
- Seleciona Tipo de Problema
- Descreve o problema
- Clica em "Criar Chamado"

### 4. Gerenciar Chamados
- Filtra por status
- Vê detalhes
- Acompanha progresso

### 5. Avaliar
- Vê chamados concluídos
- Avalia com estrelas
- Deixa comentário opcional

### 6. Perfil
- Vê dados pessoais
- Faz logout

## 🛠️ Arquitetura

```
lib/
├── main.dart              # Entrada da app com MultiProvider
├── models/                # Modelos de dados
│   ├── chamado.dart
│   ├── feedback.dart
│   ├── local.dart
│   ├── tipo_problema.dart
│   └── user.dart
├── screens/               # Telas da aplicação
│   ├── app_drawer.dart
│   ├── home_screen.dart
│   ├── login_screen.dart
│   ├── register_screen.dart
│   ├── request_screen.dart  # Criar chamado
│   ├── manage_screen.dart   # Listar chamados
│   ├── rating_screen.dart   # Avaliar
│   ├── profile_screen.dart
│   └── support_screen.dart
├── services/              # Serviços de negócio
│   ├── api_service.dart
│   ├── auth_service.dart
│   ├── chamado_service.dart
│   ├── feedback_service.dart
│   ├── reference_service.dart
│   ├── storage_service.dart
│   └── providers.dart
└── theme/
    └── app_theme.dart
```

## 🔌 Integração Backend

### Endpoints Esperados

O backend Laravel deve fornecer:

**Autenticação:**
- `POST /api/login` - Fazer login
- `POST /api/register` - Registrar novo usuário
- `POST /api/logout` - Fazer logout
- `GET /api/profile` - Dados do usuário

**Chamados:**
- `GET /api/chamados` - Listar todos os chamados do usuário
- `GET /api/chamados/{id}` - Ver detalhes de um chamado
- `POST /api/chamados` - Criar novo chamado
- `PUT /api/chamados/{id}` - Atualizar chamado
- `DELETE /api/chamados/{id}` - Deletar chamado

**Referências:**
- `GET /api/locais` - Listar locais
- `GET /api/tipos-problema` - Listar tipos de problema

**Feedback:**
- `GET /api/avaliar` - Listar feedbacks
- `POST /api/avaliar` - Criar feedback

## 💡 Estados de Carregamento

Todas as operações assincronas mostram:
- Loading spinner durante a execução
- Mensagem de erro se falhar
- Mensagem de sucesso se passar

## 📱 Responsividade

A app foi desenvolvida para ser responsiva em:
- Telas pequenas (telefones)
- Telas médias (tablets)
- Orientação vertical e horizontal

## 🔐 Segurança

- Token armazenado localmente em `SharedPreferences`
- Token enviado em headers `Authorization: Bearer {token}`
- Session restaurada automaticamente ao iniciar a app
- Logout limpa o storage

## 🐛 Troubleshooting

### "Erro de conexão"
- Verifique se o backend Laravel está rodando
- Confirme a URL da API em `api_service.dart`
- Se usar emulador, use `10.0.2.2` em vez de `localhost`

### "Email ou senha inválidos"
- Verifique as credenciais no banco de dados do backend
- Certifique-se que o usuário foi criado

### "Erro ao carregar dados"
- Verifique logs do backend
- Confirme que os endpoints estão implementados
- Teste os endpoints com Postman

## 📝 Notas

- O app usa `Provider` para gerenciamento de estado
- Dados são armazenados localmente em `SharedPreferences`
- As requisições são feitas com `Dio`
- Cada tela é independente e pode ser acessada via drawer

---

Para dúvidas ou problemas, consulte a documentação do backend em `projeto/frontend/`.
