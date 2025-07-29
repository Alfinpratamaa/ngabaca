<?php

use App\Livewire\Home;
use App\Models\Payment;
use Livewire\Volt\Volt;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Log;

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

Route::post('/midtrans/notification', [MidtransWebhookController::class, 'handle'])->name('midtrans.notification');

// Route untuk redirect setelah pembayaran
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

Route::get('book/{slug}', [BookController::class, 'show'])->name('book.show');

Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::middleware(['auth', 'complete.profile'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::middleware(['verified'])->group(function () {
        Route::get('/checkout', function () {
            Log::info('masuk halaman checkout');
            return view('checkout');
        })->name('checkout');
        Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    });
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
});
Route::middleware(['auth'])->group(function () {

    Route::get('/verification', function () {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'))->with('info', 'Email Anda sudah diverifikasi.');
        }
        return view('verification');
    })->name('verification')->middleware('throttle:6,1');

    Route::post('additional-info/store', function (Request $request) {

        $user = Auth::user();
        $user->storeAddtionalInfo($request->only('phone_number', 'password'));

        return redirect()->route('home')->with('success', 'Informasi tambahan berhasil disimpan.');
    })->name('additional-info.store');

    Route::get('additional-info', function () {
        return view('additional-info');
    })->name('additional-info');
});

Route::middleware(['auth', 'role:pelanggan'])->group(function () {
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
    Route::resource('admin/category', CategoryController::class)->names('admin.category');
});

require __DIR__ . '/auth.php';
