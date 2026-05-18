# 🎯 INSTRUÇÕES RÁPIDAS - PredialFix

## ⚡ Começar Rápido (5 minutos)

### 1️⃣ Backend (Terminal 1)
```bash
cd projeto/frontend
php artisan serve
```
✅ Rodando em `http://localhost:8000`

### 2️⃣ Mobile (Terminal 2)
```bash
cd projeto/mobile
flutter run
```
✅ App será aberto no emulador/dispositivo

### 3️⃣ Testar
1. **Registrar:**
   - Nome: João Silva
   - Email: joao@example.com
   - Senha: 123456

2. **Criar Chamado:**
   - Local: Sala 101
   - Tipo: Lâmpada Queimada
   - Descrição: A lâmpada da sala não funciona

3. **Ver Chamados:**
   - Menu → Meus Chamados
   - Filtre por status

4. **Avaliar:**
   - Menu → Avaliar
   - Clique em um chamado concluído
   - Dê 5 estrelas
   - Envie

5. **Logout:**
   - Menu → Perfil
   - Clique em "Sair"

---

## 📋 Checklist de Instalação

- [ ] Flutter SDK instalado
- [ ] PHP 8.1+ instalado
- [ ] Composer instalado
- [ ] Git clone do projeto
- [ ] `cd projeto/mobile && flutter pub get`
- [ ] `cd projeto/frontend && composer install`
- [ ] Arquivo `.env` criado no frontend
- [ ] Database criado (SQLite ou MySQL)
- [ ] `php artisan migrate`
- [ ] `php artisan serve` rodando
- [ ] `flutter run` rodando
- [ ] Login funcionando

---

## 🚨 Problemas Comuns

### ❌ "Não consegue conectar à API"
```bash
# 1. Verificar se Laravel está rodando
php artisan serve

# 2. Editar arquivo para apontar para o IP correto
# mobile/lib/services/api_service.dart
# Alterar baseUrl para seu IP

# 3. Verificar firewall
```

### ❌ "Erro no banco de dados"
```bash
# 1. Criar arquivo SQLite
touch database/predialfix.sqlite

# 2. Rodar migrações
php artisan migrate

# 3. Carregar seeds
php artisan seed:run
```

### ❌ "Flutter não encontra dependências"
```bash
flutter clean
flutter pub get
flutter run
```

### ❌ "Emulador não pega Internet"
```bash
# Usar IP do Android Studio
# em api_service.dart: http://10.0.2.2:8000/api
```

---

## 📂 Arquivos Importantes

| Arquivo | O quê | Onde |
|---------|-------|------|
| `api_service.dart` | Configurar IP da API | `mobile/lib/services/` |
| `.env` | Banco de dados | `projeto/frontend/` |
| `api.php` | Rotas da API | `projeto/frontend/routes/` |
| `main.dart` | Início do app | `mobile/lib/` |

---

## 🔑 Dados de Teste

### Usuário Admin
- Email: `admin@predialfix.com`
- Senha: `password`

### Novo Usuário (criar via app)
- Nome: Seu Nome
- Email: seu@email.com
- Senha: 123456 (min)

---

## 💬 Dicas

✨ **Hot Reload:** Durante `flutter run`, pressione `r`

✨ **Hot Restart:** Pressione `R` para reiniciar o app

✨ **Logs:** Ver logs em tempo real durante execução

✨ **Debug:** Use DevTools: `flutter attach`

---

## 📱 Navegação do App

```
Login
  ↓
Home Dashboard
  ├─ Novo Chamado
  ├─ Meus Chamados
  ├─ Avaliar
  ├─ Perfil
  └─ Suporte (Menu)
```

---

## 🎮 Funcionalidades Testáveis

1. ✅ **Registro** - Criar nova conta
2. ✅ **Login** - Entrar com email/senha
3. ✅ **Dashboard** - Ver estatísticas
4. ✅ **Criar Chamado** - Form com validação
5. ✅ **Listar Chamados** - Com filtro por status
6. ✅ **Detalhe Chamado** - Ver informações completas
7. ✅ **Avaliar** - Sistema de 5 estrelas
8. ✅ **Perfil** - Ver e editar dados
9. ✅ **Logout** - Sair com confirmação

---

## 🆘 Precisa de Ajuda?

1. **Verifique os logs** na console
2. **Leia o arquivo** `GUIA_INSTALACAO.md`
3. **Consulte** `IMPLEMENTACAO_COMPLETA.md`
4. **Execute** `flutter clean && flutter pub get`

---

## ✅ Pronto?

Se tudo está verde:
1. ✅ Backend rodando
2. ✅ Mobile compilando
3. ✅ Login funcionando
4. ✅ Dados carregando

**Você está pronto para usar o PredialFix!** 🚀

---

**Dúvidas?** Veja os arquivos de documentação no projeto.

**Boa sorte!** 💚
