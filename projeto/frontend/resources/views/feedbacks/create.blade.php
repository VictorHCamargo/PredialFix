<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Chamado – PredialFix SENAI</title>
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

    <!-- Conteúdo -->
    <main class="flex-1 px-6 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 mb-8">Avaliar Chamado</h1>

            <!-- Card com informações do chamado -->
            <div class="bg-white border border-gray-300 rounded p-6 mb-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ $chamado->tipoProblema->categoria ?? 'Sem categoria' }}
                    </h2>
                    <p class="text-gray-600 text-sm mb-4">
                        {{ $chamado->descricao }}
                    </p>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-semibold">Local:</span> {{ $chamado->local->sala_setor }} - Bloco {{ $chamado->local->bloco }}
                        </div>
                        <div>
                            <span class="font-semibold">Prioridade:</span> 
                            <span class="capitalize">{{ $chamado->prioridade }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Abertura:</span> 
                            {{ \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y') }}
                        </div>
                        <div>
                            <span class="font-semibold">Conclusão:</span> 
                            {{ $chamado->data_conclusao ? \Carbon\Carbon::parse($chamado->data_conclusao)->format('d/m/Y') : '—' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de avaliação -->
            <div class="bg-white border border-gray-300 rounded p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Sua Avaliação</h3>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc pl-4 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('avaliar.store') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Campo oculto com o ID do chamado -->
                    <input type="hidden" name="id_chamado" value="{{ $chamado->id_chamado }}">

                    <!-- Nota em Estrelas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Qual é sua avaliação? *
                        </label>
                        <div class="flex gap-3" id="stars-container">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="nota" value="{{ $i }}" class="hidden" required>
                                    <span class="text-5xl text-gray-400 hover:text-senai-red transition star" data-value="{{ $i }}">★</span>
                                </label>
                            @endfor
                        </div>
                        @error('nota')
                            <span class="text-red-600 text-xs mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Comentário -->
                    <div>
                        <label for="comentario" class="block text-sm font-semibold text-gray-700 mb-2">
                            Comentário (opcional)
                        </label>
                        <textarea id="comentario" name="comentario" placeholder="Deixe um comentário sobre o atendimento..."
                                  class="w-full border border-gray-400 rounded px-4 py-2 text-sm text-gray-700
                                         focus:outline-none focus:ring-2 focus:ring-senai-red resize-none"
                                  rows="5">{{ old('comentario') }}</textarea>
                        @error('comentario')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-3 pt-3">
                        <button type="submit"
                                class="bg-senai-red hover:bg-red-700 text-white font-bold text-sm px-8 py-3 rounded
                                       transition duration-200 active:scale-95 focus:outline-none">
                            Enviar Avaliação
                        </button>
                        <a href="{{ route('chamados.index') }}"
                           class="text-gray-600 hover:text-gray-800 font-semibold text-sm px-8 py-3 border border-gray-300 rounded
                                  transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-senai-red mt-8">
        <div class="px-6 py-6 text-white">
            <div class="max-w-4xl mx-auto grid grid-cols-2 gap-6">
                <div>
                    <p class="font-semibold mb-2">EDIFÍCIO SEDE FIESP</p>
                    <p class="text-xs leading-relaxed">Av. Paulista, 1313, São Paulo/SP</p>
                    <p class="text-xs">CEP 0131-923</p>
                </div>
                <div>
                    <p class="font-semibold mb-2">CENTRAL DE RELACIONAMENTO</p>
                    <p class="text-xs leading-relaxed">(11) 3322-0050 (Telefone/WhatsApp)</p>
                    <p class="text-xs">0800-055-1000 (Interior de SP, somente telefone fixo)</p>
                </div>
            </div>
            <div class="border-t border-red-500 mt-4 pt-4 text-center text-xs">
                Copyright 2026 © Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script>
        // Interatividade das estrelas
        const stars = document.querySelectorAll('.star');
        const starsContainer = document.getElementById('stars-container');
        let selectedRating = 0;

        stars.forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = this.dataset.value;
                updateStars(selectedRating);
                this.closest('label').querySelector('input[type="radio"]').checked = true;
            });

            star.addEventListener('mouseenter', function() {
                updateStars(this.dataset.value);
            });
        });

        starsContainer.addEventListener('mouseleave', function() {
            updateStars(selectedRating);
        });

        function updateStars(rating) {
            stars.forEach(star => {
                if (star.dataset.value <= rating) {
                    star.classList.remove('text-gray-400');
                    star.classList.add('text-senai-red');
                } else {
                    star.classList.remove('text-senai-red');
                    star.classList.add('text-gray-400');
                }
            });
        }
    </script>

</body>
</html>
