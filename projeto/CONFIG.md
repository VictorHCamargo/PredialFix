# 🚀 Guia de Configuração do Projeto Laravel — Laragon

> Guia completo para rodar o projeto **PredialFix** no Laragon (Windows), do zero ao servidor local.

---

## 📋 Índice

1. [Pré-requisitos](#-pré-requisitos-antes-de-qualquer-coisa)
2. [Clonando o Projeto no Laragon](#-clonando-o-projeto-no-laragon)
3. [Instalando as Dependências](#-instalando-as-dependências)
4. [Configurações Pós-instalação](#-configurações-pós-instalação)
5. [Rodando o Projeto](#-rodando-o-projeto)
6. [Breeze vs Filament](#-breeze-vs-filament---qual-usar)
7. [Comandos Úteis do Dia a Dia](#-comandos-úteis-do-dia-a-dia)
8. [Solução de Problemas](#-solução-de-problemas)

---

## ✅ Pré-requisitos (antes de qualquer coisa)

### Laragon instalado e configurado

Este projeto roda dentro do **Laragon**. Certifique-se de que o Laragon está instalado e com os seguintes serviços ativos:

- **Apache** (ou Nginx) — servidor web
- **MySQL** — banco de dados
- **PHP 8.3+** — versão selecionada no menu do Laragon

> 💡 Para trocar a versão do PHP no Laragon: clique com o botão direito na bandeja do sistema → **PHP** → selecione `8.3.x`.

### Ferramentas necessárias no PATH

Abra o terminal do Laragon (**botão Terminal** na interface) e verifique:

| Ferramenta | Versão mínima | Como verificar |
|---|---|---|
| **PHP** | `^8.3` | `php -v` |
| **Composer** | `^2.x` | `composer -V` |
| **Node.js** | `^18.x` | `node -v` |
| **NPM** | `^9.x` | `npm -v` |
| **Git** | qualquer | `git --version` |

> 💡 O terminal do Laragon já inclui PHP, Composer, Node e Git no PATH automaticamente. Use sempre ele para os comandos deste guia.

### Extensões PHP obrigatórias

```bash
php -m | grep -E "pdo_mysql|mysqli|mbstring|openssl|fileinfo|gd|intl|curl"
```

As extensões necessárias são: `pdo_mysql`, `mysqli`, `mbstring`, `openssl`, `fileinfo`, `gd`, `intl`, `curl`.

> 💡 Se alguma estiver faltando, abra o `php.ini` correspondente à versão ativa no Laragon (Menu → PHP → `php.ini`) e remova o `;` na frente da extensão.

---

## 📥 Clonando o Projeto no Laragon

O Laragon serve automaticamente qualquer pasta dentro de `C:\laragon\www`. O projeto **deve** ser clonado nesse caminho para funcionar corretamente.

```bash
# Acesse a pasta www do Laragon
cd C:/laragon/www

# Clone o repositório (a pasta criada será o nome do projeto)
git clone <URL_DO_REPOSITORIO> frontend

# Entre na pasta do projeto
cd frontend
```

> ⚠️ O nome da pasta (`frontend` no exemplo) define a URL local. Com o **Pretty URLs** ativado no Laragon, o projeto ficará acessível em `http://localhost:8000`. Se preferir outro nome, ajuste o clone e o `APP_URL` no `.env` de acordo.

Após clonar, clique em **Reload** na interface do Laragon para que ele reconheça o novo projeto.

---

## 📦 Instalando as Dependências

Use sempre o **terminal do Laragon** (botão Terminal na interface).

### 1. Dependências PHP (Composer)

```bash
composer install
```

> ⚠️ **Nunca use `composer update`** em um projeto existente sem alinhamento com o time — isso pode atualizar pacotes e quebrar compatibilidades.

### 2. Dependências JavaScript (NPM)

```bash
npm install --ignore-scripts
```

---

## ⚙️ Configurações Pós-instalação

Siga os passos **nesta ordem** para não ter problemas.

### Passo 1 — Criar o arquivo `.env`

```bash
copy .env.example .env
```

### Passo 2 — Gerar a chave da aplicação

```bash
php artisan key:generate
```

> ⚠️ **Obrigatório!** Sem essa chave, sessões, cookies e dados criptografados não funcionam.

### Passo 3 — Criar o banco de dados no MySQL

Este projeto usa **MySQL** (fornecido pelo Laragon). Você precisa criar o banco antes de rodar as migrations.

**Opção A — via HeidiSQL** (já vem com o Laragon):
1. Abra o HeidiSQL pelo menu do Laragon
2. Conecte na instância local (usuário `root`, senha em branco por padrão)
3. Clique com o botão direito → **Criar novo banco de dados**
4. Nome: `predial_fix_tb_g2v` → Colação: `utf8mb4_unicode_ci`

**Opção B — via terminal:**
```bash
mysql -u root -e "CREATE DATABASE predial_fix_tb_g2v CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Passo 4 — Configurar o `.env`

Abra o arquivo `.env` e ajuste as seguintes variáveis:

```env
APP_NAME=PredialFix
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=predial_fix_tb_g2v
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

> 💡 `DB_PASSWORD` fica em branco pois o MySQL do Laragon não tem senha por padrão. Se você configurou senha, preencha aqui.


### Passo 5 — Rodar as Migrations (criar as tabelas)

```bash
php artisan migrate
```

### Passo 6 — Compilar os assets frontend (Vite)

```bash
# Para gerar os assets uma única vez (recomendado antes de subir)
npm run build

# OU para desenvolvimento com hot-reload (mantém rodando em segundo plano)
npm run dev
```

---

## 🟢 Rodando o Projeto

Como o Laragon já fornece o servidor web (Apache/Nginx), **não é necessário rodar `php artisan serve`**. Basta abrir o navegador na URL do projeto.

### Acessando o projeto

Com o Laragon rodando (Apache + MySQL ativos), acesse:

```
http://localhost:8000
```


### Rodando filas e logs em desenvolvimento

Para processar filas e visualizar logs em tempo real, abra o **terminal do Laragon** e execute:

```bash
composer run dev
```

Isso inicia simultaneamente:
- `php artisan serve` → servidor PHP auxiliar em `http://localhost:8000` *(opcional no Laragon, mas não causa conflito)*
- `php artisan queue:listen` → processador de filas
- `php artisan pail` → visualizador de logs em tempo real
- `npm run dev` → Vite com hot-reload

> 💡 Se preferir rodar apenas o necessário sem o `artisan serve`, abra dois terminais:
>
> **Terminal 1:** `php artisan queue:listen --tries=1`
>
> **Terminal 2:** `npm run dev`

### Setup completo automatizado (apenas na primeira vez)

O projeto tem um script que executa toda a configuração de uma vez:

```bash
composer run setup
```

Ele roda na sequência: `composer install` → copia `.env.example` → `key:generate` → `migrate --force` → `npm install` → `npm run build`.

> ⚠️ Use apenas em projetos recém-clonados — esse script roda migrations com `--force`.
> **Não substitui** a criação manual do banco de dados MySQL (Passo 3).

---

## 🔀 Breeze vs Filament — Qual usar?

Este projeto usa **as duas ferramentas**, mas para propósitos diferentes.

### 🌿 Laravel Breeze

**O que é:** Kit de autenticação simples para o **frontend voltado ao usuário final** (login, registro, recuperação de senha, perfil).

**Quando usar:**
- Telas de login e cadastro do usuário comum
- Área do cliente/usuário da aplicação
- Páginas Blade com Tailwind CSS + Alpine.js

**Onde está no projeto:**
```
resources/views/auth/         → telas de login, registro, etc.
resources/views/profile/      → tela de perfil do usuário
app/Http/Controllers/Auth/    → controllers de autenticação
```

**Rota de acesso:** `http://localhost:8000/login`

### 🎛️ Filament

**O que é:** Painel administrativo completo para **gestão interna** dos dados.

**Quando usar:**
- Painel de administração (CRUD de usuários, chamados, equipamentos, etc.)
- Área restrita para gestores/admins
- Relatórios e dashboards internos

**Onde está no projeto:**
```
app/Filament/Resources/       → recursos CRUD
app/Providers/Filament/       → configuração do painel admin
config/filament.php           → configurações globais do Filament
```

**Rota de acesso:** `http://localhost:8000/admin`

> ⚠️ Para acessar o painel `/admin`, o usuário precisa ter permissão. Configure no `AdminPanelProvider.php` ou implemente `canAccessPanel()` no model `User`.

### Resumo rápido

| | Breeze | Filament |
|---|---|---|
| **Para quem?** | Usuário final | Administrador |
| **Complexidade** | Simples | Completo |
| **CRUD automático** | ❌ | ✅ |
| **Rota padrão** | `/login`, `/dashboard` | `/admin` |

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

### ❌ Erros de conexão com MySQL / "Access denied"
Verifique se o MySQL está ativo no Laragon e confira as credenciais no `.env`:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=
```
Confirme também que o banco `predial_fix_tb_g2v` foi criado (Passo 3).

### ❌ "Class not found" ou erros de autoload
```bash
composer dump-autoload
```

### ❌ Página em branco ou erro 500
```bash
# Verifique os logs
php artisan pail

# Ou abra direto o arquivo de log
tail -n 50 storage/logs/laravel.log
```
Certifique-se também de que `APP_DEBUG=true` está no `.env`.

### ❌ Assets CSS/JS não carregam
```bash
npm run build
```
Ou verifique se o `npm run dev` está rodando em paralelo.

### ❌ "Permission denied" em storage ou bootstrap/cache
No terminal do Laragon (como Administrador):
```bash
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap/cache /grant Everyone:(OI)(CI)F /T
```

### ❌ `http://localhost:8000` não abre / erro 404
- Confirme que o Laragon está rodando (Apache ativo)
- Confirme que a pasta do projeto está em `C:\laragon\www\frontend`
- Clique em **Reload** na interface do Laragon
- Verifique se o Pretty URLs está ativo (Menu → Apache → Virtual Hosts)

### ❌ Filament admin não abre / redireciona para login
Certifique-se de que existe um usuário no banco e que ele tem acesso ao painel. No `User.php`, implemente:
```php
public function canAccessPanel(Panel $panel): bool
{
    return true; // ou adicione lógica de role aqui
}
```

---

> 📌 **Checklist rápido para nova máquina:**
> **Laragon ativo** → **Clone em `C:\laragon\www`** → `composer install` → `npm install --ignore-scripts` → `copy .env.example .env` → Criar banco MySQL → Configurar `.env` → `php artisan key:generate` → `php artisan migrate` → `npm run build` → Acessar `http://localhost:8000`
