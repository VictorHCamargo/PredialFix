@props([
    'action' => null,
    'label'  => 'Sair',
    'color'  => 'red',
    'icon'   => true,
])

@php
    $colorMap = [
        'red'    => 'hover:bg-red-700',
        'blue'   => 'hover:bg-blue-700',
        'green'  => 'hover:bg-green-700',
        'yellow' => 'hover:bg-yellow-600',
        'gray'   => 'hover:bg-gray-600',
    ];

    $hoverClass  = $colorMap[$color] ?? $colorMap['red'];
    $formAction  = $action ?? route('logout');
@endphp

<form method="POST" action="{{ $formAction }}" class="flex items-center">
    @csrf
    <button
        type="submit"
        class="text-white text-sm font-medium px-5 py-4
               flex items-center gap-2 transition
               {{ $hoverClass }}"
    >
        {{ $label }}

        @if ($icon)
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
        @endif
    </button>
</form>