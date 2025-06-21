<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Home;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/', function(){
    return view('home');
})->name('home');

Route::middleware(['auth'])->group(function(){
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


Route::middleware(['auth','role:pelanggan'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::resource('admin/book', BookController::class)->names('admin.book');
    Route::resource('admin/user', AdminUserController::class)->names('admin.user');
    Route::resource('admin/order', OrderController::class)->names('admin.order');
    Route::resource('admin/payment', PaymentController::class)->names('admin.payment');
});




require __DIR__.'/auth.php';
