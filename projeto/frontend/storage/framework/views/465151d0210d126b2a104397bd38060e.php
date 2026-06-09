<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Usuario - PredialFix SENAI</title>
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

    <main class="mx-auto w-full max-w-3xl flex-1 px-6 py-8">
        <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
            <div>
                <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Voltar para usuarios</a>
                <h1 class="mt-2 text-2xl font-bold text-gray-800">Editar funcionario</h1>
                <p class="text-sm text-gray-500">ID: <?php echo e($usuario->id_usuario); ?></p>
            </div>

            <form method="POST" action="<?php echo e(route('admin.usuarios.toggle', $usuario->id_usuario)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <button type="submit" class="rounded bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    <?php echo e($usuario->ativo ? 'Desativar' : 'Ativar'); ?>

                </button>
            </form>
        </div>

        <?php if($errors->any()): ?>
            <div class="mb-6 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.usuarios.update', $usuario->id_usuario)); ?>" class="space-y-5 rounded border border-gray-200 bg-white p-6 shadow">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="nome" class="mb-1 block text-sm font-semibold text-gray-800">Nome</label>
                    <input id="nome" name="nome" type="text" value="<?php echo e(old('nome', $usuario->nome)); ?>" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-gray-800">E-mail</label>
                    <input id="email" name="email" type="email" value="<?php echo e(old('email', $usuario->email)); ?>" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="senha" class="mb-1 block text-sm font-semibold text-gray-800">Nova senha</label>
                    <input id="senha" name="senha" type="password" minlength="8" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="nivel_acesso" class="mb-1 block text-sm font-semibold text-gray-800">Nivel de acesso</label>
                    <select id="nivel_acesso" name="nivel_acesso" required class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="administrador" <?php if(old('nivel_acesso', $usuario->nivel_acesso) === 'administrador'): echo 'selected'; endif; ?>>Administrador</option>
                        <option value="tecnico_manutencao" <?php if(old('nivel_acesso', $usuario->nivel_acesso) === 'tecnico_manutencao'): echo 'selected'; endif; ?>>Tecnico de manutencao</option>
                        <option value="professor" <?php if(old('nivel_acesso', $usuario->nivel_acesso) === 'professor'): echo 'selected'; endif; ?>>Professor</option>
                    </select>
                </div>

                <div>
                    <label for="cod_entrada" class="mb-1 block text-sm font-semibold text-gray-800">Cracha</label>
                    <input id="cod_entrada" name="cod_entrada" type="number" value="<?php echo e(old('cod_entrada', $usuario->cod_entrada)); ?>" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>

                <div>
                    <label for="setor" class="mb-1 block text-sm font-semibold text-gray-800">Setor</label>
                    <input id="setor" name="setor" type="text" value="<?php echo e(old('setor', $usuario->setor)); ?>" class="w-full rounded border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">
                    Salvar alteracoes
                </button>
                <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="rounded border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
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
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/admin/usuarios/edit.blade.php ENDPATH**/ ?>