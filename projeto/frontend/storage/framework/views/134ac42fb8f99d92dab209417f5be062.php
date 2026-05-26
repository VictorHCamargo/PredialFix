<?php if (isset($component)) { $__componentOriginal7d2b2b5001884c9669f905ee887f65ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d2b2b5001884c9669f905ee887f65ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.base-layout','data' => ['tittle' => 'Dashboard']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.base-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tittle' => 'Dashboard']); ?>
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

    <main class="mx-auto w-full max-w-5xl flex-1 px-6 py-8">
        <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4">
            <?php
                $stats = [
                    ['label' => 'Chamados Feitos', 'value' => $totalChamados ?? 0],
                    ['label' => 'Chamados em Andamento', 'value' => $emAndamento ?? 0],
                    ['label' => 'Chamados Concluídos', 'value' => $concluidos ?? 0],
                    ['label' => 'Chamados Cancelados', 'value' => $cancelados ?? 0],
                ];
            ?>

            <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 rounded bg-white px-4 py-4 shadow">
                    <div
                        class="bg-senai-red flex h-10 w-10 flex-shrink-0 items-center justify-center rounded"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs leading-tight text-gray-500"><?php echo e($stat['label']); ?></p>
                        <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($stat['value']); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mb-8 rounded bg-white px-6 py-5 shadow">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Chamados Recentes</h2>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border border-gray-200 bg-gray-50">
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Tipo
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Descrição
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Local
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Data de Abertura
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Status
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 font-semibold text-gray-700"
                            >
                                Data de Término
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $chamadosRecentes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chamado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border border-gray-200 transition hover:bg-gray-50">
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    <?php echo e($chamado->tipoProblema->nome ??
                                            '—'); ?>

                                </td>
                                <td
                                    class="max-w-[160px] truncate border border-gray-200 px-3 py-2 text-gray-700"
                                    title="<?php echo e($chamado->descricao); ?>"
                                >
                                    <?php echo e(Str::limit(
                                            $chamado->descricao,
                                            20,
                                        )); ?>

                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    <?php echo e($chamado->local->nome ?? '—'); ?>

                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    <?php echo e($chamado->data_abertura
                                            ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y')
                                            : '—'); ?>

                                </td>
                                <td class="border border-gray-200 px-3 py-2">
                                    <?php
                                        $statusMap = [
                                            'aberto' => ['label' => 'Aberto', 'class' => 'text-blue-600'],
                                            'em_andamento' => ['label' => 'Em Andamento', 'class' => 'text-yellow-600'],
                                            'concluido' => ['label' => 'Concluído', 'class' => 'text-green-600'],
                                            'cancelado' => ['label' => 'Cancelado', 'class' => 'text-red-600'],
                                        ];
                                        $s = $statusMap[$chamado->status] ?? ['label' => $chamado->status, 'class' => 'text-gray-600'];
                                    ?>
                                    <span
                                        class="<?php echo e($s['class']); ?> font-medium"
                                        ><?php echo e($s['label']); ?></span
                                    >
                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">
                                    <?php echo e($chamado->data_conclusao
                                            ? \Carbon\Carbon::parse($chamado->data_conclusao)->format('d/m/Y')
                                            : '—'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td
                                    colspan="6"
                                    class="border border-gray-200 px-3 py-6 text-center text-gray-400"
                                >
                                    Nenhum chamado encontrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (! (auth()->user()->isAluno())): ?>
        <div class="flex justify-center">
            <a
                href="<?php echo e(route('chamados.create')); ?>"
                class="bg-senai-red rounded-full px-10 py-4 text-base font-bold text-white shadow-lg transition duration-200 hover:bg-red-700 active:scale-95"
            >
                Relatar novo Problema
            </a>
        </div>
        <?php endif; ?>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d2b2b5001884c9669f905ee887f65ae)): ?>
<?php $attributes = $__attributesOriginal7d2b2b5001884c9669f905ee887f65ae; ?>
<?php unset($__attributesOriginal7d2b2b5001884c9669f905ee887f65ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d2b2b5001884c9669f905ee887f65ae)): ?>
<?php $component = $__componentOriginal7d2b2b5001884c9669f905ee887f65ae; ?>
<?php unset($__componentOriginal7d2b2b5001884c9669f905ee887f65ae); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/dashboard.blade.php ENDPATH**/ ?>