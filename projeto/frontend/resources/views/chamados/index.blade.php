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
            $abertos       = $chamados->where('status', 'aberto')->count();
            $emExecucao    = $chamados->where('status', 'em_andamento')->count();
            $concluidosHoje = $chamados
                ->where('status', 'concluido')
                ->filter(fn($c) => $c->data_conclusao &&
                    \Carbon\Carbon::parse($c->data_conclusao)->isToday())
                ->count();
            $totalFeitos   = $chamados->count();
        @endphp

        <!-- Cards de estatísticas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach ([
                ['Chamados Abertos',  $abertos],
                ['Em execução',       $emExecucao],
                ['Concluidos Hoje',   $concluidosHoje],
                ['Chamados Feitos',   $totalFeitos],
            ] as [$label, $valor])
            <div class="bg-white border border-gray-200 rounded shadow flex items-center gap-3 px-4 py-4">
                <div class="bg-senai-red rounded w-10 h-10 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs leading-tight">{{ $label }}</p>
                    <p class="text-gray-800 text-2xl font-bold leading-tight">{{ $valor }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabela de Chamados -->
        <div class="border border-gray-300 rounded overflow-hidden bg-white mb-8">

                <!-- Barra de filtro -->
                <div class="border-b border-gray-300 px-3 py-2 flex items-center gap-2 flex-wrap bg-gray-50">
                    <button id="btn-filtrar"
                            class="flex items-center gap-1 text-gray-700 text-xs font-medium border border-gray-300
                                   rounded px-3 py-1 hover:bg-gray-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filtrar
                    </button>

                    <div id="painel-filtros" class="hidden flex gap-2 flex-wrap ml-2">
                        <select id="filtro-status"
                                class="border border-gray-300 rounded px-2 py-1 text-xs text-gray-700
                                       focus:outline-none focus:ring-1 focus:ring-senai-red">
                            <option value="">Todos os status</option>
                            <option value="aberto">Aberto</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="concluido">Concluído</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                        <input id="filtro-busca" type="text" placeholder="Buscar descrição..."
                               class="border border-gray-300 rounded px-2 py-1 text-xs text-gray-700
                                      focus:outline-none focus:ring-1 focus:ring-senai-red w-40">
                        <button onclick="aplicarFiltros()"
                                class="bg-senai-red text-white text-xs rounded px-3 py-1 hover:bg-red-700 transition">Aplicar</button>
                        <button onclick="limparFiltros()"
                                class="text-gray-500 text-xs hover:underline px-1">Limpar</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-gray-300">
                                <th class="px-3 py-2 text-gray-700 font-semibold border-r border-gray-300 text-xs">Tipo</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border-r border-gray-300 text-xs">Descrição</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border-r border-gray-300 text-xs">Local</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border-r border-gray-300 text-xs">Abertura</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border-r border-gray-300 text-xs">Status</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold text-xs">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($chamados as $chamado)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition chamado-row"
                                data-status="{{ $chamado->status }}"
                                data-descricao="{{ strtolower($chamado->descricao) }}">

                                <td class="px-3 py-2 text-gray-700 border-r border-gray-300 text-xs">
                                    {{ $chamado->tipoProblema->categoria ?? '—' }}
                                </td>

                                <td class="px-3 py-2 text-gray-700 border-r border-gray-300 text-xs max-w-[140px] truncate"
                                    title="{{ $chamado->descricao }}">
                                    {{ Str::limit($chamado->descricao, 18) }}
                                </td>

                                <td class="px-3 py-2 text-gray-700 border-r border-gray-300 text-xs">
                                    {{ $chamado->local->sala_setor ?? '—' }} - Bloco {{ $chamado->local->bloco ?? '' }}
                                </td>

                                <td class="px-3 py-2 text-gray-700 border-r border-gray-300 text-xs">
                                    {{ $chamado->data_abertura
                                        ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y')
                                        : '—' }}
                                </td>

                                <td class="px-3 py-2 border-r border-gray-300 text-xs">
                                    @php
                                        $map = [
                                            'aberto'       => ['Aberto',       'text-blue-600'],
                                            'em_andamento' => ['Em Andamento', 'text-yellow-600'],
                                            'concluido'    => ['Concluído',    'text-green-600'],
                                            'cancelado'    => ['Cancelado',    'text-red-600'],
                                        ];
                                        [$lbl, $cls] = $map[$chamado->status] ?? [$chamado->status, 'text-gray-600'];
                                    @endphp
                                    <span class="{{ $cls }} font-medium">{{ $lbl }}</span>
                                </td>

                                <td class="px-3 py-2 text-xs space-y-1">
                                    <div class="flex gap-2 flex-wrap">
                                        @if ($chamado->status === 'concluido' && !$chamado->feedback)
                                            <a href="{{ route('avaliar.create', $chamado->id_chamado) }}"
                                               class="bg-senai-red hover:bg-red-700 text-white px-2 py-1 rounded text-xs font-semibold transition">
                                                Avaliar
                                            </a>
                                        @endif

                                        <button onclick="abrirModalStatus({{ $chamado->id_chamado }}, '{{ $chamado->status }}')"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-semibold transition">
                                            Status
                                        </button>

                                        <form method="POST" action="{{ route('chamados.destroy', $chamado->id_chamado) }}" style="display:inline;"
                                              onsubmit="return confirm('Tem certeza que quer deletar este chamado?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs font-semibold transition">
                                                Deletar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-3 py-6 text-center text-gray-400 border-b border-gray-200 text-sm">
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

    <script>
        document.getElementById('btn-filtrar').addEventListener('click', () => {
            const p = document.getElementById('painel-filtros');
            p.classList.toggle('hidden');
            p.classList.toggle('flex');
        });

        function aplicarFiltros() {
            const status = document.getElementById('filtro-status').value;
            const busca  = document.getElementById('filtro-busca').value.toLowerCase();
            document.querySelectorAll('.chamado-row').forEach(row => {
                const okStatus = !status || row.dataset.status === status;
                const okBusca  = !busca  || row.dataset.descricao.includes(busca);
                row.style.display = (okStatus && okBusca) ? '' : 'none';
            });
        }

        function limparFiltros() {
            document.getElementById('filtro-status').value = '';
            document.getElementById('filtro-busca').value  = '';
            document.querySelectorAll('.chamado-row').forEach(r => r.style.display = '');
        }

        function abrirModalStatus(chamadoId, statusAtual) {
            const modal = document.getElementById('modalStatus');
            document.getElementById('modalChamadoId').value = chamadoId;
            document.getElementById('modalStatusAtual').textContent = statusAtual;
            document.getElementById('novoStatus').value = statusAtual;
            modal.classList.remove('hidden');
        }

        function fecharModalStatus() {
            document.getElementById('modalStatus').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modalStatus');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>

    <!-- Modal para alterar status -->
    <div id="modalStatus" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded shadow-lg max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-300">
                <h2 class="text-lg font-semibold text-gray-800">Alterar Status do Chamado</h2>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-gray-600 mb-4">
                    Status atual: <span id="modalStatusAtual" class="font-semibold text-gray-800"></span>
                </p>
                <form id="formStatus" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="modalChamadoId">
                    <select id="novoStatus" name="status" required
                            class="w-full border border-gray-400 rounded px-4 py-2 text-sm text-gray-700
                                   focus:outline-none focus:ring-2 focus:ring-senai-red">
                        <option value="">-- Selecione um status --</option>
                        <option value="aberto">Aberto</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="fecharModalStatus()"
                                class="text-gray-600 hover:text-gray-800 font-semibold px-4 py-2 border border-gray-300 rounded transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-senai-red hover:bg-red-700 text-white font-semibold px-4 py-2 rounded transition">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formStatus').addEventListener('submit', function(e) {
            e.preventDefault();
            const chamadoId = document.getElementById('modalChamadoId').value;
            const form = this;
            form.action = `/chamados/${chamadoId}/status`;
            form.submit();
        });
    </script>
</body>
</html>