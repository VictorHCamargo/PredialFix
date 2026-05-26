<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Chamado – PredialFix SENAI</title>
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
    <main class="mx-auto w-full max-w-2xl flex-1 px-6 py-8">
        <h1 class="mb-6 text-lg font-semibold text-gray-800">
            Editar Chamado #<?php echo e($chamado->id_chamado); ?>

        </h1>

        <?php if($errors->any()): ?>
            <div
                class="mb-5 rounded border border-red-300 bg-red-100 px-4 py-3 text-xs text-red-700"
            >
                <ul class="list-disc space-y-1 pl-4">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form
            method="POST"
            action="<?php echo e(route('chamados.update', $chamado->id_chamado)); ?>"
            enctype="multipart/form-data"
            class="flex flex-col gap-5"
        >
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Descrição do Problema -->
            <div class="flex flex-col gap-2">
                <label for="descricao" class="text-sm font-semibold text-gray-800">
                    Descrição do Problema *
                </label>
                <textarea
                    id="descricao"
                    name="descricao"
                    required
                    placeholder="Descreva em detalhes o problema encontrado"
                    class="focus:ring-senai-red w-full resize-none rounded border border-gray-400 px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    rows="4"
                    ><?php echo e(old(
                            'descricao',
                            $chamado->descricao,
                        )); ?></textarea
                >
                <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-xs text-red-600"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Tipo de Chamado -->
            <div class="flex flex-col gap-2">
                <label for="tipo_chamado" class="text-sm font-semibold text-gray-800">
                    Tipo de Chamado *
                </label>
                <div class="relative w-56">
                    <select
                        id="tipo_chamado"
                        name="tipo_chamado"
                        required
                        class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    >
                        <option value="" disabled>Selecione</option>
                        <option
                            value="interno"
                            <?php echo e(old('tipo_chamado', $chamado->tipo_chamado) === 'interno'
                                    ? 'selected'
                                    : ''); ?>

                            >Interno
                        </option>
                        <option
                            value="externo"
                            <?php echo e(old('tipo_chamado', $chamado->tipo_chamado) === 'externo'
                                    ? 'selected'
                                    : ''); ?>

                            >Externo
                        </option>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                        >▼</span
                    >
                </div>
                <?php $__errorArgs = ['tipo_chamado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-xs text-red-600"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Tipo de Incidente -->
            <div class="flex flex-col gap-2">
                <label for="id_tipo" class="text-sm font-semibold text-gray-800">
                    Tipo de Incidente:
                </label>
                <div class="relative w-56">
                    <select
                        id="id_tipo"
                        name="id_tipo"
                        required
                        class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    >
                        <option value="" disabled>Selecione</option>
                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($tipo->id_tipo); ?>"
                                <?php echo e(old('id_tipo', $chamado->id_tipo) == $tipo->id_tipo
                                        ? 'selected'
                                        : ''); ?>

                            >
                                <?php echo e($tipo->categoria); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                        >▼</span
                    >
                </div>
            </div>

            <!-- Local -->
            <div class="flex flex-col gap-2">
                <label for="id_local" class="text-sm font-semibold text-gray-800"> Local </label>
                <div class="relative w-56">
                    <select
                        id="id_local"
                        name="id_local"
                        required
                        class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    >
                        <option value="" disabled>Selecione</option>
                        <?php $__currentLoopData = $locais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $local): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($local->id_local); ?>"
                                <?php echo e(old('id_local', $chamado->id_local) == $local->id_local
                                        ? 'selected'
                                        : ''); ?>

                            >
                                <?php echo e($local->sala_setor); ?> - Bloco <?php echo e($local->bloco); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                        >▼</span
                    >
                </div>
            </div>

            <!-- Equipamento -->
            <div class="flex flex-col gap-2">
                <label for="id_equipamento" class="text-sm font-semibold text-gray-800">
                    Equipamento
                </label>
                <div class="relative w-56">
                    <select
                        id="id_equipamento"
                        name="id_equipamento"
                        class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    >
                        <option value="">Selecione</option>
                        <?php $__currentLoopData = $equipamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($equipamento->id_equipamento); ?>"
                                <?php echo e(old('id_equipamento', $chamado->id_equipamento) ==
                                    $equipamento->id_equipamento
                                        ? 'selected'
                                        : ''); ?>

                            >
                                <?php echo e($equipamento->nome); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                        >▼</span
                    >
                </div>
            </div>

            <!-- Botões -->
            <div class="flex gap-3 pt-3">
                <button
                    type="submit"
                    class="bg-senai-red focus:ring-senai-red rounded px-8 py-3 text-sm font-bold text-white transition duration-200 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-95"
                >
                    Salvar Alterações
                </button>
                <a
                    href="<?php echo e(route('chamados.show', $chamado->id_chamado)); ?>"
                    class="rounded bg-gray-600 px-8 py-3 text-sm font-bold text-white transition duration-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 active:scale-95"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </main>

    <!-- Rodapé -->
    <footer class="bg-senai-red mt-8">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-8 px-6 py-8 md:grid-cols-2">
            <div class="text-white">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide">Edifício Sede FIESP</h3>
                <p class="text-sm leading-relaxed text-red-100">Av. Paulista, 1313, São Paulo/SP<br />CEP 01311-923</p>
            </div>
            <div class="text-white">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide">
                    Central de Relacionamento
                </h3>
                <p class="text-sm leading-relaxed text-red-100">(11) 3322-0050 (Telefone/WhatsApp)<br />
                0800-055-1000 (Interior de SP,<br />somente telefone fixo)</p>
            </div>
        </div>
        <div class="bg-red-900 py-3 text-center text-xs text-red-200">
            Copyright 2026 &copy; Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/chamados/edit.blade.php ENDPATH**/ ?>