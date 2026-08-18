<?php

use App\Livewire\Admin\Hours;
use App\Livewire\Admin\Menu;
use App\Livewire\Admin\Users;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::redirect('/', '/admin/users')->name('index');

        Route::livewire('users', Users::class)->name('users');
        Route::livewire('menu', Menu::class)->name('menu');
        Route::livewire('hours', Hours::class)->name('hours');
    });
