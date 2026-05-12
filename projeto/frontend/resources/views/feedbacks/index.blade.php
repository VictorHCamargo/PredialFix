<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações – PredialFix SENAI</title>
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
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 mb-8">Avaliações</h1>

            <!-- Mensagem de sucesso -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- SEÇÃO 1: CRIAR NOVA AVALIAÇÃO -->
            <div class="bg-white rounded shadow p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">Criar Nova Avaliação</h2>

                @if ($chamadosParaAvaliar->isEmpty())
                    <div class="bg-blue-50 border border-blue-300 text-blue-700 px-4 py-3 rounded mb-6 text-sm">
                        Nenhum chamado concluído aguardando avaliação no momento.
                    </div>
                @endif

                <form method="POST" action="{{ route('avaliar.store') }}" class="space-y-5">
                    @csrf

                    <!-- Seleção do Chamado -->
                    <div>
                        <label for="id_chamado" class="block text-sm font-semibold text-gray-700 mb-2">
                            Selecione um Chamado *
                        </label>
                        <select id="id_chamado" name="id_chamado" required
                                class="w-full border border-gray-400 rounded px-4 py-2 text-sm text-gray-700
                                       focus:outline-none focus:ring-2 focus:ring-senai-red">
                            <option value="" disabled selected>-- Escolha um chamado concluído --</option>
                            @foreach ($chamadosParaAvaliar as $chamado)
                                <option value="{{ $chamado->id_chamado }}">
                                    {{ $chamado->tipoProblema->categoria }} - {{ $chamado->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_chamado')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nota em Estrelas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Avaliação *
                        </label>
                        <div class="flex gap-3" id="stars-container">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="nota" value="{{ $i }}" class="hidden" required>
                                    <span class="text-4xl text-gray-400 hover:text-senai-red transition star" data-value="{{ $i }}">★</span>
                                </label>
                            @endfor
                        </div>
                        @error('nota')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Comentário -->
                    <div>
                        <label for="comentario" class="block text-sm font-semibold text-gray-700 mb-2">
                            Comentário
                        </label>
                        <textarea id="comentario" name="comentario" placeholder="Deixe um comentário (opcional)"
                                  class="w-full border border-gray-400 rounded px-4 py-2 text-sm text-gray-700
                                         focus:outline-none focus:ring-2 focus:ring-senai-red resize-none"
                                  rows="4">{{ old('comentario') }}</textarea>
                        @error('comentario')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botão Enviar -->
                    <div class="pt-3">
                        <button type="submit"
                                class="bg-senai-red hover:bg-red-700 text-white font-bold text-sm px-8 py-2 rounded
                                       transition duration-200 active:scale-95 focus:outline-none {{ $chamadosParaAvaliar->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $chamadosParaAvaliar->isEmpty() ? 'disabled' : '' }}>
                                Enviar Avaliação
                        </button>
                    </div>
                </form>
            </div>

            <!-- SEÇÃO 2: AVALIAÇÕES REGISTRADAS -->
            <h2 class="text-lg font-semibold text-gray-800 mb-6 mt-10">Avaliações Registradas</h2>

            @if ($feedbacksRegistrados->isEmpty())
                <div class="bg-white border border-gray-300 rounded px-6 py-8 text-center">
                    <p class="text-gray-600">Nenhuma avaliação registrada ainda.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($feedbacksRegistrados as $feedback)
                        <div class="bg-white border border-gray-300 rounded p-6 shadow-sm">
                            <!-- Cabeçalho do card -->
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                    {{ $feedback->chamado->tipoProblema->categoria ?? 'Sem categoria' }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($feedback->chamado->data_abertura)->format('d/m/Y') }} - 
                                    @if($feedback->chamado->data_conclusao)
                                        {{ \Carbon\Carbon::parse($feedback->chamado->data_conclusao)->format('d/m/Y') }}
                                    @else
                                        Em andamento
                                    @endif
                                </p>
                            </div>

                            <!-- Avaliação em estrelas -->
                            <div class="mb-4 flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $feedback->nota)
                                        <span class="text-senai-red text-xl">★</span>
                                    @else
                                        <span class="text-gray-400 text-xl">☆</span>
                                    @endif
                                @endfor
                            </div>

                            <!-- Comentário -->
                            <p class="text-gray-700 text-sm leading-relaxed">
                                {{ $feedback->comentario ?? 'Sem comentário' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
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

