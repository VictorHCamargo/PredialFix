<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – PredialFix SENAI</title>
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
        /* Rede de pontos animada no painel esquerdo */
        .network-canvas { position: absolute; inset: 0; pointer-events: none; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-100 font-sans">
    <div class="w-full bg-senai-dark text-white text-xs px-4 py-1 select-none">Login</div>

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

            <svg class="network-canvas" viewBox="0 0 500 600" preserveAspectRatio="xMidYMid slice"
                 xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <style>
                        .dot { fill: #E3000F; opacity: 0.6; }
                        .line { stroke: #E3000F; stroke-width: 0.8; opacity: 0.25; }
                        @keyframes pulse { 0%,100%{opacity:.6} 50%{opacity:1} }
                        .dot { animation: pulse 3s ease-in-out infinite; }
                        .dot:nth-child(2n) { animation-delay:1s; }
                        .dot:nth-child(3n) { animation-delay:1.8s; }
                    </style>
                </defs>
                <line class="line" x1="80"  y1="120" x2="260" y2="200"/>
                <line class="line" x1="260" y1="200" x2="400" y2="100"/>
                <line class="line" x1="260" y1="200" x2="180" y2="350"/>
                <line class="line" x1="180" y1="350" x2="380" y2="320"/>
                <line class="line" x1="380" y1="320" x2="420" y2="480"/>
                <line class="line" x1="180" y1="350" x2="60"  y2="440"/>
                <line class="line" x1="60"  y1="440" x2="200" y2="520"/>
                <line class="line" x1="200" y1="520" x2="380" y2="500"/>
                <line class="line" x1="380" y1="500" x2="420" y2="480"/>

                <circle class="dot" cx="80"  cy="120" r="4"/>
                <circle class="dot" cx="260" cy="200" r="5"/>
                <circle class="dot" cx="400" cy="100" r="3.5"/>
                <circle class="dot" cx="180" cy="350" r="4.5"/>
                <circle class="dot" cx="380" cy="320" r="3"/>
                <circle class="dot" cx="420" cy="480" r="4"/>
                <circle class="dot" cx="60"  cy="440" r="3.5"/>
                <circle class="dot" cx="200" cy="520" r="4"/>
                <circle class="dot" cx="380" cy="500" r="3"/>
                <circle class="dot" cx="130" cy="260" r="2.5"/>
                <circle class="dot" cx="310" cy="420" r="2.5"/>
                <circle class="dot" cx="450" cy="250" r="2"/>
            </svg>
        </div>

        <div class="flex flex-col justify-center items-center w-full md:w-1/2 bg-gray-100 px-8 py-12 relative overflow-hidden">
            <svg class="network-canvas opacity-30" viewBox="0 0 500 600" preserveAspectRatio="xMidYMid slice"
                 xmlns="http://www.w3.org/2000/svg">
                <line style="stroke:#E3000F;stroke-width:.8;opacity:.2" x1="50"  y1="80"  x2="300" y2="180"/>
                <line style="stroke:#E3000F;stroke-width:.8;opacity:.2" x1="300" y1="180" x2="450" y2="60"/>
                <line style="stroke:#E3000F;stroke-width:.8;opacity:.2" x1="300" y1="180" x2="200" y2="380"/>
                <line style="stroke:#E3000F;stroke-width:.8;opacity:.2" x1="200" y1="380" x2="420" y2="350"/>
                <circle style="fill:#E3000F;opacity:.5" cx="50"  cy="80"  r="4"/>
                <circle style="fill:#E3000F;opacity:.5" cx="300" cy="180" r="5"/>
                <circle style="fill:#E3000F;opacity:.5" cx="450" cy="60"  r="3"/>
                <circle style="fill:#E3000F;opacity:.5" cx="200" cy="380" r="4"/>
                <circle style="fill:#E3000F;opacity:.5" cx="420" cy="350" r="3"/>
            </svg>

            <div class="relative z-10 w-full max-w-sm flex flex-col items-center gap-1">

                <h1 class="text-2xl font-semibold text-gray-800 mb-1">Bem-Vindo ao PredialFix!</h1>

                <div class="bg-senai-red text-white font-black text-3xl px-5 py-2 tracking-tight mb-2 select-none">
                    SENAI
                </div>

                <p class="text-gray-600 text-sm mb-6">Faça seu Login de Docente.</p>

                @if ($errors->any())
                    <div class="w-full bg-red-100 border border-red-300 text-red-700 text-sm rounded px-4 py-3 mb-4">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="w-full bg-green-100 border border-green-300 text-green-700 text-sm rounded px-4 py-3 mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="w-full flex flex-col gap-4">
                    @csrf

                    <div class="flex flex-col gap-1">
                        <label for="email" class="text-gray-700 text-sm font-medium">Digite seu CPF:</label>
                        <input
                            id="email"
                            name="email"
                            type="text"
                            inputmode="numeric"
                            maxlength="14"
                            placeholder="000.000.000-00"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent
                                   transition"
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="password" class="text-gray-700 text-sm font-medium">Digite Sua Senha:</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent
                                   transition"
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="cod_entrada" class="text-gray-700 text-sm font-medium">Digite seu Código de Entrada</label>
                        <input
                            id="cod_entrada"
                            name="cod_entrada"
                            type="text"
                            inputmode="numeric"
                            class="w-full bg-gray-200 border border-gray-300 rounded px-4 py-3 text-gray-800 text-sm
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-senai-red focus:border-transparent
                                   transition"
                        />
                    </div>

                    <input type="hidden" name="remember" value="0">
                    <button
                        type="submit"
                        class="mt-2 w-full bg-senai-red hover:bg-red-700 text-white font-bold text-base py-3 rounded-full
                               transition duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-senai-red focus:ring-offset-2"
                    >
                        Entrar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const cpfInput = document.getElementById('email');
        if (cpfInput) {
            cpfInput.addEventListener('input', function () {
                let v = this.value.replace(/\D/g, '').slice(0, 11);
                if (v.length > 9)      v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
                else if (v.length > 6) v = v.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
                else if (v.length > 3) v = v.replace(/(\d{3})(\d{1,3})/, '$1.$2');
                this.value = v;
            });
        }
    </script>
</body>
</html>