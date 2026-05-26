<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PredialFix - <?php echo e($tittle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        senai: { red: '#E3000F', dark: '#1a1a1a' },
                    },
                    fontFamily: {
                        sans: ['Segoe UI', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="flex min-h-screen flex-col bg-gray-100 font-sans">
    <?php echo e($slot); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/components/layouts/base-layout.blade.php ENDPATH**/ ?>