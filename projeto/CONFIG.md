# 🚀 Guia de Configuração do Projeto Laravel

> Guia completo para rodar o projeto em qualquer máquina, do zero ao servidor.

---

## 📋 Índice

1. [Pré-requisitos](#-pré-requisitos-antes-de-qualquer-coisa)
2. [Clonando o Projeto](#-clonando-o-projeto)
3. [Instalando as Dependências](#-instalando-as-dependências)
4. [Configurações Pós-instalação](#-configurações-pós-instalação)
5. [Rodando o Projeto](#-rodando-o-projeto)
6. [Breeze vs Filament](#-breeze-vs-filament---qual-usar)
7. [Comandos Úteis do Dia a Dia](#-comandos-úteis-do-dia-a-dia)
8. [Solução de Problemas](#-solução-de-problemas)

---

## ✅ Pré-requisitos (antes de qualquer coisa)

Antes de clonar o projeto, certifique-se de ter instalado na sua máquina:

| Ferramenta | Versão mínima | Como verificar | Download |
|---|---|---|---|
| **PHP** | `^8.3` | `php -v` | [php.net](https://www.php.net/downloads) |
| **Composer** | `^2.x` | `composer -V` | [getcomposer.org](https://getcomposer.org) |
| **Node.js** | `^18.x` | `node -v` | [nodejs.org](https://nodejs.org) |
| **NPM** | `^9.x` | `npm -v` | *(vem junto com Node)* |
| **Git** | qualquer | `git --version` | [git-scm.com](https://git-scm.com) |

### Extensões PHP obrigatórias

O Laravel exige algumas extensões do PHP. Verifique se estão habilitadas:

```bash
php -m | grep -E "pdo_mysql|mysqli|mbstring|openssl|fileinfo|gd|intl|curl"
```

As extensões necessárias são: `pdo_mysql`, `mysqli`, `mbstring`, `openssl`, `fileinfo`,`gb`,`intl`, `curl`.

> 💡 **Dica Windows:** Se estiver usando o XAMPP ou similar, abra o `php.ini` e remova o `;` (ponto e vírgula) na frente das extensões listadas acima para habilitá-las.

---

## 📥 Clonando o Projeto

```bash
# Clone o repositório
git clone <URL_DO_REPOSITORIO> nome-da-pasta

# Entre na pasta do projeto
cd nome-da-pasta
```

---

## 📦 Instalando as Dependências

### 1. Dependências PHP (Composer)

```bash
composer install
```

> ⚠️ **Nunca use `composer update`** em um projeto existente sem alinhamento com o time — isso pode atualizar pacotes e quebrar compatibilidades.

### 2. Dependências JavaScript (NPM)

```bash
npm install
```

---

## ⚙️ Configurações Pós-instalação

Siga os passos **nesta ordem** para não ter problemas.

### Passo 1 — Criar o arquivo `.env`

O `.env` contém as configurações sensíveis do projeto e **não é versionado no Git**.

```bash
cp .env.example .env
```

### Passo 2 — Gerar a chave da aplicação

```bash
php artisan key:generate
```

> ⚠️ **Obrigatório!** Sem essa chave, sessões, cookies e dados criptografados não funcionam.

### Passo 3 — Criar o arquivo do banco de dados (SQLite)

Este projeto usa **SQLite** — não precisa instalar MySQL ou PostgreSQL!

```bash
# Linux/macOS
touch database/database.sqlite

# Windows (PowerShell)
New-Item -ItemType File -Path "database/database.sqlite"

# Windows (CMD)
type nul > database\database.sqlite
```

### Passo 4 — Verificar as configurações do `.env`

Abra o arquivo `.env` e confirme que estas variáveis estão corretas:

```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

> 💡 `DB_HOST`, `DB_PORT`, `DB_USERNAME` e `DB_PASSWORD` podem ser ignorados quando `DB_CONNECTION=sqlite`.

### Passo 5 — Rodar as Migrations (criar as tabelas)

```bash
php artisan migrate
```

Se pedir confirmação em ambiente de produção, adicione `--force`:

```bash
php artisan migrate --force
```

### Passo 6 — Compilar os assets frontend

```bash
# Para desenvolvimento (compila uma vez)
npm run build

# OU para desenvolvimento com hot-reload (mantém rodando)
npm run dev
```

---

## 🟢 Rodando o Projeto

### Opção A — Comando único (recomendado para desenvolvimento)

O projeto possui um script `dev` configurado no `composer.json` que sobe tudo de uma vez:

```bash
composer run dev
```

Isso inicia simultaneamente:
- `php artisan serve` → servidor PHP em `http://localhost:8000`
- `php artisan queue:listen` → processador de filas
- `php artisan pail` → visualizador de logs em tempo real
- `npm run dev` → Vite com hot-reload

### Opção B — Separado (caso tenha problemas com o comando acima)

Abra **dois terminais** e rode em cada um:

**Terminal 1:**
```bash
php artisan serve
```

**Terminal 2:**
```bash
npm run dev
```

### Opção C — Setup completo automatizado

O projeto também tem um script que faz tudo de uma vez (clone → configure → rode):

```bash
composer run setup
```

> ⚠️ Use apenas em projetos novos — esse script roda migrations com `--force`.

---

## 🔀 Breeze vs Filament — Qual usar?

Este projeto usa **as duas ferramentas**, mas para propósitos diferentes. Entenda quando usar cada uma:

---

### 🌿 Laravel Breeze

**O que é:** Kit de autenticação simples para o **frontend voltado ao usuário final** (login, registro, recuperação de senha, perfil).

**Quando usar:**
- Telas de login e cadastro do usuário comum
- Área do cliente/usuário da aplicação
- Páginas Blade com Tailwind CSS + Alpine.js
- Projetos que precisam de uma UI customizável e simples

**Onde está no projeto:**
```
resources/views/auth/         → telas de login, registro, etc.
resources/views/profile/      → tela de perfil do usuário
app/Http/Controllers/Auth/    → controllers de autenticação
```

**Rota de acesso:** `http://localhost:8000/login`

---

### 🎛️ Filament

**O que é:** Painel administrativo completo (admin panel) para **gestão interna** dos dados, com tabelas, formulários e dashboards prontos.

**Quando usar:**
- Painel de administração (CRUD de usuários, perfis, etc.)
- Área restrita para gestores/admins
- Quando precisa de interfaces de gerenciamento rápidas e completas
- Relatórios e dashboards internos

**Onde está no projeto:**
```
app/Filament/Resources/       → recursos CRUD (UserResource, ProfileResource)
app/Providers/Filament/       → configuração do painel admin
config/filament.php           → configurações globais do Filament
```

**Rota de acesso:** `http://localhost:8000/admin`

> ⚠️ Para acessar o painel `/admin`, o usuário precisa ter permissão. Configure no `AdminPanelProvider.php` ou use `canAccessPanel()` no model `User`.

---

### Resumo rápido

| | Breeze | Filament |
|---|---|---|
| **Para quem?** | Usuário final | Administrador |
| **Complexidade** | Simples | Completo |
| **Customização** | Alta (Blade/CSS livre) | Média (componentes próprios) |
| **CRUD automático** | ❌ | ✅ |
| **Rota padrão** | `/login`, `/dashboard` | `/admin` |
| **Ideal para** | Frontend da app | Backoffice / gestão |

---

## 🛠️ Comandos Úteis do Dia a Dia

```bash
# Limpar todos os caches (use quando algo estranho acontecer)
php artisan optimize:clear

# Criar uma nova migration
php artisan make:migration create_tabela_table

# Reverter e refazer todas as migrations (⚠️ apaga os dados)
php artisan migrate:fresh

# Reverter e refazer com seeders
php artisan migrate:fresh --seed

# Ver todas as rotas registradas
php artisan route:list

# Criar um Resource no Filament
php artisan make:filament-resource NomeDoModel

# Ver logs em tempo real
php artisan pail

# Acessar o console interativo do Laravel
php artisan tinker
```

---

## 🔧 Solução de Problemas

### ❌ "APP_KEY is not set"
```bash
php artisan key:generate
```

### ❌ "Database file not found" ou erros de SQLite
```bash
# Linux/macOS
touch database/database.sqlite

# Windows (PowerShell)
New-Item -ItemType File -Path "database/database.sqlite"
```
Depois rode `php artisan migrate`.

### ❌ "Class not found" ou erros de autoload
```bash
composer dump-autoload
```

### ❌ Página em branco ou erro 500
```bash
# Verifique os logs
tail -n 50 storage/logs/laravel.log

# Ou use o pail
php artisan pail
```

### ❌ Assets CSS/JS não carregam
```bash
npm run build
```
Ou verifique se o `npm run dev` está rodando em paralelo.

### ❌ "Permission denied" em storage ou bootstrap/cache (Linux/macOS)
```bash
chmod -R 775 storage bootstrap/cache
```

### ❌ Filament admin não abre / redireciona para login
Certifique-se de que existe um usuário no banco e que ele tem acesso ao painel. No `User.php`, implemente:
```php
public function canAccessPanel(Panel $panel): bool
{
    return true; // ou adicione lógica de role aqui
}
```

---

> 📌 **Dica final:** Sempre que clonar o projeto em uma nova máquina, siga a ordem: **Pré-requisitos → Clone → `composer install` → `npm install` → `.env` → `key:generate` → `sqlite` → `migrate` → `npm run build` → `composer run dev`**