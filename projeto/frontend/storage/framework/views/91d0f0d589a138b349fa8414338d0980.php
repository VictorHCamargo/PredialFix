<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'brand' => 'SENAI',
    'color' => 'red',
    'itemColor' => 'red'
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
    'brand' => 'SENAI',
    'color' => 'red',
    'itemColor' => 'red'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $bgMap = [
        'red' => 'bg-red-600',
        'blue' => 'bg-blue-700',
        'green' => 'bg-green-700',
        'gray' => 'bg-gray-700',
        'dark' => 'bg-gray-900',
    ];

    $bgClass = $bgMap[$color] ?? $bgMap['red'];
?>

<nav class="<?php echo e($bgClass); ?> flex items-center justify-between px-4 py-0 shadow-md">
    <div class="flex items-center gap-1 py-2">
        <div class="flex items-center gap-2">
            <img src="<?php echo e(asset('images/SENAI_LOGO.png')); ?>" alt="SENAI Logo" class="h-10" />
        </div>
    </div>
    <div class="flex h-full items-center">
        

        <?php if (isset($component)) { $__componentOriginal6cced52613a484e7295a90162a92d81b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cced52613a484e7295a90162a92d81b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-item','data' => ['href' => ''.e(route('dashboard')).'','route' => 'dashboard','color' => $itemColor]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard')).'','route' => 'dashboard','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itemColor)]); ?>
            Home
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $attributes = $__attributesOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__attributesOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $component = $__componentOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__componentOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>

        <?php if(!Auth::user()->isAluno()): ?>
            <?php if (isset($component)) { $__componentOriginal6cced52613a484e7295a90162a92d81b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cced52613a484e7295a90162a92d81b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-item','data' => ['href' => ''.e(route('chamados.create')).'','route' => 'chamados.create','color' => $itemColor]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('chamados.create')).'','route' => 'chamados.create','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itemColor)]); ?>
                Novo Chamado
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $attributes = $__attributesOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__attributesOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $component = $__componentOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__componentOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal6cced52613a484e7295a90162a92d81b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cced52613a484e7295a90162a92d81b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-item','data' => ['href' => ''.e(route('chamados.index')).'','route' => 'chamados.index','color' => $itemColor,'border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('chamados.index')).'','route' => 'chamados.index','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itemColor),'border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
                Gerenciar Chamados
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $attributes = $__attributesOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__attributesOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $component = $__componentOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__componentOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>
        <?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal6cced52613a484e7295a90162a92d81b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cced52613a484e7295a90162a92d81b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-item','data' => ['href' => ''.e(route('avaliar.index')).'','route' => 'avaliar.index','color' => $itemColor,'border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('avaliar.index')).'','route' => 'avaliar.index','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itemColor),'border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
            Avaliar
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $attributes = $__attributesOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__attributesOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $component = $__componentOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__componentOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal6cced52613a484e7295a90162a92d81b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cced52613a484e7295a90162a92d81b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-item','data' => ['href' => ''.e(route('profile.show')).'','route' => 'profile.show','color' => $itemColor,'border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('profile.show')).'','route' => 'profile.show','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itemColor),'border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
            Meu Perfil
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $attributes = $__attributesOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__attributesOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cced52613a484e7295a90162a92d81b)): ?>
<?php $component = $__componentOriginal6cced52613a484e7295a90162a92d81b; ?>
<?php unset($__componentOriginal6cced52613a484e7295a90162a92d81b); ?>
<?php endif; ?>

        <?php echo e($slot ?? ''); ?>

    </div>

    <?php if (isset($component)) { $__componentOriginalbf5275f2af0e14a402320863fc186219 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbf5275f2af0e14a402320863fc186219 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-logout','data' => ['color' => $itemColor]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-logout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itemColor)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbf5275f2af0e14a402320863fc186219)): ?>
<?php $attributes = $__attributesOriginalbf5275f2af0e14a402320863fc186219; ?>
<?php unset($__attributesOriginalbf5275f2af0e14a402320863fc186219); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbf5275f2af0e14a402320863fc186219)): ?>
<?php $component = $__componentOriginalbf5275f2af0e14a402320863fc186219; ?>
<?php unset($__componentOriginalbf5275f2af0e14a402320863fc186219); ?>
<?php endif; ?>
</nav>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/components/navbar.blade.php ENDPATH**/ ?>