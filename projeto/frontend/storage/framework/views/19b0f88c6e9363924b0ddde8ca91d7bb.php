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

    <main class="mx-auto w-full max-w-3xl flex-1 px-6 py-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Relatar novo problema</h1>

        <?php if($errors->any()): ?>
            <div class="mb-5 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('chamados.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipo_chamado" value="interno" />

            <?php if(session('alerta_duplicado')): ?>
                <div class="rounded border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-900">
                    <p class="font-semibold">Ja existe um chamado ativo para este patrimonio.</p>
                    <a
                        href="<?php echo e(route('chamados.show', session('alerta_duplicado'))); ?>"
                        class="mt-1 inline-block font-semibold underline"
                    >
                        Ver chamado existente
                    </a>
                    <label class="mt-3 flex items-center gap-2">
                        <input type="checkbox" name="confirmar_duplicado" value="1" <?php echo e(old('confirmar_duplicado') ? 'checked' : ''); ?> />
                        Desejo abrir mesmo assim (problema diferente)
                    </label>
                </div>
            <?php endif; ?>

            <div>
                <label for="descricao" class="mb-1 block text-sm font-semibold text-gray-800">Descricao do problema *</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    rows="5"
                    required
                    class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="Descreva o problema com o maximo de detalhes"
                ><?php echo e(old('descricao')); ?></textarea>
            </div>

            <div>
                <label for="id_patrimonio" class="mb-1 block text-sm font-semibold text-gray-800">ID de patrimonio</label>
                <input
                    id="id_patrimonio"
                    type="text"
                    name="id_patrimonio"
                    value="<?php echo e(old('id_patrimonio')); ?>"
                    placeholder="ID do equipamento ou sala"
                    class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="id_tipo" class="mb-1 block text-sm font-semibold text-gray-800">Tipo de incidente</label>
                    <select id="id_tipo" name="id_tipo" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="" disabled <?php echo e(old('id_tipo') ? '' : 'selected'); ?>>Selecione</option>
                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tipo->id_tipo); ?>" <?php if(old('id_tipo') == $tipo->id_tipo): echo 'selected'; endif; ?>>
                                <?php echo e($tipo->categoria); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label for="id_local" class="mb-1 block text-sm font-semibold text-gray-800">Local</label>
                    <select id="id_local" name="id_local" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="" disabled <?php echo e(old('id_local') ? '' : 'selected'); ?>>Selecione</option>
                        <?php $__currentLoopData = $locais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $local): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($local->id_local); ?>" <?php if(old('id_local') == $local->id_local): echo 'selected'; endif; ?>>
                                <?php echo e($local->sala_setor); ?> - Bloco <?php echo e($local->bloco); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <?php if(Auth::user()->canManageTickets()): ?>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="secao_tecnica" class="mb-1 block text-sm font-semibold text-gray-800">Seccao tecnica</label>
                        <select id="secao_tecnica" name="secao_tecnica" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="eletrica" <?php if(old('secao_tecnica') === 'eletrica'): echo 'selected'; endif; ?>>Eletrica</option>
                            <option value="hidraulica" <?php if(old('secao_tecnica') === 'hidraulica'): echo 'selected'; endif; ?>>Hidraulica</option>
                            <option value="civil" <?php if(old('secao_tecnica') === 'civil'): echo 'selected'; endif; ?>>Civil</option>
                            <option value="mecanica" <?php if(old('secao_tecnica') === 'mecanica'): echo 'selected'; endif; ?>>Mecanica</option>
                        </select>
                    </div>

                    <div>
                        <label for="prioridade" class="mb-1 block text-sm font-semibold text-gray-800">Prioridade</label>
                        <select id="prioridade" name="prioridade" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="baixa" <?php if(old('prioridade') === 'baixa'): echo 'selected'; endif; ?>>Baixa</option>
                            <option value="media" <?php if(old('prioridade') === 'media'): echo 'selected'; endif; ?>>Media</option>
                            <option value="alta" <?php if(old('prioridade') === 'alta'): echo 'selected'; endif; ?>>Alta</option>
                        </select>
                    </div>

                    <div>
                        <label for="complexidade" class="mb-1 block text-sm font-semibold text-gray-800">Complexidade</label>
                        <select id="complexidade" name="complexidade" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="simples" <?php if(old('complexidade') === 'simples'): echo 'selected'; endif; ?>>Simples</option>
                            <option value="media" <?php if(old('complexidade') === 'media'): echo 'selected'; endif; ?>>Media</option>
                            <option value="complexa" <?php if(old('complexidade') === 'complexa'): echo 'selected'; endif; ?>>Complexa</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipo_trabalho" class="mb-1 block text-sm font-semibold text-gray-800">Tipo de trabalho</label>
                        <select id="tipo_trabalho" name="tipo_trabalho" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione</option>
                            <option value="preventiva" <?php if(old('tipo_trabalho') === 'preventiva'): echo 'selected'; endif; ?>>Preventiva</option>
                            <option value="corretiva" <?php if(old('tipo_trabalho') === 'corretiva'): echo 'selected'; endif; ?>>Corretiva</option>
                            <option value="melhoria" <?php if(old('tipo_trabalho') === 'melhoria'): echo 'selected'; endif; ?>>Melhoria</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(Auth::user()->isAluno()): ?>
                <div class="rounded border border-blue-300 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    Alunos nao definem prioridade, seccao tecnica, complexidade ou tipo de trabalho.
                </div>
            <?php endif; ?>

            <div class="pt-2">
                <button type="submit" class="rounded bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">
                    Enviar chamado
                </button>
            </div>
        </form>
    </main>

    <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/chamados/create.blade.php ENDPATH**/ ?>