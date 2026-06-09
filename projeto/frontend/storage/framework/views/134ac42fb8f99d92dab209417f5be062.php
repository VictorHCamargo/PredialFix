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

    <main class="mx-auto w-full max-w-6xl flex-1 px-6 py-8">
        <!-- Título da página -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Bem-vindo! Aqui está o resumo do seu sistema</p>
        </div>

        <!-- Cards de Estatísticas Principais -->
        <div class="mb-8 grid gap-4 grid-cols-2 md:grid-cols-4">
            <!-- Card: Total de Usuários -->
            <div class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow hover:shadow-md transition">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-12 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Total de Usuários</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($totalUsuarios ?? 0); ?></p>
                </div>
            </div>

            <!-- Card: Total de Chamados -->
            <div class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow hover:shadow-md transition">
                <div class="bg-senai-red flex h-10 w-10 flex-shrink-0 items-center justify-center rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Total de Chamados</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($totalChamados ?? 0); ?></p>
                </div>
            </div>

            <!-- Card: Chamados Pendentes -->
            <div class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow hover:shadow-md transition">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded bg-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Pendentes</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($chamadosPendentes ?? 0); ?></p>
                </div>
            </div>

            <!-- Card: Resolvidos Hoje -->
            <div class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow hover:shadow-md transition">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded bg-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Resolvidos Hoje</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($chamadosResolvidosHoje ?? 0); ?></p>
                </div>
            </div>
        </div>

        <!-- Grid de conteúdo secundário -->
        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Coluna da esquerda: Últimos Chamados (maior) -->
            <div class="lg:col-span-2">
                <div class="rounded border border-gray-300 bg-white overflow-hidden shadow">
                    <!-- Cabeçalho -->
                    <div class="border-b border-gray-300 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Últimos Chamados
                        </h2>
                    </div>

                    <!-- Lista de chamados -->
                    <div class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $chamadosRecentes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chamado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <p class="font-semibold text-gray-800 truncate">
                                                <?php echo e($chamado->tipoProblema?->categoria ?? '—'); ?>

                                            </p>
                                            <?php
                                                $statusMap = [
                                                    'aberto' => ['label' => 'Aberto', 'class' => 'bg-blue-100 text-blue-700'],
                                                    'em_andamento' => ['label' => 'Em Andamento', 'class' => 'bg-yellow-100 text-yellow-700'],
                                                    'concluido' => ['label' => 'Concluído', 'class' => 'bg-green-100 text-green-700'],
                                                    'cancelado' => ['label' => 'Cancelado', 'class' => 'bg-red-100 text-red-700'],
                                                ];
                                                $s = $statusMap[$chamado->status] ?? ['label' => $chamado->status, 'class' => 'bg-gray-100 text-gray-700'];
                                            ?>
                                            <span class="text-xs font-medium px-2 py-1 rounded <?php echo e($s['class']); ?>">
                                                <?php echo e($s['label']); ?>

                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            <?php echo e(Str::limit($chamado->descricao, 60)); ?>

                                        </p>
                                        <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                            <span>
                                                <strong>Local:</strong> <?php echo e($chamado->local->nome ?? '—'); ?>

                                            </span>
                                            <span>
                                                <strong>Aberto em:</strong> <?php echo e($chamado->data_abertura ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y') : '—'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="px-6 py-8 text-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Nenhum chamado encontrado.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Coluna da direita: Resumo de Status -->
            <div>
                <div class="rounded border border-gray-300 bg-white overflow-hidden shadow">
                    <!-- Cabeçalho -->
                    <div class="border-b border-gray-300 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Resumo de Status
                        </h2>
                    </div>

                    <!-- Status items -->
                    <div class="divide-y divide-gray-200">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                    <p class="text-sm font-medium text-gray-700">Em Andamento</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800"><?php echo e($emAndamento ?? 0); ?></p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: <?php echo e($totalChamados > 0 ? (($emAndamento ?? 0) / ($totalChamados ?? 1) * 100) : 0); ?>%"></div>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    <p class="text-sm font-medium text-gray-700">Concluídos</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800"><?php echo e($concluidos ?? 0); ?></p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: <?php echo e($totalChamados > 0 ? (($concluidos ?? 0) / ($totalChamados ?? 1) * 100) : 0); ?>%"></div>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <p class="text-sm font-medium text-gray-700">Cancelados</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800"><?php echo e($cancelados ?? 0); ?></p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: <?php echo e($totalChamados > 0 ? (($cancelados ?? 0) / ($totalChamados ?? 1) * 100) : 0); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão de ação -->
                <div class="mt-6">
                    <a href="<?php echo e(route('chamados.create')); ?>" class="w-full flex items-center justify-center gap-2 bg-senai-red hover:bg-red-700 text-white font-bold py-3 rounded-lg shadow-lg transition duration-200 hover:shadow-xl active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Novo Chamado
                    </a>
                </div>
            </div>
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