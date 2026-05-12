<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamado #{{ $chamado->id_chamado }} – PredialFix</title>
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

        {{-- Breadcrumb --}}
        <div class="mb-6">
            <nav class="text-sm text-gray-600">
                <a href="{{ route('chamados.index') }}" class="hover:text-gray-800">Chamados</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-semibold">Chamado #{{ $chamado->id_chamado }}</span>
            </nav>
        </div>

        {{-- Alertas --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Conteúdo Principal --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Card Informações Básicas --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2">Chamado #{{ $chamado->id_chamado }}</h1>
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
                        <span class="inline-block {{ $statusColors[$chamado->status] ?? '' }} px-4 py-2 rounded-lg font-semibold text-lg">
                            {{ ucfirst(str_replace('_', ' ', $chamado->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Tipo</p>
                            <p class="text-sm font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $chamado->tipo_chamado)) }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Aberto em</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $chamado->data_abertura->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Local</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $chamado->local->sala_setor ?? '—' }}
                                @if ($chamado->local->bloco)
                                    - Bloco {{ $chamado->local->bloco }}
                                @endif
                            </p>
                        </div>

                        @if ($chamado->prioridade)
                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Prioridade</p>
                            @php
                                $priorityColors = [
                                    'alta' => 'text-red-700',
                                    'media' => 'text-yellow-700',
                                    'baixa' => 'text-green-700'
                                ];
                            @endphp
                            <p class="text-sm font-semibold {{ $priorityColors[$chamado->prioridade] ?? '' }}">
                                {{ ucfirst($chamado->prioridade) }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Card Detalhes Adicionais --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Informações Adicionais</h2>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Criado por</p>
                            <p class="text-sm text-gray-800">{{ $chamado->usuario->nome ?? 'Desconhecido' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Tipo de Problema</p>
                            <p class="text-sm text-gray-800">{{ $chamado->tipoProblema->categoria ?? '—' }}</p>
                        </div>

                        @if ($chamado->equipamento)
                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Equipamento</p>
                            <p class="text-sm text-gray-800">{{ $chamado->equipamento->nome ?? '—' }}</p>
                        </div>
                        @endif

                        @if ($chamado->status_descricao)
                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Descrição do Status</p>
                            <p class="text-sm text-gray-800 bg-gray-100 p-3 rounded">{{ $chamado->status_descricao }}</p>
                        </div>
                        @endif

                        @if ($chamado->data_conclusao)
                        <div>
                            <p class="text-xs text-gray-600 font-medium mb-1">Concluído em</p>
                            <p class="text-sm text-gray-800">{{ $chamado->data_conclusao->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Histórico de Status --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Histórico de Status</h2>

                    @if ($chamado->historicoStatus->count() > 0)
                        <div class="space-y-3">
                            @foreach ($chamado->historicoStatus->reverse() as $historico)
                            <div class="border-l-4 border-gray-300 pl-4 py-2">
                                <div class="flex justify-between items-start mb-1">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ ucfirst(str_replace('_', ' ', $historico->status_anterior)) }}
                                            <span class="text-gray-500">→</span>
                                            {{ ucfirst(str_replace('_', ' ', $historico->status_novo)) }}
                                        </p>
                                        <p class="text-xs text-gray-600">Por: {{ $historico->usuario->nome ?? 'Desconhecido' }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $historico->created_at->format('d/m/Y H:i') }}</p>
                                </div>

                                @if ($historico->descricao_mudanca)
                                <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded mt-2">
                                    {{ $historico->descricao_mudanca }}
                                </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Nenhuma mudança de status registrada ainda.</p>
                    @endif
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Card Ações --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Ações</h3>

                    <div class="space-y-2">
                        {{-- Botão Alterar Status --}}
                        @if (auth()->check())
                            <button onclick="openStatusModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                Alterar Status
                            </button>
                        @endif

                        {{-- Botão Avaliar --}}
                        @if ($chamado->status === 'concluido' && !$chamado->feedback && auth()->user()->temCodigoEntrada())
                        <a href="{{ route('avaliar.create', $chamado->id_chamado) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium transition text-center">
                            Avaliar Chamado
                        </a>
                        @endif

                        {{-- Botão Editar --}}
                        @if (auth()->user()->id_usuario === $chamado->id_usuario && $chamado->status === 'aberto')
                        <a href="{{ route('chamados.edit', $chamado->id_chamado) }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm font-medium transition text-center">
                            Editar Chamado
                        </a>
                        @endif

                        {{-- Botão Deletar --}}
                        @if (auth()->user()->id_usuario === $chamado->id_usuario || auth()->user()->isAdmin())
                        <button onclick="openDeleteModal()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                            Deletar Chamado
                        </button>
                        @endif

                        <a href="{{ route('chamados.index') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm font-medium transition text-center">
                            Voltar
                        </a>
                    </div>
                </div>

                {{-- Card Feedback --}}
                @if ($chamado->feedback)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Feedback do Cliente</h3>

                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-1">Avaliação:</p>
                        <div class="flex gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="text-lg @if($i <= $chamado->feedback->avaliacao) text-yellow-400 @else text-gray-300 @endif">★</span>
                            @endfor
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Comentário:</p>
                        <p class="text-sm text-gray-800">{{ $chamado->feedback->comentario }}</p>
                    </div>

                    <p class="text-xs text-gray-500 mt-3">
                        {{ $chamado->feedback->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                @endif

            </div>

        </div>

    </main>

    {{-- Modal Alterar Status --}}
    <div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-300 sticky top-0 bg-white">
                <h3 class="text-lg font-semibold text-gray-800">Alterar Status do Chamado</h3>
                <p class="text-sm text-gray-600">Status atual: <strong>{{ ucfirst(str_replace('_', ' ', $chamado->status)) }}</strong></p>
            </div>

            <form method="POST" action="{{ route('chamados.updateStatus', $chamado->id_chamado) }}" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                {{-- Seletor de Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Novo Status</label>
                    <select id="status" name="status" required onchange="atualizarCampos()"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Selecione um status</option>
                        <option value="aberto">Aberto</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Prioridade (aparece ao mudar para em_andamento) --}}
                <div id="prioridadeContainer" class="hidden">
                    <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                    <select id="prioridade" name="prioridade"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Sem prioridade</option>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>

                {{-- Campo Descrição --}}
                <div id="descricaoContainer" class="hidden">
                    <label for="status_descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <textarea id="status_descricao" name="status_descricao" rows="4" placeholder="Descreva a mudança de status..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                    <p id="descricaoRequerida" class="text-red-500 text-xs mt-1 hidden">Este campo é obrigatório para este status.</p>
                    @error('status_descricao')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeStatusModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm font-medium transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Deletar --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
            <div class="px-6 py-4 border-b border-gray-300">
                <h3 class="text-lg font-semibold text-red-600">Deletar Chamado?</h3>
            </div>

            <div class="px-6 py-4">
                <p class="text-gray-600 mb-4">
                    Você está prestes a deletar o chamado <strong>#{{ $chamado->id_chamado }}</strong>. Esta ação é <strong>irreversível</strong>.
                </p>

                <form method="POST" action="{{ route('chamados.destroy', $chamado->id_chamado) }}" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm font-medium transition">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition">
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
