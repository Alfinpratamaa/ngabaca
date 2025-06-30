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

new #[Layout('components.layouts.main')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->dispatch('$refresh');

        // Redirect dengan delay kecil
        $user = Auth::user();
        if ($user->role === 'admin') {
            $this->redirect(route('admin.dashboard'));
        } else {
            $this->redirect(route('home'));
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

<div class="relative flex size-full min-h-screen flex-col mt-20 bg-white group/design-root overflow-x-hidden"
    style='font-family: "Plus Jakarta Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
        <div class="flex flex-1 justify-center">
            <div class="layout-content-container flex flex-col w-full max-w-md px-4">

                <!-- Header -->
                <h2 class="text-[#0C161B] tracking-light text-[24px] font-bold leading-tight text-center pb-3 pt-5"
                    style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Selamat Kembali Ke Ngabaca
                </h2>

                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <!-- Login Form -->
                <form wire:submit="login" class="w-full">
                    <!-- Email Input -->
                    <div class="flex flex-col gap-2 py-3">
                        <flux:input wire:model="email" label="Email Address"
                            class="bg-gray-200 rounded-md text-slate-900 [&_label]:text-slate-700" type="email"
                            required autofocus autocomplete="email" placeholder="email@example.com" />
                        <flux:input wire:model="password" :label="__('Password')" type="password" placeholder="Password"
                            required viewable autocomplete="current-password"
                            class="bg-gray-200 rounded-md [&_label]:text-slate-700" />
                    </div>

                    <!-- Remember Me (Optional) -->
                    <div class="flex items-center py-2">
                        <label class="flex items-center">
                            <input wire:model="remember" type="checkbox"
                                class="rounded border-gray-300 text-[#2a9fed] shadow-sm focus:ring-[#2a9fed]">
                            <span class="ml-2 text-sm text-[#4c7b9a]">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <div class="flex items-center justify-center">
                        <flux:button variant="primary" type="submit"
                            class="w-full cursor-pointer bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-md px-4 py-2 transition-colors duration-200">
                            {{ __('Log in') }}
                        </flux:button>
                    </div>
                </form>

                <!-- Forgot Password Link (Optional) -->
                @if (Route::has('password.request'))
                    <div class="py-1 text-center">
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                            {{ __('Forgot your password?') }}
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

            </div>
        </div>
    </div>
</div>
