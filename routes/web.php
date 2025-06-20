<?php

use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function(){
    return view('home');
})->name('home');



Route::middleware(['auth','role:pelanggan'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::view('admin', 'admin.dashboard')->name('admin.dashboard');
   Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
