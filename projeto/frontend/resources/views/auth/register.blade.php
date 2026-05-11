<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário – PredialFix SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        senai: { red: '#E3000F', dark: '#1a1a1a' },
                    },
                    fontFamily: {
                        sans: ['Segoe UI', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .network-canvas { position: absolute; inset: 0; pointer-events: none; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-100 font-sans">

    <div class="flex flex-1 min-h-0">

        <div class="hidden md:flex flex-col w-1/2 bg-white relative overflow-hidden">

            <div class="absolute top-6 left-6 flex items-center gap-3 z-10">
                <div class="bg-senai-red text-white font-black text-2xl px-3 py-1 tracking-tight select-none">
                    SENAI
                </div>
                <div class="text-gray-700 text-sm leading-tight font-medium">
                    Portal de<br>Chamados
                </div>
            </div>

            <svg class="network-canvas" viewBox="0 0 500 700" preserveAspectRatio="xMidYMid slice"
                 xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <style>
                        .dot { fill: #E3000F; }
                        .line { stroke: #E3000F; stroke-width: 0.8; opacity: 0.25; }
                        @keyframes pulse { 0%,100%{opacity:.6} 50%{opacity:1} }
                        .dot { animation: pulse 3s ease-in-out infinite; opacity:.6; }
                        .dot:nth-child(2n) { animation-delay:1s; }
                        .dot:nth-child(3n) { animation-delay:1.8s; }
                    </style>
                </defs>
                <line class="line" x1="80"  y1="120" x2="260" y2="220"/>
                <line class="line" x1="260" y1="220" x2="400" y2="120"/>
                <line class="line" x1="260" y1="220" x2="180" y2="380"/>
                <line class="line" x1="180" y1="380" x2="380" y2="360"/>
                <line class="line" x1="380" y1="360" x2="420" y2="520"/>
                <line class="line" x1="180" y1="380" x2="60"  y2="490"/>
                <line class="line" x1="60"  y1="490" x2="200" y2="580"/>
                <line class="line" x1="200" y1="580" x2="380" y2="560"/>
                <circle class="dot" cx="80"  cy="120" r="4"/>
                <circle class="dot" cx="260" cy="220" r="5"/>
                <circle class="dot" cx="400" cy="120" r="3.5"/>
                <circle class="dot" cx="180" cy="380" r="4.5"/>
                <circle class="dot" cx="380" cy="360" r="3"/>
                <circle class="dot" cx="420" cy="520" r="4"/>
                <circle class="dot" cx="60"  cy="490" r="3.5"/>
                <circle class="dot" cx="200" cy="580" r="4"/>
                <circle class="dot" cx="380" cy="560" r="3"/>
            </svg>
        </div>

        <div class="flex flex-col justify-center items-center w-full md:w-1/2 bg-gray-100 px-8 py-10 relative overflow-hidden">

            <svg class="network-canvas opacity-20" viewBox="0 0 500 700" preserveAspectRatio="xMidYMid slice"
                 xmlns="http://www.w3.org/2000/svg">
                <line style="stroke:#E3000F;stroke-width:.8;opacity:.2" x1="50" y1="80"  x2="300" y2="200"/>
                <line style="stroke:#E3000F;stroke-width:.8;opacity:.2" x1="300" y1="200" x2="450" y2="80"/>
                <circle style="fill:#E3000F;opacity:.4" cx="50"  cy="80"  r="4"/>
                <circle style="fill:#E3000F;opacity:.4" cx="300" cy="200" r="5"/>
                <circle style="fill:#E3000F;opacity:.4" cx="450" cy="80"  r="3"/>
            </svg>

            <div class="relative z-10 w-full max-w-sm flex flex-col items-center gap-1">

                <h1 class="text-2xl font-semibold text-gray-800 mb-1">Cadastro de Funcionário</h1>

                <div class="bg-senai-red text-white font-black text-3xl px-5 py-2 tracking-tight mb-2 select-none">
                    SENAI
                </div>

                <p class="text-gray-600 text-sm mb-5">Preencha os dados para criar uma conta.</p>

                @if ($errors->any())
                    <div class="w-full bg-red-100 border border-red-300 text-red-700 text-sm rounded px-4 py-3 mb-4">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="w-full flex flex-col gap-4">
                    @csrf

                    <div class="flex flex-col gap-1">
                        <label for="nome" class="text-gray-700 text-sm font-medium">
                            Nome Completo <span class="text-senai-red">*</span>
                        </label>
                        <input
                            id="nome"
                            name="nome"
                            type="text"
                            value="{{ old('nome') }}"
                            required
                            autocomplete="name"
                            placeholder="Digite seu nome completo"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent transition"
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="email" class="text-gray-700 text-sm font-medium">
                            E-mail Institucional <span class="text-senai-red">*</span>
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="seu@email.com"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent transition"
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="senha" class="text-gray-700 text-sm font-medium">
                            Senha <span class="text-senai-red">*</span>
                        </label>
                        <input
                            id="senha"
                            name="senha"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Mínimo de 8 caracteres"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent transition"
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="senha_confirmation" class="text-gray-700 text-sm font-medium">
                            Confirmar Senha <span class="text-senai-red">*</span>
                        </label>
                        <input
                            id="senha_confirmation"
                            name="senha_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Repita a senha"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent transition"
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="cod_entrada" class="text-gray-700 text-sm font-medium">
                            Código de Entrada
                            <span class="text-gray-400 text-xs font-normal">(opcional)</span>
                        </label>
                        <input
                            id="cod_entrada"
                            name="cod_entrada"
                            type="text"
                            inputmode="numeric"
                            value="{{ old('cod_entrada') }}"
                            placeholder="Código fornecido pelo gestor"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent transition"
                        />
                    </div>

                    <button
                        type="submit"
                        class="mt-2 w-full bg-senai-red hover:bg-red-700 text-white font-bold text-base py-3 rounded-full
                               transition duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-senai-red focus:ring-offset-2"
                    >
                        Cadastrar
                    </button>

                    <p class="text-center text-sm text-gray-500 mt-1">
                        Já possui conta?
                        <a href="{{ route('login') }}" class="text-senai-red font-medium hover:underline">
                            Faça Login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

</body>
</html>