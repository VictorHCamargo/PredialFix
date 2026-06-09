@props([
    'brand' => 'SENAI',
    'color' => 'red',
    'itemColor' => 'red',
])

@php
    $bgMap = [
        'red' => 'bg-red-600',
        'blue' => 'bg-blue-700',
        'green' => 'bg-green-700',
        'gray' => 'bg-gray-700',
        'dark' => 'bg-gray-900',
    ];

    $bgClass = $bgMap[$color ?? 'red'] ?? $bgMap['red'];
    $naoLidas = auth()->check()
        ? \App\Models\Notificacao::where('id_usuario', auth()->id())
            ->where('lida', false)
            ->count()
        : 0;
@endphp

<nav class="{{ $bgClass }} flex items-center justify-between px-4 py-0 shadow-md">
    <div class="flex items-center gap-2 py-2">
        <img src="{{ asset('images/SENAI_LOGO.png') }}" alt="SENAI Logo" class="h-10" />
    </div>

    <div class="flex h-full items-center">
        <x-nav-item href="{{ route('dashboard') }}" route="dashboard" :color="$itemColor">Home</x-nav-item>

        <x-nav-item href="{{ route('chamados.create') }}" route="chamados.create" :color="$itemColor">
            Novo Chamado
        </x-nav-item>

        <x-nav-item href="{{ route('chamados.index') }}" route="chamados.index" :color="$itemColor" :border="false">
            Gerenciar Chamados
        </x-nav-item>

        <x-nav-item href="{{ route('avaliar.index') }}" route="avaliar.index" :color="$itemColor" :border="false">
            Avaliar
        </x-nav-item>

        @if (Auth::user()->isAdmin())
            <x-nav-item href="{{ route('admin.usuarios.index') }}" route="admin.usuarios.*" :color="$itemColor">
                Usuários
            </x-nav-item>
        @endif

        <x-nav-item href="{{ route('profile.show') }}" route="profile.show" :color="$itemColor" :border="false">
            Meu Perfil
        </x-nav-item>

        <a
            href="{{ route('notificacoes.index') }}"
            class="relative flex items-center gap-2 px-5 py-4 text-sm font-medium text-white transition hover:bg-red-700 {{ request()->routeIs('notificacoes.*') ? 'bg-red-700' : '' }}"
            aria-label="Notificacoes"
        >
            <span>🔔</span>
            @if ($naoLidas > 0)
                <span class="absolute right-3 top-2 min-w-5 rounded-full bg-yellow-400 px-1.5 py-0.5 text-center text-[10px] font-bold text-gray-900">
                    {{ $naoLidas }}
                </span>
            @endif
        </a>

        {{ $slot ?? '' }}
    </div>

    <x-nav-logout :color="$itemColor" />
</nav>
