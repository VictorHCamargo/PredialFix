<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciar Chamados - PredialFix SENAI</title>
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
        <?php if(session('success')): ?>
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="mb-6 rounded border border-blue-400 bg-blue-100 px-4 py-3 text-blue-700">
                <?php echo e(session('info')); ?>

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
            $total = $chamados->total();
            $perPage = $chamados->perPage();
            $emAndamentoCount = $statusCounts['em_andamento'] ?? 0;
            $concluidosCount = $statusCounts['concluido'] ?? 0;
            $canceladosCount = $statusCounts['cancelado'] ?? 0;
            $podeCancelarChamados = auth()->user()->isAdmin() || auth()->user()->isEquipeManutencao();
        ?>

        <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="rounded border border-gray-200 bg-white p-4 shadow">
                <p class="text-xs text-gray-500">Total de chamados</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($total); ?></p>
            </div>
            <div class="rounded border border-gray-200 bg-white p-4 shadow">
                <p class="text-xs text-gray-500">Em andamento</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($emAndamentoCount); ?></p>
            </div>
            <div class="rounded border border-gray-200 bg-white p-4 shadow">
                <p class="text-xs text-gray-500">Concluidos</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($concluidosCount); ?></p>
            </div>
            <div class="rounded border border-gray-200 bg-white p-4 shadow">
                <p class="text-xs text-gray-500">Cancelados</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($canceladosCount); ?></p>
            </div>
        </div>

        <div class="mb-8 overflow-hidden rounded border border-gray-200 bg-white">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-4">
                <form method="GET" action="<?php echo e(route('chamados.index')); ?>" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Status</label>
                        <select name="status" class="rounded border border-gray-300 px-3 py-2 text-sm">
                            <option value="">Todos</option>
                            <option value="aberto" <?php if(request('status') === 'aberto'): echo 'selected'; endif; ?>>Aberto</option>
                            <option value="em_andamento" <?php if(request('status') === 'em_andamento'): echo 'selected'; endif; ?>>Em andamento</option>
                            <option value="concluido" <?php if(request('status') === 'concluido'): echo 'selected'; endif; ?>>Concluido</option>
                            <option value="cancelado" <?php if(request('status') === 'cancelado'): echo 'selected'; endif; ?>>Cancelado</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Prioridade</label>
                        <select name="prioridade" class="rounded border border-gray-300 px-3 py-2 text-sm">
                            <option value="">Todas</option>
                            <option value="alta" <?php if(request('prioridade') === 'alta'): echo 'selected'; endif; ?>>Alta</option>
                            <option value="media" <?php if(request('prioridade') === 'media'): echo 'selected'; endif; ?>>Media</option>
                            <option value="baixa" <?php if(request('prioridade') === 'baixa'): echo 'selected'; endif; ?>>Baixa</option>
                        </select>
                    </div>

                    <button type="submit" class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Filtrar
                    </button>

                    <a href="<?php echo e(route('chamados.index')); ?>" class="text-sm font-medium text-gray-600 hover:text-gray-800">
                        Limpar
                    </a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-white">
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Solicitante</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Descricao</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Local</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Abertura</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Prioridade</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $chamados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chamado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3 text-xs text-gray-700">
                                    <div class="font-semibold"><?php echo e($chamado->usuario->nome ?? 'Desconhecido'); ?></div>
                                    <div class="text-gray-500"><?php echo e($chamado->usuario->email ?? '-'); ?></div>
                                    <div class="text-gray-500">ID: <?php echo e($chamado->usuario->id_usuario ?? '-'); ?></div>
                                    <?php if($chamado->usuario?->cod_entrada): ?>
                                        <div class="text-gray-500">Cracha: <?php echo e($chamado->usuario->cod_entrada); ?></div>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3 text-xs text-gray-700" title="<?php echo e($chamado->descricao); ?>">
                                    <?php echo e(\Illuminate\Support\Str::limit($chamado->descricao, 60)); ?>

                                </td>

                                <td class="px-4 py-3 text-xs text-gray-700">
                                    <?php echo e($chamado->local->sala_setor ?? '—'); ?> <?php echo e($chamado->local->bloco ? '- Bloco ' . $chamado->local->bloco : ''); ?>

                                </td>

                                <td class="px-4 py-3 text-xs text-gray-700">
                                    <?php echo e($chamado->data_abertura ? $chamado->data_abertura->format('d/m/Y H:i') : '—'); ?>

                                </td>

                                <td class="px-4 py-3 text-xs">
                                    <?php if($chamado->prioridade): ?>
                                        <?php
                                            $priorityColors = [
                                                'alta' => 'bg-red-100 text-red-700',
                                                'media' => 'bg-yellow-100 text-yellow-700',
                                                'baixa' => 'bg-green-100 text-green-700',
                                            ];
                                        ?>
                                        <span class="inline-block rounded px-2 py-1 font-semibold <?php echo e($priorityColors[$chamado->prioridade] ?? ''); ?>">
                                            <?php echo e(ucfirst($chamado->prioridade)); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-500">—</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3 text-xs">
                                    <?php
                                        $statusColors = [
                                            'aberto' => 'bg-blue-100 text-blue-700',
                                            'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                            'concluido' => 'bg-green-100 text-green-700',
                                            'cancelado' => 'bg-red-100 text-red-700',
                                        ];
                                    ?>
                                    <span class="inline-block rounded px-2 py-1 font-semibold <?php echo e($statusColors[$chamado->status] ?? ''); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $chamado->status))); ?>

                                    </span>
                                </td>

                                <td class="px-4 py-3 text-xs">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="<?php echo e(route('chamados.show', $chamado->id_chamado)); ?>" class="rounded bg-blue-600 px-3 py-1 font-semibold text-white hover:bg-blue-700">
                                            Ver
                                        </a>

                                        <?php if(auth()->user()->canRateTicket($chamado)): ?>
                                            <a href="<?php echo e(route('avaliar.create', $chamado->id_chamado)); ?>" class="rounded bg-purple-600 px-3 py-1 font-semibold text-white hover:bg-purple-700">
                                                Avaliar
                                            </a>
                                        <?php endif; ?>

                                        <?php if($podeCancelarChamados && $chamado->status !== 'cancelado'): ?>
                                            <button type="button" onclick="openCancelModal('<?php echo e($chamado->id_chamado); ?>')" class="rounded bg-red-600 px-3 py-1 font-semibold text-white hover:bg-red-700">
                                                Cancelar
                                            </button>

                                            <div id="cancelModal-<?php echo e($chamado->id_chamado); ?>" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
                                                <div class="w-full max-w-md rounded-lg bg-white text-left shadow-lg">
                                                    <div class="border-b border-gray-200 px-6 py-4">
                                                        <h3 class="text-lg font-semibold text-red-600">Cancelar chamado #<?php echo e($chamado->id_chamado); ?></h3>
                                                    </div>

                                                    <form method="POST" action="<?php echo e(route('chamados.destroy', $chamado->id_chamado)); ?>" class="space-y-4 px-6 py-5">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>

                                                        <div>
                                                            <label for="justificativa_cancelamento_<?php echo e($chamado->id_chamado); ?>" class="mb-2 block text-sm font-medium text-gray-700">
                                                                Justificativa obrigatoria
                                                            </label>
                                                            <textarea
                                                                id="justificativa_cancelamento_<?php echo e($chamado->id_chamado); ?>"
                                                                name="justificativa_cancelamento"
                                                                rows="5"
                                                                required
                                                                minlength="10"
                                                                class="w-full rounded border border-gray-300 px-4 py-2 text-sm"
                                                                placeholder="Explique o motivo do cancelamento..."
                                                            ></textarea>
                                                        </div>

                                                        <div class="flex gap-3 border-t border-gray-200 pt-4">
                                                            <button type="button" onclick="closeCancelModal('<?php echo e($chamado->id_chamado); ?>')" class="flex-1 rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300">
                                                                Voltar
                                                            </button>
                                                            <button type="submit" class="flex-1 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                                                Confirmar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                                    Nenhum chamado encontrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($chamados->hasPages()): ?>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 bg-gray-50 px-4 py-3 text-sm">
                    <div class="text-gray-600">
                        Mostrando <?php echo e($chamados->firstItem() ?? 0); ?> a <?php echo e($chamados->lastItem() ?? 0); ?> de <?php echo e($chamados->total()); ?> chamados
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <?php echo e($chamados->links()); ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex justify-center">
            <a href="<?php echo e(route('chamados.create')); ?>" class="rounded-full bg-red-600 px-8 py-4 text-sm font-bold text-white hover:bg-red-700">
                Relatar novo problema
            </a>
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

    <script>
        function openCancelModal(id) {
            const modal = document.getElementById(`cancelModal-${id}`);
            modal?.classList.remove('hidden');
            modal?.classList.add('flex');
        }

        function closeCancelModal(id) {
            const modal = document.getElementById(`cancelModal-${id}`);
            modal?.classList.add('hidden');
            modal?.classList.remove('flex');
        }

        document.querySelectorAll('[id^="cancelModal-"]').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeCancelModal(modal.id.replace('cancelModal-', ''));
                }
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/chamados/index.blade.php ENDPATH**/ ?>