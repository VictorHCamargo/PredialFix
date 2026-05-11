@props([
    'brand'     => 'SENAI',
    'color'     => 'red',
    'itemColor' => 'red',
])

@php
    $bgMap = [
        'red'   => 'bg-red-600',
        'blue'  => 'bg-blue-700',
        'green' => 'bg-green-700',
        'gray'  => 'bg-gray-700',
        'dark'  => 'bg-gray-900',
    ];

    $bgClass = $bgMap[$color] ?? $bgMap['red'];
@endphp

<nav class="{{ $bgClass }} flex items-center justify-between px-4 py-0 shadow-md">
    <div class="flex items-center gap-1 py-2">
        <div class="bg-white text-red-600 font-black text-2xl px-3 py-1 tracking-tight select-none leading-none">
            {{ $brand }}
        </div>
    </div>
    <div class="flex items-center h-full">

        {{--
            Cada <x-nav-item> recebe:
              • href   → URL gerada pela rota
              • route  → nome da rota (para checar o active)
              • color  → mesma cor do itemColor da navbar
        --}}

        <x-nav-item
            href="{{ route('dashboard') }}"
            route="dashboard"
            :color="$itemColor"
        >
            Home
        </x-nav-item>

        <x-nav-item
            href="{{ route('chamados.create') }}"
            route="chamados.create"
            :color="$itemColor"
        >
            Novo Chamado
        </x-nav-item>

        <x-nav-item
            href="{{ route('chamados.index') }}"
            route="chamados.index"
            :color="$itemColor"
            :border="false"
        >
            Gerenciar Chamados
        </x-nav-item>

        {{ $slot ?? '' }}

    </div>

    <x-nav-logout :color="$itemColor" />

</nav>