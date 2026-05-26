<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chamado #<?php echo e($chamado->id_chamado); ?> – PredialFix</title>
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
        
        <div class="mb-6">
            <nav class="text-sm text-gray-600">
                <a href="<?php echo e(route('chamados.index')); ?>" class="hover:text-gray-800">Chamados</a>
                <span class="mx-2">/</span>
                <span class="font-semibold text-gray-800">Chamado #<?php echo e($chamado->id_chamado); ?></span>
            </nav>
        </div>

        
        <?php if(session('success')): ?>
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <div class="space-y-6 lg:col-span-2">
                
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <h1 class="mb-2 text-2xl font-bold text-gray-800">
                                Chamado #<?php echo e($chamado->id_chamado); ?>

                            </h1>
                            <p class="text-gray-600"><?php echo e($chamado->descricao); ?></p>
                        </div>
                        <?php
                            $statusColors = [
                                'aberto' => 'bg-blue-100 text-blue-700',
                                'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                'concluido' => 'bg-green-100 text-green-700',
                                'cancelado' => 'bg-red-100 text-red-700',
                            ];
                        ?>
                        <span
                            class="inline-block <?php echo e($statusColors[$chamado->status] ?? ''); ?> px-4 py-2 rounded-lg font-semibold text-lg"
                        >
                            <?php echo e(ucfirst(
                                    str_replace('_', ' ', $chamado->status),
                                )); ?>

                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Tipo</p>
                            <p class="text-sm font-semibold text-gray-800"><?php echo e(ucfirst(
                                    str_replace('_', ' ', $chamado->tipo_chamado),
                                )); ?></p>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Aberto em</p>
                            <p class="text-sm font-semibold text-gray-800">
                                <?php echo e($chamado->data_abertura->format(
                                        'd/m/Y H:i',
                                    )); ?>

                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Local</p>
                            <p class="text-sm font-semibold text-gray-800">
                                <?php echo e($chamado->local->sala_setor ?? '—'); ?>

                                <?php if($chamado->local->bloco): ?>
                                    - Bloco <?php echo e($chamado->local->bloco); ?>

                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if($chamado->prioridade): ?>
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Prioridade</p>
                                <?php
                                    $priorityColors = [
                                        'alta' => 'text-red-700',
                                        'media' => 'text-yellow-700',
                                        'baixa' => 'text-green-700',
                                    ];
                                ?>
                                <p
                                    class="text-sm font-semibold <?php echo e($priorityColors[$chamado->prioridade] ?? ''); ?>"
                                >
                                    <?php echo e(ucfirst($chamado->prioridade)); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Informações Adicionais</h2>

                    <div class="space-y-4">
                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Criado por</p>
                            <p class="text-sm text-gray-800"><?php echo e($chamado->usuario->nome ??
                                    'Desconhecido'); ?></p>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium text-gray-600">Tipo de Problema</p>
                            <p class="text-sm text-gray-800"><?php echo e($chamado->tipoProblema->categoria ??
                                    '—'); ?></p>
                        </div>

                        <?php if($chamado->equipamento): ?>
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Equipamento</p>
                                <p class="text-sm text-gray-800"><?php echo e($chamado->equipamento->nome ?? '—'); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($chamado->status_descricao): ?>
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Descrição do Status</p>
                                <p class="rounded bg-gray-100 p-3 text-sm text-gray-800"><?php echo e($chamado->status_descricao); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($chamado->data_conclusao): ?>
                            <div>
                                <p class="mb-1 text-xs font-medium text-gray-600">Concluído em</p>
                                <p class="text-sm text-gray-800"><?php echo e($chamado->data_conclusao->format(
                                        'd/m/Y H:i',
                                    )); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Histórico de Status</h2>

                    <?php if($chamado->historicoStatus->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $chamado->historicoStatus->reverse(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $historico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border-l-4 border-gray-300 py-2 pl-4">
                                    <div class="mb-1 flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                <?php echo e(ucfirst(
                                                        str_replace('_', ' ', $historico->status_anterior),
                                                    )); ?>

                                                <span class="text-gray-500">→</span>
                                                <?php echo e(ucfirst(
                                                        str_replace('_', ' ', $historico->status_novo),
                                                    )); ?>

                                            </p>
                                            <p class="text-xs text-gray-600">Por: <?php echo e($historico->usuario->nome ?? 'Desconhecido'); ?></p>
                                        </div>
                                        <p class="text-xs text-gray-500"><?php echo e($historico->created_at->format(
                                                'd/m/Y H:i',
                                            )); ?></p>
                                    </div>

                                    <?php if($historico->descricao_mudanca): ?>
                                        <p class="mt-2 rounded bg-gray-50 p-2 text-sm text-gray-700">
                                            <?php echo e($historico->descricao_mudanca); ?>

                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="py-4 text-center text-gray-500">Nenhuma mudança de status registrada ainda.</p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="space-y-6">
                
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 font-semibold text-gray-800">Ações</h3>

                    <div class="space-y-2">
                        
                        <?php if(auth()->check()): ?>
                            <button
                                onclick="openStatusModal()"
                                class="w-full rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                            >
                                Alterar Status
                            </button>
                        <?php endif; ?>

                        
                        <?php if(!auth()->user()->isAluno() && $chamado->status === 'concluido' && !$chamado->feedback && auth()->user()->temCodigoEntrada()): ?>
                            <a
                                href="<?php echo e(route('avaliar.create', $chamado->id_chamado)); ?>"
                                class="block w-full rounded bg-purple-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-purple-700"
                            >
                                Avaliar Chamado
                            </a>
                        <?php endif; ?>

                        
                        <?php if(!auth()->user()->isAluno() && auth()->user()->id_usuario === $chamado->id_usuario && $chamado->status === 'aberto'): ?>
                            <a
                                href="<?php echo e(route('chamados.edit', $chamado->id_chamado)); ?>"
                                class="block w-full rounded bg-gray-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-gray-700"
                            >
                                Editar Chamado
                            </a>
                        <?php endif; ?>

                        
                        <?php if(!auth()->user()->isAluno() && (auth()->user()->id_usuario === $chamado->id_usuario || auth()->user()->isAdmin())): ?>
                            <button
                                onclick="openDeleteModal()"
                                class="w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                            >
                                Deletar Chamado
                            </button>
                        <?php endif; ?>

                        <a
                            href="<?php echo e(route('chamados.index')); ?>"
                            class="block w-full rounded bg-gray-200 px-4 py-2 text-center text-sm font-medium text-gray-800 transition hover:bg-gray-300"
                        >
                            Voltar
                        </a>
                    </div>
                </div>

                
                <?php if($chamado->feedback): ?>
                    <div class="rounded-lg bg-white p-6 shadow">
                        <h3 class="mb-4 font-semibold text-gray-800">Feedback do Cliente</h3>

                        <div class="mb-3">
                            <p class="mb-1 text-sm text-gray-600">Avaliação:</p>
                            <div class="flex gap-1">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <span
                                        class="text-lg <?php if($i <= $chamado->feedback->avaliacao): ?> text-yellow-400 <?php else: ?> text-gray-300 <?php endif; ?>"
                                        >★</span
                                    >
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div>
                            <p class="mb-1 text-sm text-gray-600">Comentário:</p>
                            <p class="text-sm text-gray-800"><?php echo e($chamado->feedback->comentario); ?></p>
                        </div>

                        <p class="mt-3 text-xs text-gray-500">
                            <?php echo e($chamado->feedback->created_at->format(
                                    'd/m/Y H:i',
                                )); ?>

                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    
    <div
        id="statusModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50 p-4"
    >
        <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-lg bg-white shadow-lg">
            <div class="sticky top-0 border-b border-gray-300 bg-white px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800">Alterar Status do Chamado</h3>
                <p class="text-sm text-gray-600">Status atual: <strong><?php echo e(ucfirst(
                        str_replace('_', ' ', $chamado->status),
                    )); ?></strong></p>
            </div>

            <form
                method="POST"
                action="<?php echo e(route('chamados.updateStatus', $chamado->id_chamado)); ?>"
                class="space-y-4 p-6"
            >
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700"
                        >Novo Status</label
                    >
                    <select
                        id="status"
                        name="status"
                        required
                        onchange="atualizarCampos()"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        <option value="">Selecione um status</option>
                        <option value="aberto">Aberto</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div id="prioridadeContainer" class="hidden">
                    <label for="prioridade" class="mb-2 block text-sm font-medium text-gray-700"
                        >Prioridade</label
                    >
                    <select
                        id="prioridade"
                        name="prioridade"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        <option value="">Sem prioridade</option>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>

                
                <div id="descricaoContainer" class="hidden">
                    <label
                        for="status_descricao"
                        class="mb-2 block text-sm font-medium text-gray-700"
                        >Descrição</label
                    >
                    <textarea
                        id="status_descricao"
                        name="status_descricao"
                        rows="4"
                        placeholder="Descreva a mudança de status..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    ></textarea>
                    <p id="descricaoRequerida" class="mt-1 hidden text-xs text-red-500">Este campo é obrigatório para este status.</p>
                    <?php $__errorArgs = ['status_descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex gap-3 border-t border-gray-200 pt-4">
                    <button
                        type="button"
                        onclick="closeStatusModal()"
                        class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                    >
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div
        id="deleteModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50 p-4"
    >
        <div class="w-full max-w-sm rounded-lg bg-white shadow-lg">
            <div class="border-b border-gray-300 px-6 py-4">
                <h3 class="text-lg font-semibold text-red-600">Deletar Chamado?</h3>
            </div>

            <div class="px-6 py-4">
                <p class="mb-4 text-gray-600">Você está prestes a deletar o chamado <strong>#<?php echo e($chamado->id_chamado); ?></strong>. Esta ação é <strong>irreversível</strong>.</p>

                <form
                    method="POST"
                    action="<?php echo e(route('chamados.destroy', $chamado->id_chamado)); ?>"
                    class="space-y-4"
                >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <div class="flex gap-3">
                        <button
                            type="button"
                            onclick="closeDeleteModal()"
                            class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="flex-1 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                        >
                            Deletar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
        function openStatusModal() {
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function atualizarCampos() {
            const status = document.getElementById('status').value;
            const prioridadeContainer = document.getElementById('prioridadeContainer');
            const descricaoContainer = document.getElementById('descricaoContainer');
            const descricaoRequerida = document.getElementById('descricaoRequerida');

            // Resetar visibilidade
            prioridadeContainer.classList.add('hidden');
            descricaoContainer.classList.add('hidden');
            descricaoRequerida.classList.add('hidden');

            // Mostrar campos conforme status
            if (status === 'em_andamento') {
                prioridadeContainer.classList.remove('hidden');
            }

            if (status === 'concluido' || status === 'cancelado') {
                descricaoContainer.classList.remove('hidden');
                descricaoRequerida.classList.remove('hidden');
            }
        }

        // Fechar modals ao clicar fora
        document.getElementById('statusModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'statusModal') closeStatusModal();
        });

        document.getElementById('deleteModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') closeDeleteModal();
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/chamados/show.blade.php ENDPATH**/ ?>