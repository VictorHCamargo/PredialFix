<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold mb-4">Bem-vindo, {{ Auth::user()->nome }}!</h3>

                {{-- SE FOR ADMINISTRADOR --}}
                @if (Auth::user()->perfil->identificador === 'ADM')
                    <div class="bg-red-100 p-4 rounded text-red-800">
                        <h4>Área do Administrador</h4>
                        <p>Aqui você pode cadastrar novos perfis, apagar o sistema todo, etc.</p>
                        <button class="mt-2 bg-red-500 text-white px-4 py-2 rounded">Botão Super Secreto</button>
                    </div>

                {{-- SE FOR GESTOR GERAL --}}
                @elseif (Auth::user()->perfil->identificador === 'GET')
                    <div class="bg-blue-100 p-4 rounded text-blue-800">
                        <h4>Área do Gestor Geral</h4>
                        <p>Aqui você pode ver os relatórios de todos os funcionários.</p>
                        <button class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Ver Relatórios</button>
                    </div>

                {{-- SE FOR FUNCIONÁRIO (O que sobrar) --}}
                @else
                    <div class="bg-green-100 p-4 rounded text-green-800">
                        <h4>Área do Funcionário</h4>
                        <p>Aqui você bate seu ponto, vê suas tarefas, etc.</p>
                        <button class="mt-2 bg-green-500 text-white px-4 py-2 rounded">Minhas Tarefas</button>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>