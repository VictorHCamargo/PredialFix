<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chamado #{{ $chamado->id_chamado }} - PredialFix</title>
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

    @php
        $user = auth()->user();
        $podeAlterarStatus = $user->isAdmin() || $user->isEquipeManutencao();
        $podeEditar = $user->isAdmin() || $user->isEquipeManutencao() || ($user->isProfessor() && $chamado->id_usuario === $user->id_usuario && in_array($chamado->status, ['aberto', 'em_andamento']));
        $podeCancelar = $user->isAdmin() || $user->isEquipeManutencao();
    @endphp

    <main class="mx-auto w-full max-w-6xl flex-1 px-6 py-8">
        <div class="mb-6 text-sm text-gray-600">
            <a href="{{ route('chamados.index') }}" class="hover:text-gray-800">Chamados</a>
            <span class="mx-2">/</span>
            <span class="font-semibold text-gray-800">Chamado #{{ $chamado->id_chamado }}</span>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Chamado #{{ $chamado->id_chamado }}</h1>
                            <p class="mt-2 text-gray-600">{{ $chamado->descricao }}</p>
                        </div>

                        @php
                            $statusColors = [
                                'aberto' => 'bg-blue-100 text-blue-700',
                                'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                'concluido' => 'bg-green-100 text-green-700',
                                'cancelado' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="inline-block rounded-lg px-4 py-2 font-semibold {{ $statusColors[$chamado->status] ?? '' }}">
                            {{ ucfirst(str_replace('_', ' ', $chamado->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500">Abertura</p>
                            <p class="font-semibold text-gray-800">{{ $chamado->data_abertura->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Local</p>
                            <p class="font-semibold text-gray-800">{{ $chamado->local->sala_setor ?? '—' }} {{ $chamado->local->bloco ? '- Bloco ' . $chamado->local->bloco : '' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Prioridade</p>
                            <p class="font-semibold text-gray-800">{{ $chamado->prioridade ? ucfirst($chamado->prioridade) : '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Solicitante</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-medium text-gray-500">Nome</p>
                            <p class="font-semibold text-gray-800">{{ $chamado->usuario->nome ?? 'Desconhecido' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">E-mail</p>
                            <p class="font-semibold text-gray-800">{{ $chamado->usuario->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">ID</p>
                            <p class="font-semibold text-gray-800">{{ $chamado->usuario->id_usuario ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Cracha</p>
                            <p class="font-semibold text-gray-800">{{ $chamado->usuario->cod_entrada ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Informacoes adicionais</h2>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500">Tipo de problema</p>
                            <p class="text-gray-800">{{ $chamado->tipoProblema->categoria ?? '—' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500">ID de patrimonio</p>
                            <p class="text-gray-800">{{ $chamado->id_patrimonio ?? '—' }}</p>
                        </div>

                        @if ($chamado->equipamento)
                            <div>
                                <p class="text-xs font-medium text-gray-500">Equipamento</p>
                                <p class="text-gray-800">{{ $chamado->equipamento->nome ?? '—' }}</p>
                            </div>
                        @endif

                        @if ($chamado->status_descricao)
                            <div>
                                <p class="text-xs font-medium text-gray-500">Descricao do status</p>
                                <p class="rounded bg-gray-100 p-3 text-gray-800">{{ $chamado->status_descricao }}</p>
                            </div>
                        @endif

                        @if ($chamado->data_conclusao)
                            <div>
                                <p class="text-xs font-medium text-gray-500">Concluido em</p>
                                <p class="text-gray-800">{{ $chamado->data_conclusao->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Historico de status</h2>

                    @if ($chamado->historicoStatus->count())
                        <div class="space-y-4">
                            @foreach ($chamado->historicoStatus->reverse() as $historico)
                                <div class="border-l-4 border-gray-300 pl-4">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
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
                                        <p class="mt-2 rounded bg-gray-50 p-3 text-sm text-gray-700">{{ $historico->descricao_mudanca }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-gray-500">Nenhuma mudanca registrada ainda.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 font-semibold text-gray-800">Acoes</h3>
                    <div class="space-y-2">
                        @if ($podeAlterarStatus)
                            <button type="button" onclick="openStatusModal()" class="w-full rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                Alterar status
                            </button>
                        @endif

                        @if ($podeEditar)
                            <a href="{{ route('chamados.edit', $chamado->id_chamado) }}" class="block w-full rounded bg-gray-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-gray-700">
                                Editar chamado
                            </a>
                        @endif

                        @if (!auth()->user()->isAluno() && $chamado->status === 'concluido' && !$chamado->feedback && auth()->user()->temCodigoEntrada())
                            <a href="{{ route('avaliar.create', $chamado->id_chamado) }}" class="block w-full rounded bg-purple-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-purple-700">
                                Avaliar chamado
                            </a>
                        @endif

                        @if ($podeCancelar)
                            <button type="button" onclick="openCancelModal()" class="w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                Cancelar chamado
                            </button>
                        @endif

                        <a href="{{ route('chamados.index') }}" class="block w-full rounded bg-gray-200 px-4 py-2 text-center text-sm font-medium text-gray-800 hover:bg-gray-300">
                            Voltar
                        </a>
                    </div>
                </div>

                @if ($chamado->feedback)
                    <div class="rounded-lg bg-white p-6 shadow">
                        <h3 class="mb-4 font-semibold text-gray-800">Feedback</h3>
                        <p class="text-sm text-gray-600">Avaliacao</p>
                        <p class="mb-3 text-lg font-semibold text-gray-800">{{ $chamado->feedback->nota }}</p>
                        <p class="text-sm text-gray-600">Comentario</p>
                        <p class="text-sm text-gray-800">{{ $chamado->feedback->comentario }}</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    @if ($podeAlterarStatus)
        <div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-md rounded-lg bg-white shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">Alterar status</h3>
                </div>

                <form method="POST" action="{{ route('chamados.updateStatus', $chamado->id_chamado) }}" class="space-y-4 px-6 py-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Novo status</label>
                        <select id="status" name="status" required onchange="atualizarCampos()" class="w-full rounded border border-gray-300 px-4 py-2 text-sm">
                            <option value="">Selecione um status</option>
                            <option value="aberto">Aberto</option>
                            <option value="em_andamento">Em andamento</option>
                            <option value="concluido">Concluido</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>

                    <div id="prioridadeContainer" class="hidden">
                        <label for="prioridade" class="mb-2 block text-sm font-medium text-gray-700">Prioridade</label>
                        <select id="prioridade" name="prioridade" class="w-full rounded border border-gray-300 px-4 py-2 text-sm">
                            <option value="">Sem prioridade</option>
                            <option value="baixa">Baixa</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>

                    <div id="descricaoContainer" class="hidden">
                        <label for="status_descricao" class="mb-2 block text-sm font-medium text-gray-700">Descricao / justificativa</label>
                        <textarea id="status_descricao" name="status_descricao" rows="4" class="w-full rounded border border-gray-300 px-4 py-2 text-sm" placeholder="Descreva a mudanca de status..."></textarea>
                        <p class="mt-1 text-xs text-gray-500">Se o status for cancelado, a justificativa deve ter pelo menos 10 caracteres.</p>
                    </div>

                    <div class="flex gap-3 border-t border-gray-200 pt-4">
                        <button type="button" onclick="closeStatusModal()" class="flex-1 rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($podeCancelar)
        <div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-md rounded-lg bg-white shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-red-600">Cancelar chamado</h3>
                </div>

                <form method="POST" action="{{ route('chamados.destroy', $chamado->id_chamado) }}" class="space-y-4 px-6 py-5">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="justificativa_cancelamento" class="mb-2 block text-sm font-medium text-gray-700">Justificativa obrigatoria</label>
                        <textarea id="justificativa_cancelamento" name="justificativa_cancelamento" rows="5" required minlength="10" class="w-full rounded border border-gray-300 px-4 py-2 text-sm" placeholder="Explique o motivo do cancelamento..."></textarea>
                    </div>

                    <div class="flex gap-3 border-t border-gray-200 pt-4">
                        <button type="button" onclick="closeCancelModal()" class="flex-1 rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300">
                            Voltar
                        </button>
                        <button type="submit" class="flex-1 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Confirmar cancelamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <x-footer />

    <script>
        function openStatusModal() {
            document.getElementById('statusModal')?.classList.remove('hidden');
            document.getElementById('statusModal')?.classList.add('flex');
        }

        function closeStatusModal() {
            document.getElementById('statusModal')?.classList.add('hidden');
            document.getElementById('statusModal')?.classList.remove('flex');
        }

        function openCancelModal() {
            document.getElementById('cancelModal')?.classList.remove('hidden');
            document.getElementById('cancelModal')?.classList.add('flex');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal')?.classList.add('hidden');
            document.getElementById('cancelModal')?.classList.remove('flex');
        }

        function atualizarCampos() {
            const status = document.getElementById('status').value;
            const prioridadeContainer = document.getElementById('prioridadeContainer');
            const descricaoContainer = document.getElementById('descricaoContainer');

            prioridadeContainer.classList.add('hidden');
            descricaoContainer.classList.add('hidden');

            if (status === 'em_andamento') {
                prioridadeContainer.classList.remove('hidden');
            }

            if (status === 'concluido' || status === 'cancelado') {
                descricaoContainer.classList.remove('hidden');
            }
        }

        document.getElementById('statusModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'statusModal') closeStatusModal();
        });

        document.getElementById('cancelModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'cancelModal') closeCancelModal();
        });
    </script>
</body>
</html>
