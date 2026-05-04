<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – PredialFix SENAI</title>
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
</head>
<body class="min-h-screen flex flex-col bg-gray-100 font-sans">

    <!-- Barra de topo -->
    <div class="w-full bg-senai-dark text-white text-xs px-4 py-1 select-none">Home</div>

    <!-- ── Navbar ── -->
    <nav class="bg-senai-red flex items-center justify-between px-4 py-0 shadow-md">

        <!-- Logo -->
        <div class="flex items-center gap-1 py-2">
            <div class="bg-white text-senai-red font-black text-2xl px-3 py-1 tracking-tight select-none leading-none">
                SENAI
            </div>
        </div>

        <!-- Links de navegação -->
        <div class="flex items-center h-full">
            <a href="{{ route('dashboard') }}"
               class="text-white text-sm font-medium px-5 py-4 border-r border-red-400 hover:bg-red-700 transition
                      {{ request()->routeIs('dashboard') ? 'bg-red-700' : '' }}">
                Home
            </a>
            <a href="{{ route('chamados.create') }}"
               class="text-white text-sm font-medium px-5 py-4 border-r border-red-400 hover:bg-red-700 transition">
                Novo Chamado
            </a>
            <a href="{{ route('chamados.index') }}"
               class="text-white text-sm font-medium px-5 py-4 border-r border-red-400 hover:bg-red-700 transition">
                Gerenciar Chamados
            </a>
        </div>

        <!-- Sair -->
        <form method="POST" action="{{ route('logout') }}" class="flex items-center">
            @csrf
            <button type="submit"
                    class="text-white text-sm font-medium px-5 py-4 flex items-center gap-2 hover:bg-red-700 transition">
                Sair
                <!-- Ícone de porta/saída -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
            </button>
        </form>
    </nav>

    <!-- ── Conteúdo principal ── -->
    <main class="flex-1 px-6 py-8 max-w-5xl mx-auto w-full">

        <!-- Cards de estatísticas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            @php
                $stats = [
                    ['label' => 'Chamados Feitos',        'value' => $totalChamados      ?? 0],
                    ['label' => 'Chamados em Andamento',  'value' => $emAndamento        ?? 0],
                    ['label' => 'Chamados Concluídos',    'value' => $concluidos         ?? 0],
                    ['label' => 'Chamados Cancelados',    'value' => $cancelados         ?? 0],
                ];
            @endphp

            @foreach ($stats as $stat)
            <div class="bg-white rounded shadow flex items-center gap-3 px-4 py-4">
                <!-- Ícone vermelho -->
                <div class="bg-senai-red rounded w-10 h-10 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs leading-tight">{{ $stat['label'] }}</p>
                    <p class="text-gray-800 text-2xl font-bold leading-tight">{{ $stat['value'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded shadow px-6 py-5 mb-8">
            <h2 class="text-gray-800 font-semibold text-lg mb-4">Chamados Recentes</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border border-gray-200">
                            <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-200">Tipo</th>
                            <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-200">Descrição</th>
                            <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-200">Local</th>
                            <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-200">Data de Abertura</th>
                            <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-200">Status</th>
                            <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-200">Data de Término</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chamadosRecentes ?? [] as $chamado)
                        <tr class="border border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-3 py-2 text-gray-700 border border-gray-200">
                                {{ $chamado->tipoProblema->nome ?? '—' }}
                            </td>
                            <td class="px-3 py-2 text-gray-700 border border-gray-200 max-w-[160px] truncate"
                                title="{{ $chamado->descricao }}">
                                {{ Str::limit($chamado->descricao, 20) }}
                            </td>
                            <td class="px-3 py-2 text-gray-700 border border-gray-200">
                                {{ $chamado->local->nome ?? '—' }}
                            </td>
                            <td class="px-3 py-2 text-gray-700 border border-gray-200">
                                {{ $chamado->data_abertura ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-3 py-2 border border-gray-200">
                                @php
                                    $statusMap = [
                                        'aberto'       => ['label' => 'Aberto',        'class' => 'text-blue-600'],
                                        'em_andamento' => ['label' => 'Em Andamento',  'class' => 'text-yellow-600'],
                                        'concluido'    => ['label' => 'Concluído',     'class' => 'text-green-600'],
                                        'cancelado'    => ['label' => 'Cancelado',     'class' => 'text-red-600'],
                                    ];
                                    $s = $statusMap[$chamado->status] ?? ['label' => $chamado->status, 'class' => 'text-gray-600'];
                                @endphp
                                <span class="{{ $s['class'] }} font-medium">{{ $s['label'] }}</span>
                            </td>
                            <td class="px-3 py-2 text-gray-700 border border-gray-200">
                                {{ $chamado->data_conclusao ? \Carbon\Carbon::parse($chamado->data_conclusao)->format('d/m/Y') : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-gray-400 border border-gray-200">
                                Nenhum chamado encontrado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botão Relatar novo Problema -->
        <div class="flex justify-center">
            <a href="{{ route('chamados.create') }}"
               class="bg-senai-red hover:bg-red-700 text-white font-bold text-base px-10 py-4 rounded-full
                      shadow-lg transition duration-200 active:scale-95">
                Relatar novo Problema
            </a>
        </div>
    </main>

    <!-- ── Rodapé ── -->
    <footer class="bg-senai-red mt-8">
        <div class="max-w-5xl mx-auto px-6 py-8 grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Coluna esquerda: Endereço -->
            <div class="text-white">
                <h3 class="font-bold text-sm uppercase tracking-wide mb-3">Edifício Sede FIESP</h3>
                <p class="text-red-100 text-sm leading-relaxed">
                    Av. Paulista, 1313, São Paulo/SP<br>
                    CEP 01311-923
                </p>
            </div>

            <!-- Coluna direita: Central de Relacionamento -->
            <div class="text-white">
                <h3 class="font-bold text-sm uppercase tracking-wide mb-3">Central de Relacionamento</h3>
                <p class="text-red-100 text-sm leading-relaxed">
                    (11) 3322-0050 (Telefone/WhatsApp)<br>
                    0800-055-1000 (Interior de SP,<br>
                    somente telefone fixo)
                </p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="bg-red-900 text-center text-red-200 text-xs py-3">
            Copyright 2026 &copy; Todos os direitos reservados.
        </div>
    </footer>

</body>
</html>