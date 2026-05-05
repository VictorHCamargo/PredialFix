<x-layouts.base-layout tittle="Dashboard">
    <x-navbar />

    <main class="flex-1 px-6 py-8 max-w-5xl mx-auto w-full">

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

        <div class="flex justify-center">
            <a href="{{ route('chamados.create') }}"
               class="bg-senai-red hover:bg-red-700 text-white font-bold text-base px-10 py-4 rounded-full
                      shadow-lg transition duration-200 active:scale-95">
                Relatar novo Problema
            </a>
        </div>
    </main>

    <x-footer />

</x-layouts.base-layout>