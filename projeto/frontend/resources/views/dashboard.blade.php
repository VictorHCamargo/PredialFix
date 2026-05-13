<x-layouts.base-layout tittle="Dashboard">
    <x-navbar />

    <main class="mx-auto w-full max-w-5xl flex-1 px-6 py-8">
        <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4">
            @php
                $stats = [
                    ['label' => 'Chamados Feitos', 'value' => $totalChamados ?? 0],
                    ['label' => 'Chamados em Andamento', 'value' => $emAndamento ?? 0],
                    ['label' => 'Chamados Concluídos', 'value' => $concluidos ?? 0],
                    ['label' => 'Chamados Cancelados', 'value' => $cancelados ?? 0],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="flex items-center gap-3 rounded bg-white px-4 py-4 shadow">
                    <div
                        class="bg-senai-red flex h-10 w-10 flex-shrink-0 items-center justify-center rounded"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs leading-tight text-gray-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold leading-tight text-gray-800">{{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-8 rounded bg-white px-6 py-5 shadow">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Chamados Recentes</h2>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border border-gray-200 bg-gray-50">
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Tipo
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Descrição
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Local
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Data de Abertura
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Status
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Data de Término
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chamadosRecentes ?? [] as $chamado)
                            <tr class="border border-gray-200 transition hover:bg-gray-50">
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    {{
                                        $chamado->tipoProblema->nome ??
                                            '—'
                                    }}
                                </td>
                                <td
                                    class="max-w-[160px] truncate border border-gray-200 px-3 py-2 text-gray-700"
                                    title="{{ $chamado->descricao }}"
                                >
                                    {{
                                        Str::limit(
                                            $chamado->descricao,
                                            20,
                                        )
                                    }}
                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    {{ $chamado->local->nome ?? '—' }}
                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    {{
                                        $chamado->data_abertura
                                            ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y')
                                            : '—'
                                    }}
                                </td>
                                <td class="border border-gray-200 px-3 py-2">
                                    @php
                                        $statusMap = [
                                            'aberto' => ['label' => 'Aberto', 'class' => 'text-blue-600'],
                                            'em_andamento' => ['label' => 'Em Andamento', 'class' => 'text-yellow-600'],
                                            'concluido' => ['label' => 'Concluído', 'class' => 'text-green-600'],
                                            'cancelado' => ['label' => 'Cancelado', 'class' => 'text-red-600'],
                                        ];
                                        $s = $statusMap[$chamado->status] ?? ['label' => $chamado->status, 'class' => 'text-gray-600'];
                                    @endphp
                                    <span
                                        class="{{ $s['class'] }} font-medium"
                                        >{{ $s['label'] }}</span
                                    >
                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    {{
                                        $chamado->data_conclusao
                                            ? \Carbon\Carbon::parse($chamado->data_conclusao)->format('d/m/Y')
                                            : '—'
                                    }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="border border-gray-200 px-3 py-6 text-center text-gray-400"
                                >
                                    Nenhum chamado encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-center">
            <a
                href="{{ route('chamados.create') }}"
                class="bg-senai-red rounded-full px-10 py-4 text-base font-bold text-white shadow-lg transition duration-200 hover:bg-red-700 active:scale-95"
            >
                Relatar novo Problema
            </a>
        </div>
    </main>

    <x-footer />
</x-layouts.base-layout>
