@props ([
    'action' => null,
    'label' => 'Sair',
    'color' => 'red',
    'icon' => true
])

@php
    $colorMap = [
        'red' => 'hover:bg-red-700',
        'blue' => 'hover:bg-blue-700',
        'green' => 'hover:bg-green-700',
        'yellow' => 'hover:bg-yellow-600',
        'gray' => 'hover:bg-gray-600',
    ];

    $hoverClass = $colorMap[$color] ?? $colorMap['red'];
    $formAction = $action ?? route('logout');
@endphp

<button
    type="button"
    onclick="openLogoutModal()"
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

<!-- Modal de Logout -->
<div
    id="logoutModal"
    class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50"
>
    <div class="mx-4 w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
        <h2 class="mb-4 text-lg font-semibold text-gray-800">Sair da conta?</h2>
        <p class="mb-6 text-gray-600">Tem certeza que deseja sair? Você será desconectado do sistema.</p>
        <div class="flex gap-3">
            <form method="POST" action="{{ $formAction }}" class="flex-1">
                @csrf
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
