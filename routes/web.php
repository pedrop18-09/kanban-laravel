<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardListController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('boards', BoardController::class);
    Route::resource('boards.lists', BoardListController::class)->only(['store', 'update', 'destroy'])->shallow();
    Route::resource('lists.tasks', TaskController::class)->only(['store', 'update', 'destroy'])->shallow();
    Route::post('/boards/{board}/lists/reorder', [BoardListController::class, 'reorder'])->name('boards.lists.reorder');
    Route::post('/lists/{list}/tasks/reorder', [TaskController::class, 'reorder'])->name('lists.tasks.reorder');
});

require __DIR__.'/auth.php';
