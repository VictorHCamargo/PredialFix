<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Chamado – PredialFix SENAI</title>
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
<body class="min-h-screen flex flex-col bg-white font-sans">

    <x-navbar />

    <!-- Conteúdo -->
    <main class="flex-1 px-6 py-8 max-w-2xl mx-auto w-full">

        <h1 class="text-lg font-semibold text-gray-800 mb-6">
            Editar Chamado #{{ $chamado->id_chamado }}
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 text-xs rounded px-4 py-3 mb-5">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('chamados.update', $chamado->id_chamado) }}" enctype="multipart/form-data" class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            <!-- Descrição do Problema -->
            <div class="flex flex-col gap-2">
                <label for="descricao" class="text-gray-800 text-sm font-semibold">
                    Descrição do Problema *
                </label>
                <textarea id="descricao" name="descricao" required placeholder="Descreva em detalhes o problema encontrado"
                          class="w-full border border-gray-400 rounded px-4 py-2 text-sm text-gray-700 
                                 focus:outline-none focus:ring-2 focus:ring-senai-red resize-none"
                          rows="4">{{ old('descricao', $chamado->descricao) }}</textarea>
                @error('descricao')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tipo de Chamado -->
            <div class="flex flex-col gap-2">
                <label for="tipo_chamado" class="text-gray-800 text-sm font-semibold">
                    Tipo de Chamado *
                </label>
                <div class="relative w-56">
                    <select id="tipo_chamado" name="tipo_chamado" required
                            class="w-full appearance-none bg-white border border-gray-400 rounded px-4 py-2 pr-8 text-sm
                                   text-gray-700 focus:outline-none focus:ring-2 focus:ring-senai-red cursor-pointer">
                        <option value="" disabled>Selecione</option>
                        <option value="interno" {{ old('tipo_chamado', $chamado->tipo_chamado) === 'interno' ? 'selected' : '' }}>Interno</option>
                        <option value="externo" {{ old('tipo_chamado', $chamado->tipo_chamado) === 'externo' ? 'selected' : '' }}>Externo</option>
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600">▼</span>
                </div>
                @error('tipo_chamado')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tipo de Incidente -->
            <div class="flex flex-col gap-2">
                <label for="id_tipo" class="text-gray-800 text-sm font-semibold">
                    Tipo de Incidente:
                </label>
                <div class="relative w-56">
                    <select id="id_tipo" name="id_tipo" required
                            class="w-full appearance-none bg-white border border-gray-400 rounded px-4 py-2 pr-8 text-sm
                                   text-gray-700 focus:outline-none focus:ring-2 focus:ring-senai-red cursor-pointer">
                        <option value="" disabled>Selecione</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo }}" {{ old('id_tipo', $chamado->id_tipo) == $tipo->id_tipo ? 'selected' : '' }}>
                                {{ $tipo->categoria }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600">▼</span>
                </div>
            </div>

            <!-- Local -->
            <div class="flex flex-col gap-2">
                <label for="id_local" class="text-gray-800 text-sm font-semibold">
                    Local
                </label>
                <div class="relative w-56">
                    <select id="id_local" name="id_local" required
                            class="w-full appearance-none bg-white border border-gray-400 rounded px-4 py-2 pr-8 text-sm
                                   text-gray-700 focus:outline-none focus:ring-2 focus:ring-senai-red cursor-pointer">
                        <option value="" disabled>Selecione</option>
                        @foreach ($locais as $local)
                            <option value="{{ $local->id_local }}" {{ old('id_local', $chamado->id_local) == $local->id_local ? 'selected' : '' }}>
                                {{ $local->sala_setor }} - Bloco {{ $local->bloco }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600">▼</span>
                </div>
            </div>

            <!-- Equipamento -->
            <div class="flex flex-col gap-2">
                <label for="id_equipamento" class="text-gray-800 text-sm font-semibold">
                    Equipamento
                </label>
                <div class="relative w-56">
                    <select id="id_equipamento" name="id_equipamento"
                            class="w-full appearance-none bg-white border border-gray-400 rounded px-4 py-2 pr-8 text-sm
                                   text-gray-700 focus:outline-none focus:ring-2 focus:ring-senai-red cursor-pointer">
                        <option value="">Selecione</option>
                        @foreach ($equipamentos as $equipamento)
                            <option value="{{ $equipamento->id_equipamento }}" {{ old('id_equipamento', $chamado->id_equipamento) == $equipamento->id_equipamento ? 'selected' : '' }}>
                                {{ $equipamento->nome }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600">▼</span>
                </div>
            </div>

            <!-- Botões -->
            <div class="pt-3 flex gap-3">
                <button type="submit"
                        class="bg-senai-red hover:bg-red-700 text-white font-bold text-sm px-8 py-3 rounded
                               transition duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-senai-red focus:ring-offset-2">
                    Salvar Alterações
                </button>
                <a href="{{ route('chamados.show', $chamado->id_chamado) }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold text-sm px-8 py-3 rounded
                          transition duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2">
                    Cancelar
                </a>
            </div>
        </form>
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

</body>
</html>
