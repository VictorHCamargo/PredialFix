<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PredialFix – Registro</title>
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
<body class="min-h-screen bg-gray-100 font-sans flex items-center justify-center py-12 px-4">

    <div class="w-full max-w-md">

        {{-- Logo / Cabeçalho --}}
        <div class="text-center mb-8">
            <div class="inline-block bg-red-600 text-white font-black text-3xl px-5 py-2 tracking-tight mb-3">
                SENAI
            </div>
            <h1 class="text-2xl font-bold text-gray-800">PredialFix</h1>
            <p class="text-gray-500 text-sm mt-1">Crie sua conta</p>
        </div>

        {{-- Card de Registro --}}
        <div class="bg-white rounded-2xl shadow-lg p-8">

            <h2 class="text-lg font-semibold text-gray-700 mb-6 text-center">Registre-se</h2>

            {{-- Mensagem de erro geral --}}
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                {{-- Campo Nome --}}
                <div class="mb-4">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
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
                    >
                    @error('nome')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo E-mail --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
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
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Nível de Acesso --}}
                <div class="mb-4">
                    <label for="nivel_acesso" class="block text-sm font-medium text-gray-700 mb-1">
                        Nível de Acesso
                    </label>
                    <select
                        id="nivel_acesso"
                        name="nivel_acesso"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('nivel_acesso') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                    >
                        <option value="">Selecione seu nível</option>
                        <option value="professor" @selected(old('nivel_acesso') === 'professor')>Professor</option>
                        <option value="aluno" @selected(old('nivel_acesso') === 'aluno')>Aluno</option>
                        <option value="visitante" @selected(old('nivel_acesso') === 'visitante')>Visitante</option>
                    </select>
                    @error('nivel_acesso')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">
                        <strong>Professor/Aluno:</strong> Pode criar e visualizar chamados<br>
                        <strong>Visitante:</strong> Apenas visualiza seus chamados
                    </p>
                </div>

                {{-- Campo Setor (opcional) --}}
                <div class="mb-4">
                    <label for="setor" class="block text-sm font-medium text-gray-700 mb-1">
                        Setor/Departamento (opcional)
                    </label>
                    <input
                        id="setor"
                        type="text"
                        name="setor"
                        value="{{ old('setor') }}"
                        placeholder="Ex: Departamento de Informática"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               border-gray-300"
                    >
                </div>

                {{-- Campo Senha --}}
                <div class="mb-4">
                    <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">
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
                    >
                    @error('senha')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Confirmar Senha --}}
                <div class="mb-6">
                    <label for="senha_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
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
                    >
                    @error('senha_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botão de Submit --}}
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800
                           text-white font-semibold py-2.5 rounded-lg transition text-sm"
                >
                    Criar Conta
                </button>
            </form>

            {{-- Link para login --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Já tem conta? <a href="{{ route('login') }}" class="text-red-600 font-semibold hover:underline">Faça login</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            PredialFix &copy; {{ date('Y') }} — SENAI
        </p>
    </div>

</body>
</html>
