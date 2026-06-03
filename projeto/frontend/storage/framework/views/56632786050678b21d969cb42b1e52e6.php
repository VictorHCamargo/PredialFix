<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meu Perfil – PredialFix</title>
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

    <?php if($temErroSenha ?? false): ?>
        <div data-has-password-error style="display: none;"></div>
    <?php endif; ?>

    <?php if($temErroDelete ?? false): ?>
        <div data-has-delete-error style="display: none;"></div>
    <?php endif; ?>

    <main class="mx-auto w-full max-w-4xl flex-1 px-6 py-8">
        
        <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-gray-800"><?php echo e($user->nome); ?></h1>
            <p class="text-gray-600">Gerencie suas informações de perfil</p>
        </div>

        
        <?php if(session('success')): ?>
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            
            <div class="space-y-6 lg:col-span-2">
                
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Informações Básicas</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Nome</label>
                            <p class="text-gray-800"><?php echo e($user->nome); ?></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600"
                                >E-mail</label
                            >
                            <p class="text-gray-800"><?php echo e($user->email); ?></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600"
                                >Nível de Acesso</label
                            >
                            <div
                                class="inline-block rounded bg-red-100 px-3 py-1 text-sm font-semibold text-red-700"
                            >
                                <?php
                                    $niveis = [
                                        'administrador' => 'Administrador',
                                        'gerente_manutencao' => 'Gerente de Manutenção',
                                        'tecnico_manutencao' => 'Técnico de Manutenção',
                                        'professor' => 'Professor',
                                        'aluno' => 'Aluno',
                                    ];
                                ?>
                                <?php echo e($niveis[$user->nivel_acesso] ??
                                        $user->nivel_acesso); ?>

                            </div>
                        </div>

                        <?php if(!$user->isAluno()): ?>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-600"
                                    >Setor/Departamento</label
                                >
                                <p class="text-gray-800"><?php echo e($user->setor ?? 'Não informado'); ?></p>
                            </div>
                        <?php endif; ?>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600"
                                >Membro desde</label
                            >
                            <p class="text-gray-800"><?php echo e($user->created_at->format('d/m/Y')); ?></p>
                        </div>

                        <div class="flex gap-2 pt-4">
                            <a
                                href="<?php echo e(route('profile.edit')); ?>"
                                class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                            >
                                Editar Perfil
                            </a>
                            <button
                                type="button"
                                onclick="openChangePasswordModal()"
                                class="rounded bg-gray-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700"
                            >
                                Alterar Senha
                            </button>
                        </div>
                    </div>
                </div>

                
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">
                        Seus Chamados (5 Recentes)
                    </h2>

                    <?php if($chamadosCriados->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $chamadosCriados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chamado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div
                                    class="rounded-lg border border-gray-200 p-4 transition hover:bg-gray-50"
                                >
                                    <div class="mb-2 flex items-start justify-between">
                                        <h3 class="font-semibold text-gray-800">
                                            <?php echo e(Str::limit(
                                                    $chamado->descricao,
                                                    50,
                                                )); ?>

                                        </h3>
                                        <?php
                                            $statusColors = [
                                                'aberto' => 'bg-blue-100 text-blue-700',
                                                'em_andamento' => 'bg-yellow-100 text-yellow-700',
                                                'concluido' => 'bg-green-100 text-green-700',
                                                'cancelado' => 'bg-red-100 text-red-700',
                                            ];
                                        ?>
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold <?php echo e($statusColors[$chamado->status] ?? ''); ?>"
                                        >
                                            <?php echo e(ucfirst(
                                                    str_replace('_', ' ', $chamado->status),
                                                )); ?>

                                        </span>
                                    </div>
                                    <p class="mb-2 text-xs text-gray-600">
                                        <?php echo e($chamado->local->sala_setor ??
                                                'Local desconhecido'); ?> - <?php echo e($chamado->data_abertura->format('d/m/Y')); ?>

                                    </p>
                                    <a
                                        href="<?php echo e(route('chamados.show', $chamado->id_chamado)); ?>"
                                        class="text-sm font-medium text-red-600 hover:underline"
                                    >
                                        Ver Detalhes →
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="py-8 text-center text-gray-500">Você não tem chamados registrados.</p>
                    <?php endif; ?>

                    <?php if($chamadosCriados->count() > 0): ?>
                        <a
                            href="<?php echo e(route('chamados.index')); ?>"
                            class="mt-4 inline-block text-sm font-medium text-red-600 hover:underline"
                        >
                            Ver todos os chamados →
                        </a>
                    <?php endif; ?>
                </div>

                
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">
                        Suas Avaliações (5 Recentes)
                    </h2>

                    <?php if($feedbacks->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div
                                    class="rounded-lg border border-gray-200 p-4 transition hover:bg-gray-50"
                                >
                                    <div class="mb-2 flex items-start justify-between">
                                        <h3 class="font-semibold text-gray-800">
                                            Chamado #<?php echo e($feedback->chamado->id_chamado); ?>

                                        </h3>
                                        <div class="flex items-center gap-1">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <span
                                                    class="text-lg <?php if($i <= $feedback->nota): ?> text-yellow-400 <?php else: ?> text-gray-300 <?php endif; ?>"
                                                    >★</span
                                                >
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="mb-2 text-sm text-gray-600"><?php echo e(Str::limit(
                                            $feedback->comentario,
                                            100,
                                        )); ?></p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo e($feedback->created_at->format(
                                                'd/m/Y H:i',
                                            )); ?>

                                    </p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="py-8 text-center text-gray-500">Você não tem avaliações registradas.</p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="space-y-6">
                
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 font-semibold text-gray-800">Status da Conta</h3>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-block h-3 w-3 rounded-full bg-green-500"></span>
                            <span class="text-sm text-gray-700">
                                <?php if($user->ativo): ?> Conta Ativa <?php else: ?> Conta Inativa <?php endif; ?>
                            </span>
                        </div>

                        <div class="border-t border-gray-200 pt-2">
                            <p class="text-xs text-gray-600">Última atualização:</p>
                            <p class="text-sm text-gray-800"><?php echo e($user->updated_at->format(
                                    'd/m/Y H:i',
                                )); ?></p>
                        </div>
                    </div>
                </div>

                
                <div class="rounded-lg border-l-4 border-red-500 bg-white p-6 shadow">
                    <h3 class="mb-4 font-semibold text-gray-800 text-red-600">Ações Perigosas</h3>

                    <button
                        type="button"
                        onclick="openLogoutModal()"
                        class="mb-2 w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                    >
                        Sair da Conta
                    </button>

                    <button
                        type="button"
                        onclick="openDeleteAccountModal()"
                        class="w-full rounded bg-red-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-950"
                    >
                        Deletar Conta
                    </button>
                </div>
            </div>
        </div>
    </main>

    
    <div
        id="changePasswordModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 <?php if(!$errors->has('senha_atual') && !$errors->has('senha_nova')): ?> hidden <?php endif; ?>"
    >
        <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Alterar Senha</h3>

            <form method="POST" action="<?php echo e(route('profile.updatePassword')); ?>" novalidate>
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="mb-4">
                    <label for="senha_atual" class="mb-1 block text-sm font-medium text-gray-700"
                        >Senha Atual</label
                    >
                    <input
                        id="senha_atual"
                        type="password"
                        name="senha_atual"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        <?php $__errorArgs = ['senha_atual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> class="border-red-400 bg-red-50" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    />
                    <?php $__errorArgs = ['senha_atual'];
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

                <div class="mb-4">
                    <label for="senha_nova" class="mb-1 block text-sm font-medium text-gray-700"
                        >Nova Senha</label
                    >
                    <input
                        id="senha_nova"
                        type="password"
                        name="senha_nova"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        <?php $__errorArgs = ['senha_nova'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> class="border-red-400 bg-red-50" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    />
                    <?php $__errorArgs = ['senha_nova'];
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

                <div class="mb-6">
                    <label
                        for="senha_nova_confirmation"
                        class="mb-1 block text-sm font-medium text-gray-700"
                        >Confirmar Senha</label
                    >
                    <input
                        id="senha_nova_confirmation"
                        type="password"
                        name="senha_nova_confirmation"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    />
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeChangePasswordModal()"
                        class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div
        id="logoutModal"
        class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50"
    >
        <div class="mx-4 w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-3 text-lg font-semibold text-gray-800">Sair da Conta?</h3>
            <p class="mb-6 text-gray-600">Você está prestes a sair de sua conta. Tem certeza?</p>

            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="closeLogoutModal()"
                    class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                >
                    Cancelar
                </button>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="flex-1">
                    <?php echo csrf_field(); ?>
                    <button
                        type="submit"
                        class="w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                    >
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div
        id="deleteAccountModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 <?php if(!$errors->has('senha')): ?> hidden <?php endif; ?>"
    >
        <div class="mx-4 w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-3 text-lg font-semibold text-red-600">Deletar Conta Permanentemente?</h3>
            <p class="mb-4 text-gray-600">Esta ação é <strong>irreversível</strong>. Todos os seus dados serão removidos.</p>

            <form method="POST" action="<?php echo e(route('profile.destroy')); ?>" novalidate>
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

                <div class="mb-4">
                    <label for="delete_senha" class="mb-1 block text-sm font-medium text-gray-700"
                        >Confirme sua senha para deletar:</label
                    >
                    <input
                        id="delete_senha"
                        type="password"
                        name="senha"
                        placeholder="Sua senha"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        <?php $__errorArgs = ['senha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> class="border-red-400 bg-red-50" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    />
                    <?php $__errorArgs = ['senha'];
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

                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeDeleteAccountModal()"
                        class="flex-1 rounded bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded bg-red-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-950"
                    >
                        Deletar Permanentemente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php
        $temErroSenha = $errors->has('senha_atual') || $errors->has('senha_nova');
        $temErroDelete = $errors->has('senha');
    ?>

    <script>
        function openChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.remove('hidden');
        }

        function closeChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.add('hidden');
        }

        function openLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        function openDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.remove('hidden');
        }

        function closeDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.add('hidden');
        }

        // Se houver erros de senha, manter o modal aberto
        document.addEventListener('DOMContentLoaded', function () {
            const hasPasswordError = document.querySelector('[data-has-password-error]');
            const hasDeleteError = document.querySelector('[data-has-delete-error]');
            
            if (hasPasswordError) {
                document.getElementById('changePasswordModal').classList.remove('hidden');
                const senhaAtualField = document.getElementById('senha_atual');
                const senhaNovaField = document.getElementById('senha_nova');
                if (senhaAtualField) setTimeout(() => senhaAtualField.focus(), 100);
                else if (senhaNovaField) setTimeout(() => senhaNovaField.focus(), 100);
            }

            if (hasDeleteError) {
                document.getElementById('deleteAccountModal').classList.remove('hidden');
                const deleteSenhaField = document.getElementById('delete_senha');
                if (deleteSenhaField) setTimeout(() => deleteSenhaField.focus(), 100);
            }
        });

        // Fechar modals ao clicar fora
        document.getElementById('changePasswordModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'changePasswordModal') closeChangePasswordModal();
        });

        document.getElementById('logoutModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'logoutModal') closeLogoutModal();
        });

        document.getElementById('deleteAccountModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'deleteAccountModal') closeDeleteAccountModal();
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/profile/show.blade.php ENDPATH**/ ?>