<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PredialFix – Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        senai: { red: '#E3000F' },
                    },
                    fontFamily: {
                        sans: ['Segoe UI', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100 font-sans">
    <div class="w-full max-w-md">
        
        <div class="mb-8 text-center">
            <img
                src="<?php echo e(asset('images/SENAI_LOGO.png')); ?>"
                alt="SENAI Logo"
                class="mx-auto mb-3 h-16"
            />
            <h1 class="text-2xl font-bold text-gray-800">PredialFix</h1>
            <p class="mt-1 text-sm text-gray-500">Sistema de Gestão de Chamados</p>
        </div>

        
        <div class="rounded-2xl bg-white p-8 shadow-lg">
            <h2 class="mb-6 text-center text-lg font-semibold text-gray-700">Acesse sua conta</h2>

            
            <?php if(session('error')): ?>
                <div
                    class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                >
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" novalidate>
                <?php echo csrf_field(); ?>

                
                <div class="mb-5">
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        autocomplete="email"
                        autofocus
                        placeholder="seu@email.com"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    />
                    <?php $__errorArgs = ['email'];
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
                    <label for="senha" class="mb-1 block text-sm font-medium text-gray-700">
                        Senha
                    </label>
                    <input
                        id="senha"
                        type="password"
                        name="senha"
                        autocomplete="current-password"
                        placeholder="Digite sua senha"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               <?php $__errorArgs = ['senha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
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

                
                <div class="mb-6 flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                    />
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Manter conectado
                    </label>
                </div>

                
                <button
                    type="submit"
                    class="w-full rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 active:bg-red-800"
                >
                    Entrar
                </button>
            </form>

            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Não tem conta?
                    <a
                        href="<?php echo e(route('register')); ?>"
                        class="font-semibold text-red-600 hover:underline"
                        >Registre-se aqui</a
                    >
                </p>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">PredialFix &copy; <?php echo e(date('Y')); ?> — SENAI</p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/auth/login.blade.php ENDPATH**/ ?>