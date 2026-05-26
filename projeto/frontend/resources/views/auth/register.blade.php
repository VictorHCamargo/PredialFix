<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PredialFix – Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        senai: { red: '#E3000F' },
                    },
                    fontFamily: {
                        sans: ['Segoe UI', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100 px-4 py-12 font-sans">
    <div class="w-full max-w-md">
        {{-- Logo / Cabeçalho --}}
        <div class="mb-8 text-center">
            <img
                src="{{ asset('images/SENAI_LOGO.png') }}"
                alt="SENAI Logo"
                class="mx-auto mb-3 h-16"
            />
            <h1 class="text-2xl font-bold text-gray-800">PredialFix</h1>
            <p class="mt-1 text-sm text-gray-500">Crie sua conta</p>
        </div>

        {{-- Card de Registro --}}
        <div class="rounded-2xl bg-white p-8 shadow-lg">
            <h2 class="mb-6 text-center text-lg font-semibold text-gray-700">Registre-se</h2>

            {{-- Mensagem de erro geral --}}
            @if (session('error'))
                <div
                    class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                >
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                {{-- Campo Nome --}}
                <div class="mb-4">
                    <label for="nome" class="mb-1 block text-sm font-medium text-gray-700">
                        Nome Completo
                    </label>
                    <input
                        id="nome"
                        type="text"
                        name="nome"
                        value="{{ old('nome') }}"
                        autofocus
                        placeholder="Seu nome completo"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('nome') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                    />
                    @error ('nome')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo E-mail --}}
                <div class="mb-4">
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="seu@email.com"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('email') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                    />
                    @error ('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Senha --}}
                <div class="mb-4">
                    <label for="senha" class="mb-1 block text-sm font-medium text-gray-700">
                        Senha
                    </label>
                    <input
                        id="senha"
                        type="password"
                        name="senha"
                        placeholder="Mínimo 8 caracteres"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('senha') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                    />
                    @error ('senha')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Confirmar Senha --}}
                <div class="mb-6">
                    <label
                        for="senha_confirmation"
                        class="mb-1 block text-sm font-medium text-gray-700"
                    >
                        Confirmar Senha
                    </label>
                    <input
                        id="senha_confirmation"
                        type="password"
                        name="senha_confirmation"
                        placeholder="Confirme sua senha"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('senha_confirmation') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                    />
                    @error ('senha_confirmation')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botão de Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 active:bg-red-800"
                >
                    Criar Conta
                </button>
            </form>

            {{-- Link para login --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Já tem conta?
                    <a
                        href="{{ route('login') }}"
                        class="font-semibold text-red-600 hover:underline"
                        >Faça login</a
                    >
                </p>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">PredialFix &copy; {{ date('Y') }} — SENAI</p>
    </div>


</body>
</html>
