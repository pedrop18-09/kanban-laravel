{{ dd('cheguei aqui') }}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Quadro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('boards.store') }}">
                    @csrf

                    <label for="name" class="block font-medium text-sm text-gray-700">
                        Nome do quadro
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" autofocus>

                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mt-4">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Criar
                        </button>
                        <a href="{{ route('boards.index') }}" class="ml-2 text-gray-600 hover:underline">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
