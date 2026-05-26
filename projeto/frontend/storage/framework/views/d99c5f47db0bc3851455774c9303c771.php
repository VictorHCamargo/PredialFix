<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'action' => null,
    'label' => 'Sair',
    'color' => 'red',
    'icon' => true
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'action' => null,
    'label' => 'Sair',
    'color' => 'red',
    'icon' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colorMap = [
        'red' => 'hover:bg-red-700',
        'blue' => 'hover:bg-blue-700',
        'green' => 'hover:bg-green-700',
        'yellow' => 'hover:bg-yellow-600',
        'gray' => 'hover:bg-gray-600',
    ];

    $hoverClass = $colorMap[$color] ?? $colorMap['red'];
    $formAction = $action ?? route('logout');
?>

<button
    type="button"
    onclick="openLogoutModal()"
    class="text-white text-sm font-medium px-5 py-4
           flex items-center gap-2 transition
           <?php echo e($hoverClass); ?>"
>
    <?php echo e($label); ?>


    <?php if($icon): ?>
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2
                   2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"
            />
        </svg>
    <?php endif; ?>
</button>

<!-- Modal de Logout -->
<div
    id="logoutModal"
    class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50"
>
    <div class="mx-4 w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
        <h2 class="mb-4 text-lg font-semibold text-gray-800">Sair da conta?</h2>
        <p class="mb-6 text-gray-600">Tem certeza que deseja sair? Você será desconectado do sistema.</p>
        <div class="flex gap-3">
            <form method="POST" action="<?php echo e($formAction); ?>" class="flex-1">
                <?php echo csrf_field(); ?>
                <button
                    type="submit"
                    class="w-full rounded bg-red-600 py-2 font-medium text-white transition hover:bg-red-700"
                >
                    Sair
                </button>
            </form>
            <button
                type="button"
                onclick="closeLogoutModal()"
                class="flex-1 rounded bg-gray-300 py-2 font-medium text-gray-800 transition hover:bg-gray-400"
            >
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
    }
    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/components/nav-logout.blade.php ENDPATH**/ ?>