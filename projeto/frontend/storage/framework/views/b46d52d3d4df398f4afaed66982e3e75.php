<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Avaliações – PredialFix SENAI</title>
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
    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

    <!-- Conteúdo -->
    <main class="flex-1 px-6 py-8">
        <div class="mx-auto max-w-4xl">
            <h1 class="mb-8 text-2xl font-semibold text-gray-800">Avaliações</h1>

            <!-- Mensagem de sucesso -->
            <?php if(session('success')): ?>
                <div
                    class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700"
                >
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- SEÇÃO 1: CRIAR NOVA AVALIAÇÃO -->
            <div class="mb-8 rounded bg-white p-6 shadow">
                <h2 class="mb-6 text-lg font-semibold text-gray-800">Criar Nova Avaliação</h2>

                <?php if($chamadosParaAvaliar->isEmpty()): ?>
                    <div
                        class="mb-6 rounded border border-blue-300 bg-blue-50 px-4 py-3 text-sm text-blue-700"
                    >
                        Nenhum chamado concluído aguardando avaliação no momento.
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('avaliar.store')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    <!-- Seleção do Chamado -->
                    <div>
                        <label
                            for="id_chamado"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Selecione um Chamado *
                        </label>
                        <select
                            id="id_chamado"
                            name="id_chamado"
                            required
                            class="focus:ring-senai-red w-full rounded border border-gray-400 px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2"
                        >
                            <option value="" disabled selected>
                                -- Escolha um chamado concluído --
                            </option>
                            <?php $__currentLoopData = $chamadosParaAvaliar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chamado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($chamado->id_chamado); ?>">
                                    <?php echo e($chamado->tipoProblema->categoria); ?> - <?php echo e($chamado->descricao); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['id_chamado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="mt-1 text-xs text-red-600"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Nota em Estrelas -->
                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">
                            Avaliação *
                        </label>
                        <div class="flex gap-3" id="stars-container">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="nota"
                                        value="<?php echo e($i); ?>"
                                        class="hidden"
                                        required
                                    />
                                    <span
                                        class="hover:text-senai-red star text-4xl text-gray-400 transition"
                                        data-value="<?php echo e($i); ?>"
                                        >★</span
                                    >
                                </label>
                            <?php endfor; ?>
                        </div>
                        <?php $__errorArgs = ['nota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="mt-1 text-xs text-red-600"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Comentário -->
                    <div>
                        <label
                            for="comentario"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Comentário
                        </label>
                        <textarea
                            id="comentario"
                            name="comentario"
                            placeholder="Deixe um comentário (opcional)"
                            class="focus:ring-senai-red w-full resize-none rounded border border-gray-400 px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2"
                            rows="4"
                            ><?php echo e(old('comentario')); ?></textarea
                        >
                        <?php $__errorArgs = ['comentario'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="mt-1 text-xs text-red-600"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Botão Enviar -->
                    <div class="pt-3">
                        <button
                            type="submit"
                            class="bg-senai-red hover:bg-red-700 text-white font-bold text-sm px-8 py-2 rounded
                                       transition duration-200 active:scale-95 focus:outline-none <?php echo e($chamadosParaAvaliar->isEmpty() ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                            <?php echo e($chamadosParaAvaliar->isEmpty()
                                    ? 'disabled'
                                    : ''); ?>

                        >
                            Enviar Avaliação
                        </button>
                    </div>
                </form>
            </div>

            <!-- SEÇÃO 2: AVALIAÇÕES REGISTRADAS -->
            <h2 class="mb-6 mt-10 text-lg font-semibold text-gray-800">Avaliações Registradas</h2>

            <?php if($feedbacksRegistrados->isEmpty()): ?>
                <div class="rounded border border-gray-300 bg-white px-6 py-8 text-center">
                    <p class="text-gray-600">Nenhuma avaliação registrada ainda.</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php $__currentLoopData = $feedbacksRegistrados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded border border-gray-300 bg-white p-6 shadow-sm">
                            <!-- Cabeçalho do card -->
                            <div class="mb-4">
                                <h3 class="mb-1 text-lg font-semibold text-gray-800">
                                    <?php echo e($feedback->chamado->tipoProblema->categoria ??
                                            'Sem categoria'); ?>

                                </h3>
                                <p class="text-sm text-gray-600">
                                    <?php echo e(\Carbon\Carbon::parse($feedback->chamado->data_abertura)->format(
                                            'd/m/Y',
                                        )); ?> -
                                    <?php if($feedback->chamado->data_conclusao): ?>
                                        <?php echo e(\Carbon\Carbon::parse(
                                                $feedback->chamado->data_conclusao,
                                            )->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        Em andamento
                                    <?php endif; ?>
                                </p>
                            </div>

                            <!-- Avaliação em estrelas -->
                            <div class="mb-4 flex gap-1">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $feedback->nota): ?>
                                        <span class="text-senai-red text-xl">★</span>
                                    <?php else: ?>
                                        <span class="text-xl text-gray-400">☆</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>

                            <!-- Comentário -->
                            <p class="text-sm leading-relaxed text-gray-700">
                                <?php echo e($feedback->comentario ??
                                        'Sem comentário'); ?>

                            </p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-senai-red mt-8">
        <div class="px-6 py-6 text-white">
            <div class="mx-auto grid max-w-4xl grid-cols-2 gap-6">
                <div>
                    <p class="mb-2 font-semibold">EDIFÍCIO SEDE FIESP</p>
                    <p class="text-xs leading-relaxed">Av. Paulista, 1313, São Paulo/SP</p>
                    <p class="text-xs">CEP 0131-923</p>
                </div>
                <div>
                    <p class="mb-2 font-semibold">CENTRAL DE RELACIONAMENTO</p>
                    <p class="text-xs leading-relaxed">(11) 3322-0050 (Telefone/WhatsApp)</p>
                    <p class="text-xs">0800-055-1000 (Interior de SP, somente telefone fixo)</p>
                </div>
            </div>
            <div class="mt-4 border-t border-red-500 pt-4 text-center text-xs">
                Copyright 2026 © Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script>
        // Interatividade das estrelas
        const stars = document.querySelectorAll('.star');
        const starsContainer = document.getElementById('stars-container');
        let selectedRating = 0;

        stars.forEach((star) => {
            star.addEventListener('click', function () {
                selectedRating = this.dataset.value;
                updateStars(selectedRating);
                this.closest('label').querySelector('input[type="radio"]').checked = true;
            });

            star.addEventListener('mouseenter', function () {
                updateStars(this.dataset.value);
            });
        });

        starsContainer.addEventListener('mouseleave', function () {
            updateStars(selectedRating);
        });

        function updateStars(rating) {
            stars.forEach((star) => {
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
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/feedbacks/index.blade.php ENDPATH**/ ?>