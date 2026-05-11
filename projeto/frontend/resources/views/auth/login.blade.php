<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PredialFix – Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        senai: { red: '#E3000F' }
                    },
                    fontFamily: {
                        sans: ['Segoe UI', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-100 font-sans flex items-center justify-center">

    <div class="w-full max-w-md">

        {{-- Logo / Cabeçalho --}}
        <div class="text-center mb-8">
            <div class="inline-block bg-red-600 text-white font-black text-3xl px-5 py-2 tracking-tight mb-3">
                SENAI
            </div>
            <h1 class="text-2xl font-bold text-gray-800">PredialFix</h1>
            <p class="text-gray-500 text-sm mt-1">Sistema de Gestão de Chamados</p>
        </div>

        {{-- Card de Login --}}
        <div class="bg-white rounded-2xl shadow-lg p-8">

            <h2 class="text-lg font-semibold text-gray-700 mb-6 text-center">Acesse sua conta</h2>

            {{-- Mensagem de erro geral (ex: vindo de redirect) --}}
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Campo E-mail --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
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
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Código de Entrada --}}
                <div class="mb-6">
                    <label for="cod_entrada" class="block text-sm font-medium text-gray-700 mb-1">
                        Código de Entrada
                    </label>
                    <input
                        id="cod_entrada"
                        type="number"
                        name="cod_entrada"
                        autocomplete="off"
                        placeholder="Digite seu código"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('cod_entrada') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                    >
                    @error('cod_entrada')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lembrar sessão --}}
                <div class="flex items-center mb-6">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Manter conectado
                    </label>
                </div>

                {{-- Botão de Submit --}}
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800
                           text-white font-semibold py-2.5 rounded-lg transition text-sm"
                >
                    Entrar
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            PredialFix &copy; {{ date('Y') }} — SENAI
        </p>
    </div>

</body>
</html>
