<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chamado #{{ $chamado->id_chamado }} – PredialFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { senai: { red: '#E3000F', dark: '#1a1a1a' } },
                    fontFamily: { sans: ['Segoe UI', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen flex-col bg-gray-50 font-sans">
    <x-navbar />

    <main class="mx-auto w-full max-w-6xl flex-1 px-6 py-8">
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <nav class="text-sm text-gray-600">
                <a href="{{ route('chamados.index') }}" class="hover:text-gray-800">Chamados</a>
                <span class="mx-2">/</span>
                <span class="font-semibold text-gray-800">Chamado #{{ $chamado->id_chamado }}</span>
            </nav>
        </div>

        {{-- Alertas --}}
        @if (session('success'))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Conteúdo Principal --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Card Informações Básicas --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <h1 class="mb-2 text-2xl font-bold text-gray-800">
                                Chamado #{{ $chamado->id_chamado }}
                            </h1>
                            <p class="text-gray-600">{{ $chamado->descricao }}</p>
                        </div>
                        @php
                            $statusColors = [
                                'aberto' => 'bg-blue-100 text-blue-700',
                                'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                'concluido' => 'bg-green-100 text-green-700',
                                'cancelado' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span
                            class="inline-block {{ $statusColors[$chamado->status] ?? '' }} px-4 py-2 rounded-lg font-semibold text-lg"
                        >
                            {{
                                ucfirst(
                                    str_replace('_', ' ', $chamado->status),
                                )
                            }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Tipo</p>
                            <p class="text-sm font-semibold text-gray-800">{{
                                ucfirst(
                                    str_replace('_', ' ', $chamado->tipo_chamado),
                                )
                            }}</p>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Aberto em</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{
                                    $chamado->data_abertura->format(
                                        'd/m/Y H:i',
                                    )
                                }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Local</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $chamado->local->sala_setor ?? '—' }}
                                @if ($chamado->local->bloco)
                                    - Bloco {{ $chamado->local->bloco }}
                                @endif
                            </p>
                        </div>

                        @if ($chamado->prioridade)
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Prioridade</p>
                                @php
                                    $priorityColors = [
                                        'alta' => 'text-red-700',
                                        'media' => 'text-yellow-700',
                                        'baixa' => 'text-green-700',
                                    ];
                                @endphp
                                <p
                                    class="text-sm font-semibold {{ $priorityColors[$chamado->prioridade] ?? '' }}"
                                >
                                    {{ ucfirst($chamado->prioridade) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card Detalhes Adicionais --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Informações Adicionais</h2>

                    <div class="space-y-4">
                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Criado por</p>
                            <p class="text-sm text-gray-800">{{
                                $chamado->usuario->nome ??
                                    'Desconhecido'
                            }}</p>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Tipo de Problema</p>
                            <p class="text-sm text-gray-800">{{
                                $chamado->tipoProblema->categoria ??
                                    '—'
                            }}</p>
                        </div>

                        @if ($chamado->equipamento)
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Equipamento</p>
                                <p class="text-sm text-gray-800">{{ $chamado->equipamento->nome ?? '—' }}</p>
                            </div>
                        @endif

                        @if ($chamado->status_descricao)
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Descrição do Status</p>
                                <p class="rounded bg-gray-100 p-3 text-sm text-gray-800">{{ $chamado->status_descricao }}</p>
                            </div>
                        @endif

                        @if ($chamado->data_conclusao)
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Concluído em</p>
                                <p class="text-sm text-gray-800">{{
                                    $chamado->data_conclusao->format(
                                        'd/m/Y H:i',
                                    )
                                }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Histórico de Status --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Histórico de Status</h2>

                    @if ($chamado->historicoStatus->count() > 0)
                        <div class="space-y-3">
                            @foreach ($chamado->historicoStatus->reverse() as $historico)
                                <div class="border-l-4 border-gray-300 py-2 pl-4">
                                    <div class="mb-1 flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{
                                                    ucfirst(
                                                        str_replace('_', ' ', $historico->status_anterior),
                                                    )
                                                }}
                                                <span class="text-gray-500">→</span>
                                                {{
                                                    ucfirst(
                                                        str_replace('_', ' ', $historico->status_novo),
                                                    )
                                                }}
                                            </p>
                                            <p class="text-xs text-gray-600">Por: {{ $historico->usuario->nome ?? 'Desconhecido' }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500">{{
                                            $historico->created_at->format(
                                                'd/m/Y H:i',
                                            )
                                        }}</p>
                                    </div>

                                    @if ($historico->descricao_mudanca)
                                        <p class="mt-2 rounded bg-gray-50 p-2 text-sm text-gray-700">
                                            {{ $historico->descricao_mudanca }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-gray-500">Nenhuma mudança de status registrada ainda.</p>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Card Ações --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 font-semibold text-gray-800">Ações</h3>

                    <div class="space-y-2">
                        {{-- Botão Alterar Status --}}
                        @if (auth()->check())
                            <button
                                onclick="openStatusModal()"
                                class="w-full rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                            >
                                Alterar Status
                            </button>
                        @endif

                        {{-- Botão Avaliar --}}
                        @if ($chamado->status === 'concluido' && !$chamado->feedback && auth()->user()->temCodigoEntrada())
                            <a
                                href="{{ route('avaliar.create', $chamado->id_chamado) }}"
                                class="block w-full rounded bg-purple-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-purple-700"
                            >
                                Avaliar Chamado
                            </a>
                        @endif

                        {{-- Botão Editar --}}
                        @if (auth()->user()->id_usuario === $chamado->id_usuario && $chamado->status === 'aberto')
                            <a
                                href="{{ route('chamados.edit', $chamado->id_chamado) }}"
                                class="block w-full rounded bg-gray-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-gray-700"
                            >
                                Editar Chamado
                            </a>
                        @endif

                        {{-- Botão Deletar --}}
                        @if (auth()->user()->id_usuario === $chamado->id_usuario || auth()->user()->isAdmin())
                            <button
                                onclick="openDeleteModal()"
                                class="w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                            >
                                Deletar Chamado
                            </button>
                        @endif

                        <a
                            href="{{ route('chamados.index') }}"
                            class="block w-full rounded bg-gray-200 px-4 py-2 text-center text-sm font-medium text-gray-800 transition hover:bg-gray-300"
                        >
                            Voltar
                        </a>
                    </div>
                </div>

                {{-- Card Feedback --}}
                @if ($chamado->feedback)
                    <div class="rounded-lg bg-white p-6 shadow">
                        <h3 class="mb-4 font-semibold text-gray-800">Feedback do Cliente</h3>

                        <div class="mb-3">
                            <p class="mb-1 text-sm text-gray-600">Avaliação:</p>
                            <div class="flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span
                                        class="text-lg @if($i <= $chamado->feedback->avaliacao) text-yellow-400 @else text-gray-300 @endif"
                                        >★</span
                                    >
                                @endfor
                            </div>
                        </div>

                        <div>
                            <p class="mb-1 text-sm text-gray-600">Comentário:</p>
                            <p class="text-sm text-gray-800">{{ $chamado->feedback->comentario }}</p>
                        </div>

                        <p class="mt-3 text-xs text-gray-500">
                            {{
                                $chamado->feedback->created_at->format(
                                    'd/m/Y H:i',
                                )
                            }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Modal Alterar Status --}}
    <div
        id="statusModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50 p-4"
    >
        <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-lg bg-white shadow-lg">
            <div class="sticky top-0 border-b border-gray-300 bg-white px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800">Alterar Status do Chamado</h3>
                <p class="text-sm text-gray-600">Status atual: <strong>{{
                    ucfirst(
                        str_replace('_', ' ', $chamado->status),
                    )
                }}</strong></p>
            </div>

            <form
                method="POST"
                action="{{ route('chamados.updateStatus', $chamado->id_chamado) }}"
                class="space-y-4 p-6"
            >
                @csrf
                @method ('PATCH')

                {{-- Seletor de Status --}}
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700"
                        >Novo Status</label
                    >
                    <select
                        id="status"
                        name="status"
                        required
                        onchange="atualizarCampos()"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        <option value="">Selecione um status</option>
                        <option value="aberto">Aberto</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    @error ('status')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Prioridade (aparece ao mudar para em_andamento) --}}
                <div id="prioridadeContainer" class="hidden">
                    <label for="prioridade" class="mb-2 block text-sm font-medium text-gray-700"
                        >Prioridade</label
                    >
                    <select
                        id="prioridade"
                        name="prioridade"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        <option value="">Sem prioridade</option>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>

                {{-- Campo Descrição --}}
                <div id="descricaoContainer" class="hidden">
                    <label
                        for="status_descricao"
                        class="mb-2 block text-sm font-medium text-gray-700"
                        >Descrição</label
                    >
                    <textarea
                        id="status_descricao"
                        name="status_descricao"
                        rows="4"
                        placeholder="Descreva a mudança de status..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    ></textarea>
                    <p id="descricaoRequerida" class="mt-1 hidden text-xs text-red-500">Este campo é obrigatório para este status.</p>
                    @error ('status_descricao')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 border-t border-gray-200 pt-4">
                    <button
                        type="button"
                        onclick="closeStatusModal()"
                        class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                    >
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Deletar --}}
    <div
        id="deleteModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50 p-4"
    >
        <div class="w-full max-w-sm rounded-lg bg-white shadow-lg">
            <div class="border-b border-gray-300 px-6 py-4">
                <h3 class="text-lg font-semibold text-red-600">Deletar Chamado?</h3>
            </div>

            <div class="px-6 py-4">
                <p class="mb-4 text-gray-600">Você está prestes a deletar o chamado <strong>#{{ $chamado->id_chamado }}</strong>. Esta ação é <strong>irreversível</strong>.</p>

                <form
                    method="POST"
                    action="{{ route('chamados.destroy', $chamado->id_chamado) }}"
                    class="space-y-4"
                >
                    @csrf
                    @method ('DELETE')

                    <div class="flex gap-3">
                        <button
                            type="button"
                            onclick="closeDeleteModal()"
                            class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="flex-1 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                        >
                            Deletar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function openStatusModal() {
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function atualizarCampos() {
            const status = document.getElementById('status').value;
            const prioridadeContainer = document.getElementById('prioridadeContainer');
            const descricaoContainer = document.getElementById('descricaoContainer');
            const descricaoRequerida = document.getElementById('descricaoRequerida');

            // Resetar visibilidade
            prioridadeContainer.classList.add('hidden');
            descricaoContainer.classList.add('hidden');
            descricaoRequerida.classList.add('hidden');

            // Mostrar campos conforme status
            if (status === 'em_andamento') {
                prioridadeContainer.classList.remove('hidden');
            }

            if (status === 'concluido' || status === 'cancelado') {
                descricaoContainer.classList.remove('hidden');
                descricaoRequerida.classList.remove('hidden');
            }
        }

        // Fechar modals ao clicar fora
        document.getElementById('statusModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'statusModal') closeStatusModal();
        });

        document.getElementById('deleteModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') closeDeleteModal();
        });
    </script>
</body>
</html>
