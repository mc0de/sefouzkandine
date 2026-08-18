<?php

use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', StorefrontController::class)->name('home');
Route::get('/en', StorefrontController::class)->name('home.en');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/admin.php';
require __DIR__.'/settings.php';
