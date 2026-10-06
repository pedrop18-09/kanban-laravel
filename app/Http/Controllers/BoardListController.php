<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\BoardList;

class BoardListController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board)
    {
        $this->authorize('create', [BoardList::class, $board]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $board->lists()->create([
            'name' => $validated['name'],
            'position' => $board->lists()->count(),
        ]);

        return redirect()->route('boards.show', $board);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoardList $list)
    {
        $this->authorize('update', $list);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $list->update($validated);

        return redirect()->route('boards.show', $list->board);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardList $list)
    {
        $this->authorize('delete', $list);

        $board = $list->board;

        $list->delete();

        return redirect()->route('boards.show', $board);
    }

    public function reorder(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:board_lists,id',
        ]);

        foreach ($validated['order'] as $index => $listId) {
            BoardList::find($listId)->update(['position' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }
}
