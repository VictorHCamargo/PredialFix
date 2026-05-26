<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => '#',
    'route' => '',
    'color' => 'red',
    'border' => true
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
    'href' => '#',
    'route' => '',
    'color' => 'red',
    'border' => true
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
        'red' => 'hover:bg-red-700   data-[active]:bg-red-700',
        'blue' => 'hover:bg-blue-700  data-[active]:bg-blue-700',
        'green' => 'hover:bg-green-700 data-[active]:bg-green-700',
        'yellow' => 'hover:bg-yellow-600 data-[active]:bg-yellow-600',
        'gray' => 'hover:bg-gray-600  data-[active]:bg-gray-600',
    ];
    $colorClasses = $colorMap[$color] ?? $colorMap['red'];
    $isActive = $route && request()->routeIs($route);
    $borderClass = $border ? 'border-r border-red-400' : '';
?>

<a
    href="<?php echo e($href); ?>"
    <?php echo e($isActive ? 'data-active' : ''); ?>

    class="text-white text-sm font-medium px-5 py-4 transition
           <?php echo e($borderClass); ?>

           <?php echo e($colorClasses); ?>

           <?php echo e($isActive ? 'bg-red-700' : ''); ?>"
>
    <?php echo e($slot); ?>

</a>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/components/nav-item.blade.php ENDPATH**/ ?>