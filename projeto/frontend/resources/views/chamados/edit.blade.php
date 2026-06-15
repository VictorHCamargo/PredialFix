<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Chamado - PredialFix SENAI</title>
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
<body class="flex min-h-screen flex-col bg-white font-sans">
    <x-navbar />

    <main class="mx-auto w-full max-w-3xl flex-1 px-6 py-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Editar chamado #{{ $chamado->id_chamado }}</h1>

        @if ($errors->any())
            <div class="mb-5 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('chamados.update', $chamado->id_chamado) }}" class="space-y-5">
            @csrf
            @method('PUT')

            @if(auth()->user()->isProfessor())
                <div class="rounded border border-blue-300 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    Professores podem editar apenas a descricao enquanto o chamado estiver aberto ou em andamento.
                </div>

                <div>
                    <label for="descricao" class="mb-1 block text-sm font-semibold text-gray-800">Descricao</label>
                    <textarea
                        id="descricao"
                        name="descricao"
                        rows="6"
                        required
                        class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    >{{ old('descricao', $chamado->descricao) }}</textarea>
                </div>
            @else
                <input type="hidden" name="tipo_chamado" value="interno" />

                <div>
                    <label for="descricao" class="mb-1 block text-sm font-semibold text-gray-800">Descricao</label>
                    <textarea
                        id="descricao"
                        name="descricao"
                        rows="5"
                        required
                        class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    >{{ old('descricao', $chamado->descricao) }}</textarea>
                </div>

                <div>
                    <label for="id_patrimonio" class="mb-1 block text-sm font-semibold text-gray-800">ID de patrimonio</label>
                    <input
                        id="id_patrimonio"
                        type="text"
                        name="id_patrimonio"
                        value="{{ old('id_patrimonio', $chamado->id_patrimonio) }}"
                        class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="id_tipo" class="mb-1 block text-sm font-semibold text-gray-800">Tipo de incidente</label>
                        <select id="id_tipo" name="id_tipo" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo }}" @selected(old('id_tipo', $chamado->id_tipo) == $tipo->id_tipo)>
                                    {{ $tipo->categoria }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_local" class="mb-1 block text-sm font-semibold text-gray-800">Local</label>
                        <select id="id_local" name="id_local" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            @foreach ($locais as $local)
                                <option value="{{ $local->id_local }}" @selected(old('id_local', $chamado->id_local) == $local->id_local)>
                                    {{ $local->sala_setor }} - Bloco {{ $local->bloco }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_equipamento" class="mb-1 block text-sm font-semibold text-gray-800">Equipamento</label>
                        <select id="id_equipamento" name="id_equipamento" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Nenhum</option>
                            @foreach ($equipamentos as $equipamento)
                                <option value="{{ $equipamento->id_equipamento }}" @selected(old('id_equipamento', $chamado->id_equipamento) == $equipamento->id_equipamento)>
                                    {{ $equipamento->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="secao_tecnica" class="mb-1 block text-sm font-semibold text-gray-800">Seccao tecnica</label>
                        <select id="secao_tecnica" name="secao_tecnica" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="eletrica" @selected(old('secao_tecnica', $chamado->secao_tecnica) === 'eletrica')>Eletrica</option>
                            <option value="hidraulica" @selected(old('secao_tecnica', $chamado->secao_tecnica) === 'hidraulica')>Hidraulica</option>
                            <option value="civil" @selected(old('secao_tecnica', $chamado->secao_tecnica) === 'civil')>Civil</option>
                            <option value="mecanica" @selected(old('secao_tecnica', $chamado->secao_tecnica) === 'mecanica')>Mecanica</option>
                        </select>
                    </div>

                    <div>
                        <label for="prioridade" class="mb-1 block text-sm font-semibold text-gray-800">Prioridade</label>
                        <select id="prioridade" name="prioridade" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Sem prioridade</option>
                            <option value="baixa" @selected(old('prioridade', $chamado->prioridade) === 'baixa')>Baixa</option>
                            <option value="media" @selected(old('prioridade', $chamado->prioridade) === 'media')>Media</option>
                            <option value="alta" @selected(old('prioridade', $chamado->prioridade) === 'alta')>Alta</option>
                        </select>
                    </div>

                    <div>
                        <label for="complexidade" class="mb-1 block text-sm font-semibold text-gray-800">Complexidade</label>
                        <select id="complexidade" name="complexidade" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="simples" @selected(old('complexidade', $chamado->complexidade) === 'simples')>Simples</option>
                            <option value="media" @selected(old('complexidade', $chamado->complexidade) === 'media')>Media</option>
                            <option value="complexa" @selected(old('complexidade', $chamado->complexidade) === 'complexa')>Complexa</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipo_trabalho" class="mb-1 block text-sm font-semibold text-gray-800">Tipo de trabalho</label>
                        <select id="tipo_trabalho" name="tipo_trabalho" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="preventiva" @selected(old('tipo_trabalho', $chamado->tipo_trabalho) === 'preventiva')>Preventiva</option>
                            <option value="corretiva" @selected(old('tipo_trabalho', $chamado->tipo_trabalho) === 'corretiva')>Corretiva</option>
                            <option value="melhoria" @selected(old('tipo_trabalho', $chamado->tipo_trabalho) === 'melhoria')>Melhoria</option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">
                    Salvar alteracoes
                </button>
                <a href="{{ route('chamados.show', $chamado->id_chamado) }}" class="rounded border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </main>

    <x-footer />
</body>
</html>
