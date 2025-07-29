<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[
    Layout('components.layouts.main', [
        'title' => 'Login',
        'description' => 'Masuk ke akun Ngabaca Anda untuk melanjutkan.',
    ]),
]
class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    // Props untuk title dan description
    public string $title = 'Selamat Kembali Ke Ngabaca';
    public string $description = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = \App\Models\User::where('email', $this->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak ditemukan. Silakan daftar terlebih dahulu.',
            ]);
        }

        if (!empty($user->google_id)) {
            throw ValidationException::withMessages([
                'email' => 'Email ini hanya bisa login dengan Google. Silakan gunakan Google login atau ubah password.',
            ]);
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Password salah. Silakan coba lagi.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->dispatch('$refresh');

        if (session('after_login_redirect_to') === 'checkout') {
            session()->pull('after_login_redirect_to');
            $this->redirect(route('checkout'))->with('success', 'Berhasil masuk. Silakan lanjutkan checkout Anda.');
            return;
        }

        // Redirect dengan delay kecil
        $user = Auth::user();
        if ($user->role === 'admin') {
            $this->redirect(route('admin.dashboard'));
        } else {
            $this->redirect(session()->pull('url.intended', route('home')));
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div class="relative flex size-full min-h-screen flex-col mt-20 bg-surface group/design-root overflow-x-hidden"
    style='font-family: "Plus Jakarta Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
        <div class="flex flex-1 justify-center">
            <div class="layout-content-container flex flex-col w-full max-w-md px-4">
                <form wire:submit="login" class="flex flex-col p-6">
                    <!-- Header -->
                    <h2 class="text-[#0C161B] tracking-light text-[24px] font-bold leading-tight text-center pb-3 pt-5"
                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        {{ $title }}
                    </h2>

                    <!-- Description (jika ada) --
                    <!-- Email Input -->
                    <div class="flex flex-col gap-2 py-3">
                        <flux:input wire:model.live="email" label="Email Address"
                            class="bg-gray-200 rounded-md text-slate-900 [&_label]:text-slate-700" type="email"
                            required autofocus autocomplete="email" placeholder="email@example.com" />
                        <flux:input wire:model.live="password" :label="__('Password')" type="password" placeholder="Password"
                            required viewable autocomplete="current-password"
                            class="bg-gray-200 rounded-md [&_label]:text-slate-700" />
                    </div>

                    <!-- Remember Me (Optional) -->
                    <div class="flex items-center py-2">
                        <label class="flex items-center">
                            <input wire:model.live="remember" type="checkbox"
                                class="rounded border-gray-300 text-[#2a9fed] shadow-sm focus:ring-[#2a9fed]">
                            <span class="ml-2 text-sm text-[#4c7b9a]">{{ __('Ingat Saya') }}</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <div class="flex items-center justify-center">
                        <flux:button variant="primary" type="submit"
                            class="w-full cursor-pointer bg-primary hover:bg-primary/75 text-white font-semibold rounded-md px-4 py-2 transition-colors duration-200">
                            {{ __('Log in') }}
                        </flux:button>
                    </div>
                </form>

                @error('email')
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: @js($message),
                        });
                    </script>
                @enderror

                @error('password')
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: @js($message),
                        });
                    </script>
                @enderror



                <!-- Forgot Password Link (Optional) -->
                @if (Route::has('password.request'))
                    <div class="py-1 text-center">
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                            {{ __('Lupa Password?') }}
                        </a>
                    </div>
                @endif

                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="py-3 text-center">
                        <span class="text-[#4c7b9a] text-sm font-normal leading-normal">Don't have an account? </span>
                        <a href="{{ route('register') }}" wire:navigate
                            class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                            Register
                        </a>
                    </div>
                @endif

                <!-- Divider -->
                <div class="flex items-center py-4">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink mx-4 text-gray-500 text-sm">atau</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Google Login Section -->
                <div class="py-2">

                    <!-- Google Login Button -->
                    <a href="{{ route('auth.google') }}"
                        class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 rounded-md px-4 py-3 text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                        <!-- Google Icon SVG -->
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span>Masuk lewat Google</span>
                    </a>
                </div>



            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:init', function() {
        // Inisialisasi SweetAlert2
        window.Swal = Swal;

        // Tangani event error dari Livewire
        Livewire.on('error', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: message,
            });
        });
    });
</script>
