<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $board->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-6">
                    {{ $board->name }}
                </h1>

                <h2 class="text-lg font-semibold mb-4">
                    Listas
                </h2>

                {{-- Adicionar lista --}}
                <form method="POST" action="{{ route('boards.lists.store', $board) }}" class="mb-6">
                    @csrf

                    <div class="flex gap-2">
                        <input
                            type="text"
                            name="name"
                            placeholder="Nome da lista"
                            class="border-gray-300 rounded-md shadow-sm flex-1"
                            required
                        >

                        <button
                            type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md"
                        >
                            Adicionar lista
                        </button>
                    </div>

                    @error('name')
                        <p class="text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </form>

                {{-- Container das listas --}}
                <div id="lists-container">
                    @forelse ($board->lists as $list)

                        {{-- Lista --}}
                        <div class="border rounded p-4 mb-6" data-id="{{ $list->id }}">

                            <div class="flex items-center gap-2 mb-4">

                                {{-- Alça de arrastar --}}
                                <span class="drag-handle cursor-move text-gray-400 px-2">⠿</span>

                                <form method="POST" action="{{ route('lists.update', $list) }}" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $list->name }}"
                                        class="border-gray-300 rounded-md shadow-sm" required>
                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                        Renomear
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('lists.destroy', $list) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">
                                        Excluir
                                    </button>
                                </form>

                            </div>

                            <div class="ml-4">
                                <h3 class="font-semibold mb-3">Tasks</h3>

                                {{-- Container das tasks dessa lista --}}
                                <div id="tasks-container-{{ $list->id }}" data-list-id="{{ $list->id }}">
                                    @forelse ($list->tasks as $task)

                                        <div class="border rounded p-3 mb-3" data-id="{{ $task->id }}">
                                            <div class="flex items-center gap-2">

                                                <span class="drag-handle cursor-move text-gray-400 px-2">⠿</span>

                                                <form method="POST" action="{{ route('tasks.update', $task) }}" class="inline-flex gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name" value="{{ $task->name }}"
                                                        class="border-gray-300 rounded-md shadow-sm" required>
                                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                                        Renomear
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">
                                                        Excluir
                                                    </button>
                                                </form>

                                            </div>

                                            @if ($task->description)
                                                <p class="text-gray-600 mt-2">{{ $task->description }}</p>
                                            @endif
                                        </div>

                                    @empty
                                        <p class="text-gray-500 mb-4">Esta lista ainda não possui tasks.</p>
                                    @endforelse
                                </div>

                                <form method="POST" action="{{ route('lists.tasks.store', $list) }}" class="mt-4">
                                    @csrf
                                    <div class="flex gap-2">
                                        <input type="text" name="name" placeholder="Nome da task"
                                            class="border-gray-300 rounded-md shadow-sm" required>
                                        <input type="text" name="description" placeholder="Descrição (opcional)"
                                            class="border-gray-300 rounded-md shadow-sm">
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                                            Adicionar task
                                        </button>
                                    </div>
                                </form>

                            </div>

                        </div>

                    @empty
                        <p class="text-gray-500">Este quadro ainda não possui listas.</p>
                    @endforelse
                </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            function enviarOrdem(url, ids) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ order: ids }),
                });
            }

            // Arrastar listas
            const listsContainer = document.getElementById('lists-container');
            if (listsContainer) {
                new Sortable(listsContainer, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: function () {
                        const ids = [...listsContainer.children].map(el => el.dataset.id);
                        enviarOrdem('{{ route('boards.lists.reorder', $board) }}', ids);
                    },
                });
            }

            // Arrastar tasks dentro de cada lista
            document.querySelectorAll('[id^="tasks-container-"]').forEach(function (container) {
                new Sortable(container, {
                    group: 'tasks',
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: function (evt) {
                        const listId = evt.to.dataset.listId;
                        const ids = [...evt.to.children].map(el => el.dataset.id);
                        enviarOrdem(`/lists/${listId}/tasks/reorder`, ids);
                    },
                });
            });

        });
    </script>
</x-app-layout>
