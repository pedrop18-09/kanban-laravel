@props(['board' => null])

<form method="POST" action="{{ $board ? route('boards.update', $board) : route('boards.store') }}">
    @csrf

    @if ($board)
        @method('PUT')
    @endif

    <label for="name" class="block font-medium text-sm text-gray-700">
        Nome do quadro
    </label>
    <input id="name" name="name" type="text" value="{{ old('name', $board->name ?? '') }}"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" autofocus>

    @error('name')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror

    <div class="mt-4">
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            {{ $board ? 'Salvar' : 'Criar' }}
        </button>
        <a href="{{ route('boards.index') }}" class="ml-2 text-gray-600 hover:underline">
            Cancelar
        </a>
    </div>
</form>
