<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Quadros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('boards.create') }}"
                   class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-gray rounded hover:bg-indigo-700">
                    + Novo Quadro
                </a>

                @if ($boards->isEmpty())
                    <p class="text-gray-500">Você ainda não tem nenhum quadro. Crie o primeiro!</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($boards as $board)
                            <li class="border rounded p-4 flex justify-between items-center">
                                <a href="{{ route('boards.show', $board) }}" class="font-medium text-lg hover:underline">
                                    {{ $board->name }}
                                </a>
                                <div class="space-x-2">
                                    <a href="{{ route('boards.edit', $board) }}" class="text-blue-600 hover:underline">Editar</a>
                                    <form action="{{ route('boards.destroy', $board) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline"
                                                onclick="return confirm('Tem certeza?')">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
