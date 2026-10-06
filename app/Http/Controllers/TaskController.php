<?php

namespace App\Http\Controllers;

use App\Models\BoardList;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BoardList $list)
    {
        $this->authorize('create', [Task::class, $list]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        $list->tasks()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'position' => $list->tasks()->count()
        ]);

        return redirect()->route('boards.show', $list->board);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        $task->update($validated);

        return redirect()->route('boards.show', $task->boardList->board);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('boards.show', $task->boardList->board);
    }

    public function reorder(Request $request, BoardList $list)
    {
        $this->authorize('create', [Task::class, $list]);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:tasks,id',
        ]);

        foreach ($validated['order'] as $index => $taskId) {
            Task::find($taskId)->update(['position' => $index, 'board_list_id' => $list->id]);
        }

        return response()->json(['status' => 'ok']);
    }
}
