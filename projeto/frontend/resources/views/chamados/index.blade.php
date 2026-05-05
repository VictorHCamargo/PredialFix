<x-layouts.base-layout tittle="Gerenciar">
    <x-navbar />

    <!-- Conteúdo -->
    <main class="flex-1 px-6 py-8 max-w-5xl mx-auto w-full">

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

        <!-- Tabela + botões Avaliar -->
        <div class="flex gap-4 items-start mb-8">

            <!-- Tabela -->
            <div class="flex-1 border border-gray-300 rounded overflow-hidden">

                <!-- Barra de filtro -->
                <div class="border-b border-gray-300 px-3 py-2 flex items-center gap-2 flex-wrap">
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
                        {{-- Filtro de status usa os valores do controller: aberto|em_andamento|concluido|cancelado --}}
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
                            <tr class="bg-white">
                                <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-300 text-xs">Tipo</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-300 text-xs">Descrição</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-300 text-xs">Local</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-300 text-xs">Data de Abertura</th>
                                <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-300 text-xs">Status</th>
                                {{-- "Detalhes" usa descricao — model Chamado não tem coluna 'detalhes' --}}
                                <th class="px-3 py-2 text-gray-700 font-semibold border border-gray-300 text-xs">Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($chamados as $chamado)
                            <tr class="border border-gray-200 hover:bg-gray-50 transition chamado-row"
                                data-status="{{ $chamado->status }}"
                                data-descricao="{{ strtolower($chamado->descricao) }}">

                                <td class="px-3 py-2 text-gray-700 border border-gray-200 text-xs">
                                    {{ $chamado->tipoProblema->nome ?? '—' }}
                                </td>

                                <td class="px-3 py-2 text-gray-700 border border-gray-200 text-xs max-w-[130px] truncate"
                                    title="{{ $chamado->descricao }}">
                                    {{ Str::limit($chamado->descricao, 18) }}
                                </td>

                                <td class="px-3 py-2 text-gray-700 border border-gray-200 text-xs">
                                    {{ $chamado->local->nome ?? '—' }}
                                </td>

                                <td class="px-3 py-2 text-gray-700 border border-gray-200 text-xs">
                                    {{ $chamado->data_abertura
                                        ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y')
                                        : '—' }}
                                </td>

                                <td class="px-3 py-2 border border-gray-200 text-xs">
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

                                {{--
                                    "Detalhes" na imagem mostra mensagens como "Técnico a caminho",
                                    "Aguardando Equipamentos" — isso viria de feedback ou campo extra.
                                    Usando descricao resumida como fallback seguro enquanto não há coluna dedicada.
                                --}}
                                <td class="px-3 py-2 text-gray-600 border border-gray-200 text-xs max-w-[160px] truncate"
                                    title="{{ $chamado->descricao }}">
                                    {{ Str::limit($chamado->descricao, 28) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-3 py-6 text-center text-gray-400 border border-gray-200 text-sm">
                                    Nenhum chamado encontrado.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Coluna de botões Avaliar — alinhada linha a linha com a tabela -->
            <div class="flex flex-col gap-0 pt-[38px] min-w-[100px]">
                @forelse ($chamados as $chamado)
                    {{--
                        Chamado concluído → botão ativo (vermelho)
                        Demais status    → botão desabilitado (cinza com X)
                        Link aponta para chamados.show que já existe no controller
                    --}}
                    @if ($chamado->status === 'concluido')
                        <a href="{{ route('chamados.show', $chamado->id_chamado) }}"
                           class="flex items-center justify-center bg-senai-red text-white text-xs font-bold
                                  rounded px-4 py-[9px] mb-[1px] hover:bg-red-700 transition shadow">
                            Avaliar
                        </a>
                    @else
                        <span class="flex items-center justify-center gap-1 text-gray-400 text-xs font-semibold
                                     border border-gray-300 rounded px-4 py-[9px] mb-[1px] cursor-not-allowed select-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Avaliar
                        </span>
                    @endif
                @empty
                @endforelse
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

   <x-footer />

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
    </script>
</x-layouts.base-layout>