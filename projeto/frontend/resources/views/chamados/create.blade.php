<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Chamado – PredialFix SENAI</title>
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

    <div class="w-full bg-senai-dark text-white text-xs px-4 py-1 select-none">novo chamado</div>

    <!-- Navbar -->
    <nav class="bg-senai-red flex items-center justify-between px-4 shadow-md">
        <div class="flex items-center gap-1 py-2">
            <div class="bg-white text-senai-red font-black text-2xl px-3 py-1 tracking-tight select-none leading-none">SENAI</div>
        </div>
        <div class="flex items-center h-full">
            <a href="{{ route('dashboard') }}"
               class="text-white text-sm font-medium px-5 py-4 border-r border-red-400 hover:bg-red-700 transition">Home</a>
            <a href="{{ route('chamados.create') }}"
               class="text-white text-sm font-medium px-5 py-4 border-r border-red-400 bg-red-700 transition">Novo Chamado</a>
            <a href="{{ route('chamados.index') }}"
               class="text-white text-sm font-medium px-5 py-4 border-r border-red-400 hover:bg-red-700 transition">Gerenciar Chamados</a>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="flex items-center">
            @csrf
            <button type="submit" class="text-white text-sm font-medium px-5 py-4 flex items-center gap-2 hover:bg-red-700 transition">
                Sair
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
            </button>
        </form>
    </nav>

    <!-- Conteúdo -->
    <main class="flex-1 px-6 py-8 max-w-3xl w-full">

        <h1 class="text-xl font-semibold text-gray-800 mb-6">
            Olá, <span class="font-bold">{{ Auth::user()->nome }}</span>. Relate o problema abaixo.
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 text-sm rounded px-4 py-3 mb-5">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{--
            Campos alinhados 100% com ChamadoController::store():
              descricao     → required|string
              prioridade    → required|in:baixa,media,alta
              id_local      → required|exists:locais,id_local
              id_tipo       → required|exists:tipo_problemas,id_tipo
              id_equipamento→ nullable|exists:equipamentos,id_equipamento
        --}}
        <form method="POST" action="{{ route('chamados.store') }}" enctype="multipart/form-data" class="flex flex-col gap-5">
            @csrf

            <!-- id_tipo -->
            <div class="flex flex-col gap-1">
                <label for="id_tipo" class="text-gray-800 text-sm font-semibold">
                    Tipo de Incidente: <span class="text-senai-red">*</span>
                </label>
                <div class="relative w-52">
                    <select id="id_tipo" name="id_tipo" required
                            class="w-full appearance-none bg-white border border-gray-400 rounded px-4 py-2 pr-8 text-sm
                                   text-gray-700 focus:outline-none focus:ring-2 focus:ring-senai-red cursor-pointer">
                        <option value="" disabled {{ old('id_tipo') ? '' : 'selected' }}>Selecione</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo }}" {{ old('id_tipo') == $tipo->id_tipo ? 'selected' : '' }}>
                                {{ $tipo->nome }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-500 text-xs">▼</span>
                </div>
            </div>

            <!-- id_local -->
            <div class="flex flex-col gap-1">
                <label for="id_local" class="text-gray-800 text-sm font-semibold">
                    Local <span class="text-senai-red">*</span>
                </label>
                <select id="id_local" name="id_local" required
                        class="w-full max-w-xl border border-gray-400 rounded px-4 py-2 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-senai-red bg-white cursor-pointer">
                    <option value="" disabled {{ old('id_local') ? '' : 'selected' }}>Selecione o local</option>
                    @foreach ($locais as $local)
                        <option value="{{ $local->id_local }}" {{ old('id_local') == $local->id_local ? 'selected' : '' }}>
                            {{ $local->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- prioridade → SOMENTE: baixa | media | alta -->
            <div class="flex flex-col gap-1">
                <label for="prioridade" class="text-gray-800 text-sm font-semibold">
                    Nível de Prioridade <span class="text-senai-red">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <div class="relative w-52">
                        <select id="prioridade" name="prioridade" required
                                class="w-full appearance-none bg-white border border-gray-400 rounded px-4 py-2 pr-8 text-sm
                                       text-gray-700 focus:outline-none focus:ring-2 focus:ring-senai-red cursor-pointer">
                            <option value="" disabled {{ old('prioridade') ? '' : 'selected' }}>Selecione</option>
                            <option value="baixa" {{ old('prioridade') === 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="media" {{ old('prioridade') === 'media' ? 'selected' : '' }}>Média</option>
                            <option value="alta"  {{ old('prioridade') === 'alta'  ? 'selected' : '' }}>Alta</option>
                            {{-- "critica" removido: controller rejeita com in:baixa,media,alta --}}
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-500 text-xs">▼</span>
                    </div>
                    <span class="text-gray-500 text-xs">(Baixa, Média, Alta)</span>
                </div>
            </div>

            <!-- id_equipamento → nullable, lista de Equipamento::where('status','ativo') -->
            <div class="flex flex-col gap-1">
                <label for="id_equipamento" class="text-gray-800 text-sm font-semibold">
                    Equipamento
                    <span class="text-gray-400 text-xs font-normal">(opcional)</span>
                </label>
                <div class="relative w-52">
                    <select id="id_equipamento" name="id_equipamento"
                            class="w-full appearance-none bg-white border border-gray-400 rounded px-4 py-2 pr-8 text-sm
                                   text-gray-700 focus:outline-none focus:ring-2 focus:ring-senai-red cursor-pointer">
                        <option value="">Nenhum</option>
                        @foreach ($equipamentos as $equipamento)
                            <option value="{{ $equipamento->id_equipamento }}"
                                {{ old('id_equipamento') == $equipamento->id_equipamento ? 'selected' : '' }}>
                                {{ $equipamento->nome }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-500 text-xs">▼</span>
                </div>
            </div>

            <!-- descricao → required|string -->
            <div class="flex flex-col gap-1">
                <label for="descricao" class="text-gray-800 text-sm font-semibold">
                    Descrição <span class="text-senai-red">*</span>
                </label>
                <textarea id="descricao" name="descricao" rows="4" required
                          placeholder="Descreva o problema com detalhes..."
                          class="w-full max-w-xl border border-gray-400 rounded px-4 py-2 text-sm text-gray-700
                                 focus:outline-none focus:ring-2 focus:ring-senai-red resize-none">{{ old('descricao') }}</textarea>
            </div>

            <!-- Foto (campo extra de UX — não validado no controller) -->
            <div class="flex flex-col gap-1">
                <label class="text-gray-800 text-sm font-semibold">Foto</label>
                <div id="drop-zone"
                     class="w-24 h-24 border-2 border-dashed border-gray-400 rounded flex flex-col items-center
                            justify-center cursor-pointer hover:border-senai-red hover:bg-red-50 transition relative">
                    <input id="foto" name="foto" type="file" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    <p class="text-gray-500 text-xs text-center leading-tight px-1">Faça upload</p>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 mt-1" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-400 text-xs text-center px-1 mt-1">ou arraste uma imagem</p>
                </div>
                <div id="preview-container" class="hidden mt-2">
                    <img id="preview-img" src="" alt="Pré-visualização" class="max-h-32 rounded border border-gray-300 shadow-sm">
                    <button type="button" id="remove-img" class="mt-1 text-xs text-senai-red hover:underline">Remover imagem</button>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="bg-senai-red hover:bg-red-700 text-white font-bold text-sm px-8 py-3 rounded
                               transition duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-senai-red focus:ring-offset-2">
                    Enviar
                </button>
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

    <script>
        const input = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-container');
        const previewImg = document.getElementById('preview-img');
        const removeBtn = document.getElementById('remove-img');

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { previewImg.src = e.target.result; previewContainer.classList.remove('hidden'); };
                reader.readAsDataURL(this.files[0]);
            }
        });
        removeBtn.addEventListener('click', () => { input.value=''; previewImg.src=''; previewContainer.classList.add('hidden'); });

        const zone = document.getElementById('drop-zone');
        zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('border-senai-red'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('border-senai-red'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('border-senai-red');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>