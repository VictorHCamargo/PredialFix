<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Perfil – PredialFix</title>
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

    <main class="mx-auto w-full max-w-2xl flex-1 px-6 py-8">
        {{-- Cabeçalho --}}
        <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-gray-800">Editar Perfil</h1>
            <p class="text-gray-600">Atualize suas informações</p>
        </div>

        {{-- Alertas --}}
        @if (session('success'))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulário de Edição --}}
        <div class="rounded-lg bg-white p-8 shadow">
            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf
                @method('PATCH')

                {{-- Nome --}}
                <div class="mb-6">
                    <label for="nome" class="mb-2 block text-sm font-medium text-gray-700">
                        Nome Completo
                    </label>
                    <input
                        id="nome"
                        type="text"
                        name="nome"
                        value="{{ old('nome', $user->nome) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-red-500 @error('nome') border-red-400 bg-red-50 @enderror"
                    />
                    @error('nome')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- E-mail --}}
                <div class="mb-6">
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border-red-400 bg-red-50 @enderror"
                    />
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @if (!$user->isAluno())
                    {{-- Setor --}}
                    <div class="mb-6">
                        <label for="setor" class="mb-2 block text-sm font-medium text-gray-700">
                            Setor/Departamento
                        </label>
                        <input
                            id="setor"
                            type="text"
                            name="setor"
                            value="{{ old('setor', $user->setor) }}"
                            placeholder="Ex: Departamento de Informática"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-red-500"
                        />
                    </div>
                @endif

                {{-- Botões --}}
                <div class="flex gap-3 pt-4">
                    <button
                        type="submit"
                        class="flex-1 rounded-lg bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-700 active:scale-95"
                    >
                        Salvar Alterações
                    </button>
                    <a
                        href="{{ route('profile.show') }}"
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-6 py-3 text-center font-semibold text-gray-800 transition hover:bg-gray-50"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Link para voltar --}}
        <div class="mt-6 text-center">
            <a href="{{ route('profile.show') }}" class="text-sm text-red-600 hover:underline">
                ← Voltar ao Perfil
            </a>
        </div>
    </main>

    <footer class="mt-8 bg-senai-red">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-8 px-6 py-8 md:grid-cols-2">
            <div class="text-white">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide">Edifício Sede FIESP</h3>
                <p class="text-sm leading-relaxed text-red-100">
                    Av. Paulista, 1313, São Paulo/SP<br />
                    CEP 01311-923
                </p>
            </div>
            <div class="text-white">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide">Central de Relacionamento</h3>
                <p class="text-sm leading-relaxed text-red-100">
                    (11) 3322-0050 (Telefone/WhatsApp)<br />
                    0800-055-1000 (Interior de SP,<br />
                    somente telefone fixo)
                </p>
            </div>
        </div>
        <div class="bg-red-900 py-3 text-center text-xs text-red-200">
            Copyright 2026 &copy; Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
