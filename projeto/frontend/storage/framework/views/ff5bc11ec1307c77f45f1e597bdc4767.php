<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Avaliar Chamado – PredialFix SENAI</title>
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
        <div class="mx-auto max-w-2xl">
            <h1 class="mb-8 text-2xl font-semibold text-gray-800">Avaliar Chamado</h1>

            <!-- Card com informações do chamado -->
            <div class="mb-6 rounded border border-gray-300 bg-white p-6">
                <div class="mb-6">
                    <h2 class="mb-2 text-lg font-semibold text-gray-800">
                        <?php echo e($chamado->tipoProblema->categoria ??
                                'Sem categoria'); ?>

                    </h2>
                    <p class="mb-4 text-sm text-gray-600"><?php echo e($chamado->descricao); ?></p>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-semibold">Local:</span>
                            <?php echo e($chamado->local->sala_setor); ?> - Bloco <?php echo e($chamado->local->bloco); ?>

                        </div>
                        <div>
                            <span class="font-semibold">Prioridade:</span>
                            <span class="capitalize"><?php echo e($chamado->prioridade); ?></span>
                        </div>
                        <div>
                            <span class="font-semibold">Abertura:</span>
                            <?php echo e(\Carbon\Carbon::parse($chamado->data_abertura)->format(
                                    'd/m/Y',
                                )); ?>

                        </div>
                        <div>
                            <span class="font-semibold">Conclusão:</span>
                            <?php echo e($chamado->data_conclusao
                                    ? \Carbon\Carbon::parse($chamado->data_conclusao)->format('d/m/Y')
                                    : '—'); ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de avaliação -->
            <div class="rounded border border-gray-300 bg-white p-6">
                <h3 class="mb-6 text-lg font-semibold text-gray-800">Sua Avaliação</h3>

                <?php if($errors->any()): ?>
                    <div
                        class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700"
                    >
                        <ul class="list-disc space-y-1 pl-4 text-sm">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('avaliar.store')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    <!-- Campo oculto com o ID do chamado -->
                    <input type="hidden" name="id_chamado" value="<?php echo e($chamado->id_chamado); ?>" />

                    <!-- Nota em Estrelas -->
                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">
                            Qual é sua avaliação? *
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
                                        class="hover:text-senai-red star text-5xl text-gray-400 transition"
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
                            <span class="mt-2 block text-xs text-red-600"><?php echo e($message); ?></span>
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
                            Comentário (opcional)
                        </label>
                        <textarea
                            id="comentario"
                            name="comentario"
                            placeholder="Deixe um comentário sobre o atendimento..."
                            class="focus:ring-senai-red w-full resize-none rounded border border-gray-400 px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2"
                            rows="5"
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

                    <!-- Botões -->
                    <div class="flex gap-3 pt-3">
                        <button
                            type="submit"
                            class="bg-senai-red rounded px-8 py-3 text-sm font-bold text-white transition duration-200 hover:bg-red-700 focus:outline-none active:scale-95"
                        >
                            Enviar Avaliação
                        </button>
                        <a
                            href="<?php echo e(route('chamados.index')); ?>"
                            class="rounded border border-gray-300 px-8 py-3 text-sm font-semibold text-gray-600 transition hover:text-gray-800"
                        >
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
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/feedbacks/create.blade.php ENDPATH**/ ?>