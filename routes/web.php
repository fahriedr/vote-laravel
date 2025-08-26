<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\PollController::class, 'search'])->name('polls.search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/polls/create', [App\Http\Controllers\PollController::class, 'create'])->name('polls.create');
    Route::post('/polls/store', [App\Http\Controllers\PollController::class, 'store'])->name('polls.store');
    Route::get('/polls/edit/{pollId}', [App\Http\Controllers\PollController::class, 'edit'])->name('polls.edit');
    Route::put('/polls/update/{pollId}', [App\Http\Controllers\PollController::class, 'update'])->name('polls.update');
    Route::delete('/polls/{pollId}/delete', [App\Http\Controllers\PollController::class, 'delete'])->name('polls.delete');

    Route::get('/similarity', [UtilityController::class, 'form'])->name('similarity.form');
    Route::post('/similarity', [UtilityController::class, 'calculate'])->name('similarity.check');
});

Route::get('/vote/{pollUniqeId}', [App\Http\Controllers\VoteController::class, 'show'])->name('vote.show');
Route::post('/vote/{pollId}', [App\Http\Controllers\VoteController::class, 'create'])->name('vote.create');
Route::get('/poll/result/{pollId}', [App\Http\Controllers\PollController::class, 'result'])->name('vote.result');


require __DIR__.'/auth.php';
