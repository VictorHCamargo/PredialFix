<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Usuarios - PredialFix SENAI</title>
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

    <main class="mx-auto w-full max-w-7xl flex-1 px-6 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Usuarios</h1>
                <p class="text-sm text-gray-500">Cadastro e status de funcionarios.</p>
            </div>

            <a href="{{ route('admin.usuarios.create') }}" class="rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                Novo funcionario
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $nivelLabels = [
                'administrador' => 'Administrador',
                'gerente_manutencao' => 'Gerente de manutencao',
                'tecnico_manutencao' => 'Tecnico de manutencao',
                'professor' => 'Professor',
                'aluno' => 'Aluno',
            ];
        @endphp

        <div class="overflow-hidden rounded border border-gray-200 bg-white shadow">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Nome</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">E-mail</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Nivel</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Setor</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Cracha</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-800">{{ $usuario->nome }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $usuario->id_usuario }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $usuario->email }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $nivelLabels[$usuario->nivel_acesso] ?? $usuario->nivel_acesso }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $usuario->setor ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $usuario->cod_entrada ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($usuario->ativo)
                                        <span class="rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">Ativo</span>
                                    @else
                                        <span class="rounded bg-gray-200 px-2 py-1 text-xs font-semibold text-gray-700">Inativo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.usuarios.edit', $usuario->id_usuario) }}" class="rounded bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-700">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('admin.usuarios.toggle', $usuario->id_usuario) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded bg-gray-700 px-3 py-1 text-xs font-semibold text-white hover:bg-gray-800">
                                                {{ $usuario->ativo ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Nenhum usuario cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($usuarios->hasPages())
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </main>

    <x-footer />
</body>
</html>
