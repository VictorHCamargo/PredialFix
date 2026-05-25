# 🚀 Guia de Instalação e Execução - PredialFix

## Pré-requisitos

### Para o Backend (Laravel)
- PHP 8.1+
- Composer
- MySQL/SQLite
- Laravel 10+

### Para o Mobile (Flutter)
- Flutter SDK 3.0+
- Dart 3.0+
- Android SDK (para Android)
- Xcode (para iOS - Mac apenas)

---

## ⚙️ Setup do Backend

### 1. Instalar dependências PHP
```bash
cd projeto/frontend
composer install
```

### 2. Configurar arquivo .env
```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env`:
```
DB_CONNECTION=sqlite
# ou mysql se preferir
DB_DATABASE=predialfix.sqlite
```

### 3. Criar banco de dados
```bash
# Para SQLite
touch database/predialfix.sqlite

# Para MySQL
mysql -u root -p
CREATE DATABASE predialfix;
EXIT;
```

### 4. Executar migrações
```bash
php artisan migrate
```

### 5. Executar seeds (dados iniciais)
```bash
php artisan seed:run
```

### 6. Iniciar servidor Laravel
```bash
php artisan serve
# Servidor rodará em http://localhost:8000
```

---

## 📱 Setup do Mobile

### 1. Instalar dependências Flutter
```bash
cd projeto/mobile
flutter pub get
```

### 2. Configurar API URL
Editar `lib/services/api_service.dart`:

**Para emulador Android:**
```dart
static const String baseUrl = 'http://10.0.2.2:8000/api';
```

**Para dispositivo físico (mesmo IP da máquina):**
```dart
static const String baseUrl = 'http://192.168.1.100:8000/api';
```

### 3. Executar no Android
```bash
flutter run
```

### 4. Executar no iOS (Mac)
```bash
flutter run -d iphone
```

### 5. Executar na Web
```bash
flutter run -d chrome
```

---

## 🧪 Testando a Aplicação

### 1. Abrir o app
Após `flutter run`, o app abrirá no emulador/dispositivo

### 2. Fazer Registro
- Clique em "Criar Conta"
- Preencha: Nome, Email, Senha (min 6 chars)
- Clique em "Registrar"

### 3. Fazer Login
- Use os dados registrados
- Será redirecionado para Dashboard

### 4. Criar um Chamado
- Clique em "Novo Chamado"
- Selecione Local e Tipo de Problema
- Descreva o problema
- Clique em "Criar"

### 5. Listar Chamados
- Clique em "Meus Chamados"
- Filtre por status
- Clique em um chamado para ver detalhes

### 6. Avaliar Chamado
- Vá para "Avaliar"
- Selecione um chamado concluído
- Dê 1-5 estrelas
- Adicione comentário (opcional)
- Clique em "Enviar Avaliação"

### 7. Ver Perfil
- Clique no menu → "Perfil"
- Veja seus dados
- Clique em "Sair" para logout

---

## 🔗 Endpoints da API (HTTP)

### Autenticação
```
POST   /api/auth/login           - Login
POST   /api/auth/register        - Registrar
POST   /api/auth/logout          - Logout
GET    /api/auth/profile         - Perfil do usuário
```

### Chamados
```
GET    /api/chamados              - Listar meus chamados
POST   /api/chamados              - Criar chamado
GET    /api/chamados/{id}         - Detalhe do chamado
PUT    /api/chamados/{id}         - Atualizar chamado
DELETE /api/chamados/{id}         - Deletar chamado
PATCH  /api/chamados/{id}/status  - Alterar status
```

### Referências
```
GET    /api/reference/locais             - Listar locais
GET    /api/reference/tipos-problema     - Listar tipos
```

### Avaliações
```
GET    /api/feedback              - Listar avaliações
POST   /api/feedback              - Criar avaliação
GET    /api/feedback/{id}         - Detalhe
PUT    /api/feedback/{id}         - Atualizar
DELETE /api/feedback/{id}         - Deletar
```

---

## 🐛 Troubleshooting

### Erro: "Target of URI doesn't exist"
**Solução:** Executar `flutter pub get` e `flutter clean`
```bash
flutter clean
flutter pub get
flutter run
```

### Erro: "Failed to connect to API"
**Verificar:**
1. Backend está rodando: `php artisan serve`
2. API URL está correta em `api_service.dart`
3. Firewall não está bloqueando

### Erro: "Database connection refused"
**Solução:** 
1. Criar banco: `touch database/predialfix.sqlite`
2. Executar migrações: `php artisan migrate`

### Erro: "Sanctum exception"
**Solução:**
```bash
php artisan install:api
php artisan migrate
```

### App não carrega dados
**Checklist:**
- ✅ Backend rodando
- ✅ API URL correta
- ✅ Token salvo no storage
- ✅ Sem erros na console

---

## 📱 Estrutura do Projeto

```
PredialFix/
├── projeto/
│   ├── frontend/                 (Laravel)
│   │   ├── app/
│   │   │   ├── Http/Controllers/Api/
│   │   │   ├── Models/
│   │   │   └── ...
│   │   ├── routes/api.php        (Rotas da API)
│   │   ├── database/migrations/  (Migrações)
│   │   └── .env                  (Configurações)
│   │
│   └── mobile/                   (Flutter)
│       ├── lib/
│       │   ├── main.dart         (Entry point)
│       │   ├── screens/          (Telas)
│       │   ├── services/         (Serviços)
│       │   ├── models/           (Modelos)
│       │   └── theme/            (Tema)
│       └── pubspec.yaml          (Dependências)
│
└── IMPLEMENTACAO_COMPLETA.md    (Este documento)
```

---

## 🎯 Checklist Final

- [ ] Backend rodando em http://localhost:8000
- [ ] Database criado e migrado
- [ ] Dados seed carregados
- [ ] Flutter SDK instalado
- [ ] Dependências Flutter instaladas (`flutter pub get`)
- [ ] API URL configurada corretamente
- [ ] Emulador/dispositivo conectado
- [ ] App executando sem erros
- [ ] Login funcionando
- [ ] Criar chamado funcionando
- [ ] Listar chamados funcionando
- [ ] Avaliar chamados funcionando

---

## 💡 Dicas

1. **Modo debug:** Ativar via `flutter run` (padrão)
2. **Release:** `flutter run --release`
3. **Logs:** Ver no DevTools: `flutter attach`
4. **Hot reload:** Press `r` durante `flutter run`
5. **Hot restart:** Press `R` durante `flutter run`

---

## 📞 Suporte

Se encontrar problemas:
1. Verificar logs na console
2. Consultar seção Troubleshooting
3. Garantir que todas as dependências estão instaladas
4. Tentar `flutter clean` e `flutter pub get` novamente

---

**Pronto para usar! 🎉**
