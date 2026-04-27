<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PredialFix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased min-h-screen flex">
    
    <div class="hidden md:flex md:w-1/2 bg-white dark:bg-gray-950 relative flex-col overflow-hidden transition-colors duration-300">
        
        <div class="absolute inset-0 bg-no-repeat bg-cover bg-center dark:opacity-50 transition-all duration-300
                    bg-[image:var(--bg-claro)] 
                    dark:bg-[image:var(--bg-escuro)]"
             style="--bg-claro: url('{{ asset('images/background_senai_light.png') }}'); 
                    --bg-escuro: url('{{ asset('images/background_senai_night.png') }}');">
        </div>

        <div class="relative z-10 p-8 flex items-center">
            <img src="{{ asset('images/senai_logo.png') }}" alt="SENAI Portal de Chamados" class="h-10">
        </div>
    </div>

    <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-8">
        
        <div class="w-full max-w-sm flex flex-col items-center">

            <h1 class="text-xl sm:text-2xl text-gray-800 mb-2 text-center">Bem-Vindo ao PredialFix!</h1>

            <img src="{{ asset('images/senai_logo_red.png') }}" alt="SENAI" class="h-12 mb-2">

            <p class="text-gray-600 mb-8 text-center">Faça seu Login de Docente.</p>

            <x-auth-session-status class="mb-4 w-full" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="w-full">
                @csrf

                <div class="mb-4">
                    <label for="cpf" class="block text-sm text-gray-700 mb-1">Digite seu CPF:</label>
                    <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required autofocus
                        class="w-full bg-gray-300 border border-gray-400 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 py-2 px-3">
                    <x-input-error :messages="$errors->get('cpf')" class="mt-1 text-red-600" />
                </div>

                <div class="mb-4">
                    <label for="senha" class="block text-sm text-gray-700 mb-1">Digite Sua Senha:</label>
                    <input id="senha" type="password" name="senha" required
                        class="w-full bg-gray-300 border border-gray-400 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 py-2 px-3">
                    <x-input-error :messages="$errors->get('senha')" class="mt-1 text-red-600" />
                </div>


                <div class="flex justify-center">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-10 rounded-full transition duration-150 ease-in-out">
                        Entrar
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>
</html>