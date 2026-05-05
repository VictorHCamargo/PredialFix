@props([
    'href'   => '#',
    'route'  => '',
    'color'  => 'red',
    'border' => true,
])

@php
    $colorMap = [
        'red'    => 'hover:bg-red-700   data-[active]:bg-red-700',
        'blue'   => 'hover:bg-blue-700  data-[active]:bg-blue-700',
        'green'  => 'hover:bg-green-700 data-[active]:bg-green-700',
        'yellow' => 'hover:bg-yellow-600 data-[active]:bg-yellow-600',
        'gray'   => 'hover:bg-gray-600  data-[active]:bg-gray-600',
    ];
    $colorClasses = $colorMap[$color] ?? $colorMap['red'];
    $isActive = $route && request()->routeIs($route);
    $borderClass = $border ? 'border-r border-red-400' : '';
@endphp

<a
    href="{{ $href }}"
    {{ $isActive ? 'data-active' : '' }}
    class="text-white text-sm font-medium px-5 py-4 transition
           {{ $borderClass }}
           {{ $colorClasses }}
           {{ $isActive ? 'bg-red-700' : '' }}"
>
    {{ $slot }}
</a>