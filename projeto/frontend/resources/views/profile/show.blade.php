<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meu Perfil – PredialFix</title>
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

    <main class="mx-auto w-full max-w-4xl flex-1 px-6 py-8">
        {{-- Cabeçalho do Perfil --}}
        <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-gray-800">{{ $user->nome }}</h1>
            <p class="text-gray-600">Gerencie suas informações de perfil</p>
        </div>

        {{-- Alertas --}}
        @if (session('success'))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Grid de conteúdo --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {{-- Coluna Esquerda: Informações do Perfil --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Card de Informações Básicas --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Informações Básicas</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Nome</label>
                            <p class="text-gray-800">{{ $user->nome }}</p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600"
                                >E-mail</label
                            >
                            <p class="text-gray-800">{{ $user->email }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-600"
                                    >Nível de Acesso</label
                                >
                                <div
                                    class="inline-block rounded bg-red-100 px-3 py-1 text-sm font-semibold text-red-700"
                                >
                                    @php
                                        $niveis = [
                                            'administrador' => 'Administrador',
                                            'gerente_manutencao' => 'Gerente de Manutenção',
                                            'tecnico_manutencao' => 'Técnico de Manutenção',
                                            'professor' => 'Professor',
                                            'aluno' => 'Aluno',
                                            'visitante' => 'Visitante',
                                        ];
                                    @endphp
                                    {{
                                        $niveis[$user->nivel_acesso] ??
                                            $user->nivel_acesso
                                    }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-600"
                                    >Setor</label
                                >
                                <p class="text-gray-800">{{ $user->setor ?? 'Não informado' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600"
                                >Membro desde</label
                            >
                            <p class="text-gray-800">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>

                        <div class="flex gap-2 pt-4">
                            <a
                                href="{{ route('profile.edit') }}"
                                class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                            >
                                Editar Perfil
                            </a>
                            <button
                                type="button"
                                onclick="openChangePasswordModal()"
                                class="rounded bg-gray-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700"
                            >
                                Alterar Senha
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Card de Chamados Recentes --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">
                        Seus Chamados (5 Recentes)
                    </h2>

                    @if ($chamadosCriados->count() > 0)
                        <div class="space-y-3">
                            @foreach ($chamadosCriados as $chamado)
                                <div
                                    class="rounded-lg border border-gray-200 p-4 transition hover:bg-gray-50"
                                >
                                    <div class="mb-2 flex items-start justify-between">
                                        <h3 class="font-semibold text-gray-800">
                                            {{
                                                Str::limit(
                                                    $chamado->descricao,
                                                    50,
                                                )
                                            }}
                                        </h3>
                                        @php
                                            $statusColors = [
                                                'aberto' => 'bg-blue-100 text-blue-700',
                                                'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                                'concluido' => 'bg-green-100 text-green-700',
                                                'cancelado' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold {{ $statusColors[$chamado->status] ?? '' }}"
                                        >
                                            {{
                                                ucfirst(
                                                    str_replace('_', ' ', $chamado->status),
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <p class="mb-2 text-xs text-gray-600">
                                        {{
                                            $chamado->local->sala_setor ??
                                                'Local desconhecido'
                                        }} - {{ $chamado->data_abertura->format('d/m/Y') }}
                                    </p>
                                    <a
                                        href="{{ route('chamados.show', $chamado->id_chamado) }}"
                                        class="text-sm font-medium text-red-600 hover:underline"
                                    >
                                        Ver Detalhes →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-8 text-center text-gray-500">Você não tem chamados registrados.</p>
                    @endif

                    @if ($chamadosCriados->count() > 0)
                        <a
                            href="{{ route('chamados.index') }}"
                            class="mt-4 inline-block text-sm font-medium text-red-600 hover:underline"
                        >
                            Ver todos os chamados →
                        </a>
                    @endif
                </div>

                {{-- Card de Avaliações/Feedbacks --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">
                        Suas Avaliações (5 Recentes)
                    </h2>

                    @if ($feedbacks->count() > 0)
                        <div class="space-y-3">
                            @foreach ($feedbacks as $feedback)
                                <div
                                    class="rounded-lg border border-gray-200 p-4 transition hover:bg-gray-50"
                                >
                                    <div class="mb-2 flex items-start justify-between">
                                        <h3 class="font-semibold text-gray-800">
                                            Chamado #{{ $feedback->chamado->id_chamado }}
                                        </h3>
                                        <div class="flex items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span
                                                    class="text-lg @if($i <= $feedback->avaliacao) text-yellow-400 @else text-gray-300 @endif"
                                                    >★</span
                                                >
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-2 text-sm text-gray-600">{{
                                        Str::limit(
                                            $feedback->comentario,
                                            100,
                                        )
                                    }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{
                                            $feedback->created_at->format(
                                                'd/m/Y H:i',
                                            )
                                        }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-8 text-center text-gray-500">Você não tem avaliações registradas.</p>
                    @endif
                </div>
            </div>

            {{-- Coluna Direita: Resumo --}}
            <div class="space-y-6">
                {{-- Card de Status --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 font-semibold text-gray-800">Status da Conta</h3>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-block h-3 w-3 rounded-full bg-green-500"></span>
                            <span class="text-sm text-gray-700">
                                @if ($user->ativo) Conta Ativa @else Conta Inativa @endif
                            </span>
                        </div>

                        <div class="border-t border-gray-200 pt-2">
                            <p class="text-xs text-gray-600">Última atualização:</p>
                            <p class="text-sm text-gray-800">{{
                                $user->updated_at->format(
                                    'd/m/Y H:i',
                                )
                            }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card de Ações Perigosas --}}
                <div class="rounded-lg border-l-4 border-red-500 bg-white p-6 shadow">
                    <h3 class="mb-4 font-semibold text-gray-800 text-red-600">Ações Perigosas</h3>

                    <button
                        type="button"
                        onclick="openLogoutModal()"
                        class="mb-2 w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                    >
                        Sair da Conta
                    </button>

                    <button
                        type="button"
                        onclick="openDeleteAccountModal()"
                        class="w-full rounded bg-red-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-950"
                    >
                        Deletar Conta
                    </button>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal de Alterar Senha --}}
    <div
        id="changePasswordModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50"
    >
        <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Alterar Senha</h3>

            <form method="POST" action="{{ route('profile.updatePassword') }}" novalidate>
                @csrf
                @method ('PUT')

                <div class="mb-4">
                    <label for="senha_atual" class="mb-1 block text-sm font-medium text-gray-700"
                        >Senha Atual</label
                    >
                    <input
                        id="senha_atual"
                        type="password"
                        name="senha_atual"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        @error ('senha_atual') class="border-red-400 bg-red-50" @enderror
                    />
                    @error ('senha_atual')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="senha_nova" class="mb-1 block text-sm font-medium text-gray-700"
                        >Nova Senha</label
                    >
                    <input
                        id="senha_nova"
                        type="password"
                        name="senha_nova"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        @error ('senha_nova') class="border-red-400 bg-red-50" @enderror
                    />
                    @error ('senha_nova')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label
                        for="senha_nova_confirmation"
                        class="mb-1 block text-sm font-medium text-gray-700"
                        >Confirmar Senha</label
                    >
                    <input
                        id="senha_nova_confirmation"
                        type="password"
                        name="senha_nova_confirmation"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    />
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeChangePasswordModal()"
                        class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal de Logout --}}
    <div
        id="logoutModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50"
    >
        <div class="mx-4 w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-3 text-lg font-semibold text-gray-800">Sair da Conta?</h3>
            <p class="mb-6 text-gray-600">Você está prestes a sair de sua conta. Tem certeza?</p>

            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="closeLogoutModal()"
                    class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                >
                    Cancelar
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button
                        type="submit"
                        class="w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                    >
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal de Deletar Conta --}}
    <div
        id="deleteAccountModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50"
    >
        <div class="mx-4 w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-3 text-lg font-semibold text-red-600">Deletar Conta Permanentemente?</h3>
            <p class="mb-4 text-gray-600">Esta ação é <strong>irreversível</strong>. Todos os seus dados serão removidos.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" novalidate>
                @csrf
                @method ('DELETE')

                <div class="mb-4">
                    <label for="delete_senha" class="mb-1 block text-sm font-medium text-gray-700"
                        >Confirme sua senha para deletar:</label
                    >
                    <input
                        id="delete_senha"
                        type="password"
                        name="senha"
                        placeholder="Sua senha"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        @error ('senha') class="border-red-400 bg-red-50" @enderror
                    />
                    @error ('senha')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeDeleteAccountModal()"
                        class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded bg-red-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-950"
                    >
                        Deletar Permanentemente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.remove('hidden');
        }

        function closeChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.add('hidden');
        }

        function openLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        function openDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.remove('hidden');
        }

        function closeDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.add('hidden');
        }

        // Fechar modals ao clicar fora
        document.getElementById('changePasswordModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'changePasswordModal') closeChangePasswordModal();
        });

        document.getElementById('logoutModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'logoutModal') closeLogoutModal();
        });

        document.getElementById('deleteAccountModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'deleteAccountModal') closeDeleteAccountModal();
        });
    </script>
</body>
</html>
