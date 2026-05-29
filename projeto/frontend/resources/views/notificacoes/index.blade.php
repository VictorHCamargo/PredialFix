<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notificacoes - PredialFix SENAI</title>
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
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Notificacoes</h1>
                <p class="text-sm text-gray-500">Atualizacoes dos chamados acompanhados pela sua conta.</p>
            </div>

            <form method="POST" action="{{ route('notificacoes.todasLidas') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    Marcar todas como lidas
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded border border-gray-200 bg-white shadow">
            @forelse ($notificacoes as $notificacao)
                <div class="border-b border-gray-100 px-5 py-4 {{ $notificacao->lida ? 'bg-white' : 'bg-red-50' }}">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <span class="rounded bg-gray-100 px-2 py-1 text-xs font-semibold uppercase text-gray-700">
                                    {{ str_replace('_', ' ', $notificacao->tipo) }}
                                </span>
                                @unless ($notificacao->lida)
                                    <span class="rounded bg-red-600 px-2 py-1 text-xs font-semibold text-white">Nova</span>
                                @endunless
                                <span class="text-xs text-gray-500">{{ $notificacao->created_at?->format('d/m/Y H:i') }}</span>
                            </div>

                            <p class="text-sm text-gray-800">{{ $notificacao->mensagem }}</p>

                            @if ($notificacao->id_chamado)
                                <a href="{{ route('chamados.show', $notificacao->id_chamado) }}" class="mt-2 inline-block text-sm font-semibold text-red-600 hover:underline">
                                    Ver chamado #{{ $notificacao->id_chamado }}
                                </a>
                            @endif
                        </div>

                        @unless ($notificacao->lida)
                            <form method="POST" action="{{ route('notificacoes.lida', $notificacao->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                    Marcar como lida
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-gray-500">
                    Nenhuma notificacao encontrada.
                </div>
            @endforelse

            @if ($notificacoes->hasPages())
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
                    {{ $notificacoes->links() }}
                </div>
            @endif
        </div>
    </main>

    <x-footer />
</body>
</html>
