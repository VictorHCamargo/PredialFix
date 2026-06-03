<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Usuarios - PredialFix SENAI</title>
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

    <main class="mx-auto w-full max-w-7xl flex-1 px-6 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Usuarios</h1>
                <p class="text-sm text-gray-500">Cadastro e status de funcionarios.</p>
            </div>

            <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                Novo funcionario
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="mb-6 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php
            $nivelLabels = [
                'administrador' => 'Administrador',
                'gerente_manutencao' => 'Gerente de manutencao',
                'tecnico_manutencao' => 'Tecnico de manutencao',
                'professor' => 'Professor',
                'aluno' => 'Aluno',
            ];
        ?>

        <div class="overflow-hidden rounded border border-gray-200 bg-white shadow">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Nome</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">E-mail</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Nivel</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Setor</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Cracha</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-800"><?php echo e($usuario->nome); ?></div>
                                    <div class="text-xs text-gray-500">ID: <?php echo e($usuario->id_usuario); ?></div>
                                </td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($usuario->email); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($nivelLabels[$usuario->nivel_acesso] ?? $usuario->nivel_acesso); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($usuario->setor ?? '-'); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($usuario->cod_entrada ?? '-'); ?></td>
                                <td class="px-4 py-3">
                                    <?php if($usuario->ativo): ?>
                                        <span class="rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">Ativo</span>
                                    <?php else: ?>
                                        <span class="rounded bg-gray-200 px-2 py-1 text-xs font-semibold text-gray-700">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="<?php echo e(route('admin.usuarios.edit', $usuario->id_usuario)); ?>" class="rounded bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-700">
                                            Editar
                                        </a>

                                        <form method="POST" action="<?php echo e(route('admin.usuarios.toggle', $usuario->id_usuario)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="rounded bg-gray-700 px-3 py-1 text-xs font-semibold text-white hover:bg-gray-800">
                                                <?php echo e($usuario->ativo ? 'Desativar' : 'Ativar'); ?>

                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Nenhum usuario cadastrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($usuarios->hasPages()): ?>
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
                    <?php echo e($usuarios->links()); ?>

                </div>
            <?php endif; ?>
        </div>
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
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/admin/usuarios/index.blade.php ENDPATH**/ ?>