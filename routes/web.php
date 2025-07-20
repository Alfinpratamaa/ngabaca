<?php

use App\Livewire\Home;
use App\Models\Payment;
use Livewire\Volt\Volt;
use App\Livewire\CartPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MidtransController;
use App\Livewire\CheckoutPage;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('about-us', function () {
    return view('about');
})->name('about');

Route::get('contact-us', function () {
    return view('contact');
})->name('contact');

Route::get('catalog', function () {
    return view('catalog');
})->name('catalog');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('book/{slug}', [BookController::class, 'show'])->name('book.show');

Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Route::get('/verification', function () {
        $user = Auth::user();
        if ($user->is_phone_verified === true || $user->role === 'admin') {
            return redirect()->route('home');
        }
        return view('verification');
    })->name('verification');
});

Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler'])->name('midtrans.notification');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::resource('admin/book', BookController::class)->names('admin.book');
    Route::resource('admin/user', AdminUserController::class)->names('admin.user');
    Route::resource('admin/order', OrderController::class)->names('admin.order');
    Route::resource('admin/payment', PaymentController::class)->names('admin.payment');
    Route::resource('admin/category', CategoryController::class)->names('admin.category');
});

require __DIR__ . '/auth.php';
