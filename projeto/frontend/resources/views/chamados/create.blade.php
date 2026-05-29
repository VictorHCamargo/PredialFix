<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Novo Chamado - PredialFix SENAI</title>
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
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Relatar novo problema</h1>

        @if ($errors->any())
            <div class="mb-5 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('chamados.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="tipo_chamado" value="interno" />

            @if (session('alerta_duplicado'))
                <div class="rounded border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-900">
                    <p class="font-semibold">Ja existe um chamado ativo para este patrimonio.</p>
                    <a
                        href="{{ route('chamados.show', session('alerta_duplicado')) }}"
                        class="mt-1 inline-block font-semibold underline"
                    >
                        Ver chamado existente
                    </a>
                    <label class="mt-3 flex items-center gap-2">
                        <input type="checkbox" name="confirmar_duplicado" value="1" {{ old('confirmar_duplicado') ? 'checked' : '' }} />
                        Desejo abrir mesmo assim (problema diferente)
                    </label>
                </div>
            @endif

            <div>
                <label for="descricao" class="mb-1 block text-sm font-semibold text-gray-800">Descricao do problema *</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    rows="5"
                    required
                    class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="Descreva o problema com o maximo de detalhes"
                >{{ old('descricao') }}</textarea>
            </div>

            <div>
                <label for="id_patrimonio" class="mb-1 block text-sm font-semibold text-gray-800">ID de patrimonio</label>
                <input
                    id="id_patrimonio"
                    type="text"
                    name="id_patrimonio"
                    value="{{ old('id_patrimonio') }}"
                    placeholder="ID do equipamento ou sala"
                    class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="id_tipo" class="mb-1 block text-sm font-semibold text-gray-800">Tipo de incidente</label>
                    <select id="id_tipo" name="id_tipo" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="" disabled {{ old('id_tipo') ? '' : 'selected' }}>Selecione</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo }}" @selected(old('id_tipo') == $tipo->id_tipo)>
                                {{ $tipo->categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_local" class="mb-1 block text-sm font-semibold text-gray-800">Local</label>
                    <select id="id_local" name="id_local" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="" disabled {{ old('id_local') ? '' : 'selected' }}>Selecione</option>
                        @foreach ($locais as $local)
                            <option value="{{ $local->id_local }}" @selected(old('id_local') == $local->id_local)>
                                {{ $local->sala_setor }} - Bloco {{ $local->bloco }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @unless(Auth::user()->isAluno())
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="secao_tecnica" class="mb-1 block text-sm font-semibold text-gray-800">Seccao tecnica</label>
                        <select id="secao_tecnica" name="secao_tecnica" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="eletrica" @selected(old('secao_tecnica') === 'eletrica')>Eletrica</option>
                            <option value="hidraulica" @selected(old('secao_tecnica') === 'hidraulica')>Hidraulica</option>
                            <option value="civil" @selected(old('secao_tecnica') === 'civil')>Civil</option>
                            <option value="mecanica" @selected(old('secao_tecnica') === 'mecanica')>Mecanica</option>
                        </select>
                    </div>

                    <div>
                        <label for="prioridade" class="mb-1 block text-sm font-semibold text-gray-800">Prioridade</label>
                        <select id="prioridade" name="prioridade" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="baixa" @selected(old('prioridade') === 'baixa')>Baixa</option>
                            <option value="media" @selected(old('prioridade') === 'media')>Media</option>
                            <option value="alta" @selected(old('prioridade') === 'alta')>Alta</option>
                        </select>
                    </div>

                    <div>
                        <label for="complexidade" class="mb-1 block text-sm font-semibold text-gray-800">Complexidade</label>
                        <select id="complexidade" name="complexidade" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="simples" @selected(old('complexidade') === 'simples')>Simples</option>
                            <option value="media" @selected(old('complexidade') === 'media')>Media</option>
                            <option value="complexa" @selected(old('complexidade') === 'complexa')>Complexa</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipo_trabalho" class="mb-1 block text-sm font-semibold text-gray-800">Tipo de trabalho</label>
                        <select id="tipo_trabalho" name="tipo_trabalho" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="preventiva" @selected(old('tipo_trabalho') === 'preventiva')>Preventiva</option>
                            <option value="corretiva" @selected(old('tipo_trabalho') === 'corretiva')>Corretiva</option>
                            <option value="melhoria" @selected(old('tipo_trabalho') === 'melhoria')>Melhoria</option>
                        </select>
                    </div>
                </div>
            @endunless

            @if (Auth::user()->isAluno())
                <div class="rounded border border-blue-300 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    Alunos nao definem prioridade, seccao tecnica, complexidade ou tipo de trabalho.
                </div>
            @endif

            <div class="pt-2">
                <button type="submit" class="rounded bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">
                    Enviar chamado
                </button>
            </div>
        </form>
    </main>

    <x-footer />
</body>
</html>
