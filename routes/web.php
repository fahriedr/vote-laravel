<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/polls/create', [App\Http\Controllers\PollController::class, 'store'])->name('polls.store');
    Route::post('/polls/update', [App\Http\Controllers\PollController::class, 'update'])->name('polls.update');
    Route::get('/polls/{unique_id}/delete', [App\Http\Controllers\PollController::class, 'destroy'])->name('polls.destroy');
});

Route::get('/vote/{pollUniqeId}', [App\Http\Controllers\VoteController::class, 'show'])->name('vote.show');
Route::post('/vote/{pollId}', [App\Http\Controllers\VoteController::class, 'create'])->name('vote.create');
Route::get('/poll/result/{pollId}', [App\Http\Controllers\PollController::class, 'result'])->name('vote.result');


require __DIR__.'/auth.php';
