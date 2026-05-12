<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Chamados – PredialFix SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { senai: { red: '#E3000F', dark: '#1a1a1a' } },
                    fontFamily: { sans: ['Segoe UI', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50 font-sans">

    <x-navbar />

    <main class="flex-1 px-6 py-8 max-w-6xl mx-auto w-full">

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                {{ session('info') }}
            </div>
        @endif

        @php
            $total = $chamados->total();
            $perPage = $chamados->perPage();
        @endphp

        <!-- Cards de estatísticas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-gray-200 rounded shadow flex items-center gap-3 px-4 py-4">
                <div class="bg-senai-red rounded w-10 h-10 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs leading-tight">Total de Chamados</p>
                    <p class="text-gray-800 text-2xl font-bold leading-tight">{{ $total }}</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded shadow flex items-center gap-3 px-4 py-4">
                <div class="bg-yellow-500 rounded w-10 h-10 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs leading-tight">Em Andamento</p>
                    <p class="text-gray-800 text-2xl font-bold leading-tight">0</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded shadow flex items-center gap-3 px-4 py-4">
                <div class="bg-green-500 rounded w-10 h-10 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs leading-tight">Concluídos</p>
                    <p class="text-gray-800 text-2xl font-bold leading-tight">0</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded shadow flex items-center gap-3 px-4 py-4">
                <div class="bg-red-500 rounded w-10 h-10 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs leading-tight">Página</p>
                    <p class="text-gray-800 text-2xl font-bold leading-tight">{{ $chamados->currentPage() }} de {{ $chamados->lastPage() }}</p>
                </div>
            </div>
        </div>

        <!-- Tabela de Chamados -->
        <div class="border border-gray-300 rounded overflow-hidden bg-white mb-8">

            <!-- Barra de filtro -->
            <div class="border-b border-gray-300 px-4 py-3 bg-gray-50">
                <form method="GET" action="{{ route('chamados.index') }}" class="flex gap-3 flex-wrap items-end">
                    <div class="flex gap-3 flex-wrap items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="">Todos os status</option>
                                <option value="aberto" @selected(request('status') === 'aberto')>Aberto</option>
                                <option value="em_andamento" @selected(request('status') === 'em_andamento')>Em Andamento</option>
                                <option value="concluido" @selected(request('status') === 'concluido')>Concluído</option>
                                <option value="cancelado" @selected(request('status') === 'cancelado')>Cancelado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tipo</label>
                            <select name="tipo_chamado" class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="">Todos os tipos</option>
                                <option value="interno" @selected(request('tipo_chamado') === 'interno')>Interno</option>
                                <option value="externo" @selected(request('tipo_chamado') === 'externo')>Externo</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Prioridade</label>
                            <select name="prioridade" class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="">Todas as prioridades</option>
                                <option value="alta" @selected(request('prioridade') === 'alta')>Alta</option>
                                <option value="media" @selected(request('prioridade') === 'media')>Média</option>
                                <option value="baixa" @selected(request('prioridade') === 'baixa')>Baixa</option>
                            </select>
                        </div>

                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                            Filtrar
                        </button>

                        <a href="{{ route('chamados.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-300">
                            <th class="px-4 py-3 text-gray-700 font-semibold border-r border-gray-300 text-xs">Tipo</th>
                            <th class="px-4 py-3 text-gray-700 font-semibold border-r border-gray-300 text-xs">Descrição</th>
                            <th class="px-4 py-3 text-gray-700 font-semibold border-r border-gray-300 text-xs">Local</th>
                            <th class="px-4 py-3 text-gray-700 font-semibold border-r border-gray-300 text-xs">Abertura</th>
                            <th class="px-4 py-3 text-gray-700 font-semibold border-r border-gray-300 text-xs">Prioridade</th>
                            <th class="px-4 py-3 text-gray-700 font-semibold border-r border-gray-300 text-xs">Status</th>
                            <th class="px-4 py-3 text-gray-700 font-semibold text-xs">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chamados as $chamado)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-700 border-r border-gray-300 text-xs">
                                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $chamado->tipo_chamado)) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-gray-700 border-r border-gray-300 text-xs max-w-[180px] truncate" title="{{ $chamado->descricao }}">
                                {{ Str::limit($chamado->descricao, 25) }}
                            </td>

                            <td class="px-4 py-3 text-gray-700 border-r border-gray-300 text-xs">
                                {{ $chamado->local->sala_setor ?? '—' }} - {{ $chamado->local->bloco ?? '' }}
                            </td>

                            <td class="px-4 py-3 text-gray-700 border-r border-gray-300 text-xs">
                                {{ $chamado->data_abertura ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y') : '—' }}
                            </td>

                            <td class="px-4 py-3 border-r border-gray-300 text-xs">
                                @if ($chamado->prioridade)
                                    @php
                                        $priorityColors = [
                                            'alta' => 'bg-red-100 text-red-700',
                                            'media' => 'bg-yellow-100 text-yellow-700',
                                            'baixa' => 'bg-green-100 text-green-700'
                                        ];
                                    @endphp
                                    <span class="inline-block {{ $priorityColors[$chamado->prioridade] ?? '' }} px-2 py-1 rounded font-semibold">
                                        {{ ucfirst($chamado->prioridade) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 border-r border-gray-300 text-xs">
                                @php
                                    $statusColors = [
                                        'aberto' => 'bg-blue-100 text-blue-700',
                                        'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                        'concluido' => 'bg-green-100 text-green-700',
                                        'cancelado' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-block {{ $statusColors[$chamado->status] ?? '' }} px-2 py-1 rounded font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $chamado->status)) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-xs space-y-1">
                                <div class="flex gap-1 flex-wrap">
                                    <a href="{{ route('chamados.show', $chamado->id_chamado) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-semibold transition">
                                        Ver
                                    </a>

                                    @if ($chamado->status === 'concluido' && !$chamado->feedback && auth()->user()->temCodigoEntrada())
                                        <a href="{{ route('avaliar.create', $chamado->id_chamado) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded text-xs font-semibold transition">
                                            Avaliar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-400 border-b border-gray-200 text-sm">
                                Nenhum chamado encontrado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if ($chamados->hasPages())
            <div class="border-t border-gray-300 px-4 py-3 bg-gray-50 flex justify-between items-center text-sm">
                <div class="text-gray-600">
                    Mostrando {{ $chamados->firstItem() ?? 0 }} a {{ $chamados->lastItem() ?? 0 }} de {{ $chamados->total() }} chamados
                </div>
                <div class="flex gap-1">
                    @if ($chamados->onFirstPage())
                        <span class="px-3 py-1 border border-gray-300 text-gray-400 rounded cursor-not-allowed">← Anterior</span>
                    @else
                        <a href="{{ $chamados->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded transition">← Anterior</a>
                    @endif

                    @foreach ($chamados->getUrlRange(1, $chamados->lastPage()) as $page => $url)
                        @if ($page == $chamados->currentPage())
                            <span class="px-3 py-1 bg-red-600 text-white rounded font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($chamados->hasMorePages())
                        <a href="{{ $chamados->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded transition">Próximo →</a>
                    @else
                        <span class="px-3 py-1 border border-gray-300 text-gray-400 rounded cursor-not-allowed">Próximo →</span>
                    @endif
                </div>
            </div>
            @endif

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

    <!-- Rodapé -->
    <footer class="bg-senai-red mt-8">
        <div class="max-w-5xl mx-auto px-6 py-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="text-white">
                <h3 class="font-bold text-sm uppercase tracking-wide mb-3">Edifício Sede FIESP</h3>
                <p class="text-red-100 text-sm leading-relaxed">Av. Paulista, 1313, São Paulo/SP<br>CEP 01311-923</p>
            </div>
            <div class="text-white">
                <h3 class="font-bold text-sm uppercase tracking-wide mb-3">Central de Relacionamento</h3>
                <p class="text-red-100 text-sm leading-relaxed">
                    (11) 3322-0050 (Telefone/WhatsApp)<br>
                    0800-055-1000 (Interior de SP,<br>somente telefone fixo)
                </p>
            </div>
        </div>
        <div class="bg-red-900 text-center text-red-200 text-xs py-3">Copyright 2026 &copy; Todos os direitos reservados.</div>
    </footer>

    </script>
</body>
</html>