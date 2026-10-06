<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'position'];

    public function boardList(): BelongsTo
    {
        return $this->belongsTo(BoardList::class);
    }
}
