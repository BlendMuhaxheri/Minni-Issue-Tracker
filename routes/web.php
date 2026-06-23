<?php

use App\Http\Controllers\Comments\CommentController;
use App\Http\Controllers\Issues\IssueController;
use App\Http\Controllers\Issues\IssueMemberController;
use App\Http\Controllers\Issues\IssueTagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\Tags\TagController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    */
    Route::resource('projects', ProjectController::class);
    Route::resource('issues', IssueController::class);
    Route::resource('tags', TagController::class)->only(['index', 'store']);

    Route::get('/users', UserController::class)->name('users.index');

    /*
    |--------------------------------------------------------------------------
    | AJAX - Tags
    |--------------------------------------------------------------------------
    */
    Route::post('/issues/{issue}/tags', [IssueTagController::class, 'attach'])->name('issues.tags.attach');
    Route::delete('/issues/{issue}/tags/{tag}', [IssueTagController::class, 'detach'])->name('issues.tags.detach');

    /*
    |--------------------------------------------------------------------------
    | AJAX - Comments
    |--------------------------------------------------------------------------
    */
    Route::get('/issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
    Route::post('/issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

    /*
    |--------------------------------------------------------------------------
    | BONUS - Members
    |--------------------------------------------------------------------------
    */
    Route::post('/issues/{issue}/members', [IssueMemberController::class, 'attach'])->name('issues.members.attach');
    Route::delete('/issues/{issue}/members/{user}', [IssueMemberController::class, 'detach'])->name('issues.members.detach');
});

require __DIR__.'/auth.php';
