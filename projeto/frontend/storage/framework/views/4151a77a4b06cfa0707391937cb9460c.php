<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciar Chamados – PredialFix SENAI</title>
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

    <main class="mx-auto w-full max-w-6xl flex-1 px-6 py-8">
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

        <?php
            $total = $chamados->total();
            $perPage = $chamados->perPage();
            
            // Contagens por status dos cards
            $emAndamentoCount = $statusCounts['em_andamento'] ?? 0;
            $concluidosCount = $statusCounts['concluido'] ?? 0;
            $canceladosCount = $statusCounts['cancelado'] ?? 0;
        ?>

        <!-- Cards de estatísticas -->
        <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4">
            <div
                class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow"
            >
                <div
                    class="bg-senai-red flex h-10 w-10 flex-shrink-0 items-center justify-center rounded"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Total de Chamados</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($total); ?></p>
                </div>
            </div>

            <div
                class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow"
            >
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded bg-yellow-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Em Andamento</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($emAndamentoCount); ?></p>
                </div>
            </div>

            <div
                class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow"
            >
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded bg-green-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Concluídos</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($concluidosCount); ?></p>
                </div>
            </div>

            <div
                class="flex items-center gap-3 rounded border border-gray-200 bg-white px-4 py-4 shadow"
            >
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded bg-red-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs leading-tight text-gray-500">Cancelados</p>
                    <p class="text-2xl font-bold leading-tight text-gray-800"><?php echo e($canceladosCount); ?></p>
                </div>
            </div>
        </div>

        <!-- Tabela de Chamados -->
        <div class="mb-8 overflow-hidden rounded border border-gray-300 bg-white">
            <!-- Barra de filtro -->
            <div class="border-b border-gray-300 bg-gray-50 px-4 py-3">
                <form
                    method="GET"
                    action="<?php echo e(route('chamados.index')); ?>"
                    class="flex flex-wrap items-end gap-3"
                >
                    <div class="flex flex-wrap items-end gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700"
                                >Status</label
                            >
                            <select
                                name="status"
                                class="rounded border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                                <option value="">Todos os status</option>
                                <option value="aberto" <?php if(request('status') === 'aberto'): echo 'selected'; endif; ?>
                                    >Aberto
                                </option>
                                <option
                                    value="em_andamento"
                                    <?php if(request('status') === 'em_andamento'): echo 'selected'; endif; ?>
                                    >Em Andamento
                                </option>
                                <option
                                    value="concluido"
                                    <?php if(request('status') === 'concluido'): echo 'selected'; endif; ?>
                                    >Concluído
                                </option>
                                <option
                                    value="cancelado"
                                    <?php if(request('status') === 'cancelado'): echo 'selected'; endif; ?>
                                    >Cancelado
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700">Tipo</label>
                            <select
                                name="tipo_chamado"
                                class="rounded border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                                <option value="">Todos os tipos</option>
                                <option
                                    value="interno"
                                    <?php if(request('tipo_chamado') === 'interno'): echo 'selected'; endif; ?>
                                    >Interno
                                </option>
                                <option
                                    value="externo"
                                    <?php if(request('tipo_chamado') === 'externo'): echo 'selected'; endif; ?>
                                    >Externo
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700"
                                >Prioridade</label
                            >
                            <select
                                name="prioridade"
                                class="rounded border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                                <option value="">Todas as prioridades</option>
                                <option value="alta" <?php if(request('prioridade') === 'alta'): echo 'selected'; endif; ?>
                                    >Alta
                                </option>
                                <option value="media" <?php if(request('prioridade') === 'media'): echo 'selected'; endif; ?>
                                    >Média
                                </option>
                                <option value="baixa" <?php if(request('prioridade') === 'baixa'): echo 'selected'; endif; ?>
                                    >Baixa
                                </option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                        >
                            Filtrar
                        </button>

                        <a
                            href="<?php echo e(route('chamados.index')); ?>"
                            class="text-sm font-medium text-gray-600 hover:text-gray-800"
                        >
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-300 bg-white">
                            <th
                                class="border-r border-gray-300 px-4 py-3 text-xs font-semibold text-gray-700"
                            >
                                Tipo
                            </th>
                            <th
                                class="border-r border-gray-300 px-4 py-3 text-xs font-semibold text-gray-700"
                            >
                                Descrição
                            </th>
                            <th
                                class="border-r border-gray-300 px-4 py-3 text-xs font-semibold text-gray-700"
                            >
                                Local
                            </th>
                            <th
                                class="border-r border-gray-300 px-4 py-3 text-xs font-semibold text-gray-700"
                            >
                                Abertura
                            </th>
                            <th
                                class="border-r border-gray-300 px-4 py-3 text-xs font-semibold text-gray-700"
                            >
                                Prioridade
                            </th>
                            <th
                                class="border-r border-gray-300 px-4 py-3 text-xs font-semibold text-gray-700"
                            >
                                Status
                            </th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $chamados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chamado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-200 transition hover:bg-gray-50">
                                <td
                                    class="border-r border-gray-300 px-4 py-3 text-xs text-gray-700"
                                >
                                    <span
                                        class="inline-block rounded bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700"
                                    >
                                        <?php echo e(ucfirst(
                                                str_replace('_', ' ', $chamado->tipo_chamado),
                                            )); ?>

                                    </span>
                                </td>

                                <td
                                    class="max-w-[180px] truncate border-r border-gray-300 px-4 py-3 text-xs text-gray-700"
                                    title="<?php echo e($chamado->descricao); ?>"
                                >
                                    <?php echo e(Str::limit(
                                            $chamado->descricao,
                                            25,
                                        )); ?>

                                </td>

                                <td
                                    class="border-r border-gray-300 px-4 py-3 text-xs text-gray-700"
                                >
                                    <?php echo e($chamado->local->sala_setor ?? '—'); ?> - <?php echo e($chamado->local->bloco ?? ''); ?>

                                </td>

                                <td
                                    class="border-r border-gray-300 px-4 py-3 text-xs text-gray-700"
                                >
                                    <?php echo e($chamado->data_abertura
                                            ? \Carbon\Carbon::parse($chamado->data_abertura)->format('d/m/Y')
                                            : '—'); ?>

                                </td>

                                <td class="border-r border-gray-300 px-4 py-3 text-xs">
                                    <?php if($chamado->prioridade): ?>
                                        <?php
                                            $priorityColors = [
                                                'alta' => 'bg-red-100 text-red-700',
                                                'media' => 'bg-yellow-100 text-yellow-700',
                                                'baixa' => 'bg-green-100 text-green-700',
                                            ];
                                        ?>
                                        <span
                                            class="inline-block <?php echo e($priorityColors[$chamado->prioridade] ?? ''); ?> px-2 py-1 rounded font-semibold"
                                        >
                                            <?php echo e(ucfirst($chamado->prioridade)); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-500">—</span>
                                    <?php endif; ?>
                                </td>

                                <td class="border-r border-gray-300 px-4 py-3 text-xs">
                                    <?php
                                        $statusColors = [
                                            'aberto' => 'bg-blue-100 text-blue-700',
                                            'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                            'concluido' => 'bg-green-100 text-green-700',
                                            'cancelado' => 'bg-red-100 text-red-700',
                                        ];
                                    ?>
                                    <span
                                        class="inline-block <?php echo e($statusColors[$chamado->status] ?? ''); ?> px-2 py-1 rounded font-semibold"
                                    >
                                        <?php echo e(ucfirst(
                                                str_replace('_', ' ', $chamado->status),
                                            )); ?>

                                    </span>
                                </td>

                                <td class="space-y-1 px-4 py-3 text-xs">
                                    <div class="flex flex-wrap gap-1">
                                        <a
                                            href="<?php echo e(route('chamados.show', $chamado->id_chamado)); ?>"
                                            class="rounded bg-blue-600 px-2 py-1 text-xs font-semibold text-white transition hover:bg-blue-700"
                                        >
                                            Ver
                                        </a>

                                        <?php if(!auth()->user()->isAluno() && $chamado->status === 'concluido' && !$chamado->feedback && auth()->user()->temCodigoEntrada()): ?>
                                            <a
                                                href="<?php echo e(route('avaliar.create', $chamado->id_chamado)); ?>"
                                                class="rounded bg-purple-600 px-2 py-1 text-xs font-semibold text-white transition hover:bg-purple-700"
                                            >
                                                Avaliar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td
                                    colspan="7"
                                    class="border-b border-gray-200 px-4 py-6 text-center text-sm text-gray-400"
                                >
                                    Nenhum chamado encontrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if($chamados->hasPages()): ?>
                <div
                    class="flex items-center justify-between border-t border-gray-300 bg-gray-50 px-4 py-3 text-sm"
                >
                    <div class="text-gray-600">
                        Mostrando <?php echo e($chamados->firstItem() ?? 0); ?> a <?php echo e($chamados->lastItem() ?? 0); ?> de <?php echo e($chamados->total()); ?> chamados
                    </div>
                    <div class="flex gap-1">
                        <?php if($chamados->onFirstPage()): ?>
                            <span
                                class="cursor-not-allowed rounded border border-gray-300 px-3 py-1 text-gray-400"
                                >← Anterior</span
                            >
                        <?php else: ?>
                            <a
                                href="<?php echo e($chamados->previousPageUrl()); ?>"
                                class="rounded border border-gray-300 px-3 py-1 text-gray-700 transition hover:bg-gray-100"
                                >← Anterior</a
                            >
                        <?php endif; ?>

                        <?php $__currentLoopData = $chamados->getUrlRange(1, $chamados->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $chamados->currentPage()): ?>
                                <span
                                    class="rounded bg-red-600 px-3 py-1 font-semibold text-white"
                                    ><?php echo e($page); ?></span
                                >
                            <?php else: ?>
                                <a
                                    href="<?php echo e($url); ?>"
                                    class="rounded border border-gray-300 px-3 py-1 text-gray-700 transition hover:bg-gray-100"
                                    ><?php echo e($page); ?></a
                                >
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($chamados->hasMorePages()): ?>
                            <a
                                href="<?php echo e($chamados->nextPageUrl()); ?>"
                                class="rounded border border-gray-300 px-3 py-1 text-gray-700 transition hover:bg-gray-100"
                                >Próximo →</a
                            >
                        <?php else: ?>
                            <span
                                class="cursor-not-allowed rounded border border-gray-300 px-3 py-1 text-gray-400"
                                >Próximo →</span
                            >
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botão Relatar novo Problema -->
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

    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/chamados/index.blade.php ENDPATH**/ ?>