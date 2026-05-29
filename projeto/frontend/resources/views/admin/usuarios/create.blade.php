<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Novo Usuario - PredialFix SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { senai: { red: '#E3000F', dark: '#1a1a1a' } },
                    fontFamily: { sans: ['Segoe UI', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen flex-col bg-gray-50 font-sans">
    <x-navbar />

    <main class="mx-auto w-full max-w-3xl flex-1 px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.usuarios.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Voltar para usuarios</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-800">Novo funcionario</h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-5 rounded border border-gray-200 bg-white p-6 shadow">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="nome" class="mb-1 block text-sm font-semibold text-gray-800">Nome</label>
                    <input id="nome" name="nome" type="text" value="{{ old('nome') }}" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-gray-800">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="senha" class="mb-1 block text-sm font-semibold text-gray-800">Senha inicial</label>
                    <input id="senha" name="senha" type="password" required minlength="8" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="nivel_acesso" class="mb-1 block text-sm font-semibold text-gray-800">Nivel de acesso</label>
                    <select id="nivel_acesso" name="nivel_acesso" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="" disabled {{ old('nivel_acesso') ? '' : 'selected' }}>Selecione</option>
                        <option value="administrador" @selected(old('nivel_acesso') === 'administrador')>Administrador</option>
                        <option value="gerente_manutencao" @selected(old('nivel_acesso') === 'gerente_manutencao')>Gerente de manutencao</option>
                        <option value="tecnico_manutencao" @selected(old('nivel_acesso') === 'tecnico_manutencao')>Tecnico de manutencao</option>
                        <option value="professor" @selected(old('nivel_acesso') === 'professor')>Professor</option>
                        <option value="aluno" @selected(old('nivel_acesso') === 'aluno')>Aluno</option>
                    </select>
                </div>

                <div>
                    <label for="cod_entrada" class="mb-1 block text-sm font-semibold text-gray-800">Cracha</label>
                    <input id="cod_entrada" name="cod_entrada" type="number" value="{{ old('cod_entrada') }}" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="setor" class="mb-1 block text-sm font-semibold text-gray-800">Setor</label>
                    <input id="setor" name="setor" type="text" value="{{ old('setor') }}" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">
                    Cadastrar
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="rounded border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </main>

    <x-footer />
</body>
</html>
