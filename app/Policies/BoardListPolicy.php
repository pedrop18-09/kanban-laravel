<?php

namespace App\Policies;

use App\Models\BoardList;
use App\Models\User;
use App\Models\Board;
use Illuminate\Auth\Access\Response;

class BoardListPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BoardList $boardList): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Board $board): bool
    {
        return $user->id === $board->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BoardList $boardList): bool
    {
        return $user->id === $boardList->board->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BoardList $boardList): bool
    {
        return $user->id === $boardList->board->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BoardList $boardList): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BoardList $boardList): bool
    {
        return false;
    }
}
