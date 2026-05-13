<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PredialFix – Login</title>
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
<body class="flex min-h-screen items-center justify-center bg-gray-100 font-sans">
    <div class="w-full max-w-md">
        {{-- Logo / Cabeçalho --}}
        <div class="mb-8 text-center">
            <img
                src="{{ asset('images/SENAI_LOGO.png') }}"
                alt="SENAI Logo"
                class="mx-auto mb-3 h-16"
            />
            <h1 class="text-2xl font-bold text-gray-800">PredialFix</h1>
            <p class="mt-1 text-sm text-gray-500">Sistema de Gestão de Chamados</p>
        </div>

        {{-- Card de Login --}}
        <div class="rounded-2xl bg-white p-8 shadow-lg">
            <h2 class="mb-6 text-center text-lg font-semibold text-gray-700">Acesse sua conta</h2>

            {{-- Mensagem de erro geral (ex: vindo de redirect) --}}
            @if (session('error'))
                <div
                    class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                >
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Campo E-mail --}}
                <div class="mb-5">
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
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
                <div class="mb-6">
                    <label for="senha" class="mb-1 block text-sm font-medium text-gray-700">
                        Senha
                    </label>
                    <input
                        id="senha"
                        type="password"
                        name="senha"
                        autocomplete="current-password"
                        placeholder="Digite sua senha"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('senha') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                    />
                    @error ('senha')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lembrar sessão --}}
                <div class="mb-6 flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                    />
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Manter conectado
                    </label>
                </div>

                {{-- Botão de Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 active:bg-red-800"
                >
                    Entrar
                </button>
            </form>

            {{-- Link para registro --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Não tem conta?
                    <a
                        href="{{ route('register') }}"
                        class="font-semibold text-red-600 hover:underline"
                        >Registre-se aqui</a
                    >
                </p>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">PredialFix &copy; {{ date('Y') }} — SENAI</p>
    </div>
</body>
</html>
