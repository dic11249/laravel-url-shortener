<?php

use App\Models\Url;
use Inertia\Inertia;
use App\Http\Resources\UrlResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/** dashboard */
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/** profile */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/** url */
Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
Route::get('/urls/create', [UrlController::class, 'create'])->name('urls.create');
Route::post('/urls/store', [UrlController::class, 'store'])->name('urls.store');


require __DIR__.'/auth.php';

Route::get('/r/{code}', [RedirectController::class, 'redirect'])
    ->where('code', '[a-zA-Z0-9]{3,10}')
    ->name('redirect');
