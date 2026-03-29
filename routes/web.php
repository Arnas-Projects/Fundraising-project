<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DashboardController;
use App\Models\Donation;
use App\Models\Story;
use App\Http\Controllers\TagController;

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
    if (auth()->check()) {
        return redirect()->route('stories.index');
    }

    $featuredStories = Story::query()
        ->whereIn('status', ['active', 'closed'])
        ->with(['tags'])
        ->withSum('donations as total_donated', 'amount')
        ->latest()
        ->take(3)
        ->get();

    $storyCount = Story::query()
        ->whereIn('status', ['active', 'closed'])
        ->count();

    $raisedTotal = Donation::query()->sum('amount');

    return view('welcome', compact('featuredStories', 'storyCount', 'raisedTotal'));
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Istorijų CRUD maršrutai su autentifikacija
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

// Titulinio puslapio maršrutas, rodantis visas istorijas
Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');

// Individualios istorijos peržiūros maršrutas
Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');

Route::post('/stories/{story}/donate', [DonationController::class, 'store'])
    ->middleware('auth')
    ->name('donations.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Maršrutas, rodantis istorijas pagal tagą
// Route::get('/tags/{tag}', [StoryController::class, 'byTag'])->name('tags.show');

Route::post('/stories/{story}/like', [StoryController::class, 'toggleLike'])
    ->name('stories.like');

Route::post('/stories/{story}/comments', [StoryController::class, 'storeComment'])
    ->name('stories.comments');

// ADMIN ROUTES
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin panel
    Route::get('/admin', [StoryController::class, 'adminIndex'])->name('admin.index');
    
    // Admin approve, reject, delete routes
    Route::post('/admin/stories/{story}/approve', [StoryController::class, 'approveAdmin'])->name('admin.approve');
    Route::delete('/admin/stories/{story}', [StoryController::class, 'destroyAdmin'])->name('admin.delete');
    Route::post('/admin/stories/{story}/reject', [StoryController::class, 'rejectAdmin'])->name('admin.reject');
    
    // ADMIN HASHTAG MANAGEMENT
    // Admin tag storage and creation routes
    Route::get('/admin/tags', [TagController::class, 'index'])->name('admin.tags.index');
    Route::get('/admin/tags/create', [TagController::class, 'create'])->name('admin.tags.create');
    Route::post('/admin/tags', [TagController::class, 'store'])->name('admin.tags.store');

    // Admin tag edit and update routes
    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit'])->name('admin.tags.edit');
    Route::put('/admin/tags/{tag}', [TagController::class, 'update'])->name('admin.tags.update');

    // Admin tag delete route
    Route::delete('/admin/tags/{tag}', [TagController::class, 'destroy'])->name('admin.tags.destroy');
});


require __DIR__.'/auth.php';

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
