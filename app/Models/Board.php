<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\BelongsTo;
class Board extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function create()
    {
        return view('boards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=> 'required|string|max:255'
        ]);

        auth()->user()-boards()-create($validated);

        return redirect()->route('boards.index');
    }
}
