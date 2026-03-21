<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DashboardController;
use App\Models\Story;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create'); // shows form
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store'); // saves form
    Route::get('stories/{story}/edit', [StoryController::class, 'edit'])->name('stories.edit'); // shows edit form
    Route::put('stories/{story}', [StoryController::class, 'update'])->name('stories.update'); // saves edit form
    Route::delete('stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy'); // deletes story
});

Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');

Route::get('/', [StoryController::class, 'index'])->name('stories.index');

Route::post('/stories/{story}/donate', [DonationController::class, 'store'])
    ->middleware('auth')
    ->name('donations.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/tags/{tag}', [StoryController::class, 'byTag'])->name('tags.show');

Route::post('/stories/{story}/like', [StoryController::class, 'toogleLike'])
    ->name('stories.like');

Route::post('/stories/{story}/comments', [StoryController::class, 'storeComment'])
    ->name('stories.comments');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [StoryController::class, 'adminIndex'])->name('admin.index');
    Route::post('/admin/stories/{story}/approve', [StoryController::class, 'approveAdmin'])->name('admin.approve');
    Route::delete('/admin/stories/{story}', [StoryController::class, 'destroyAdmin'])->name('admin.delete');
});


require __DIR__.'/auth.php';
