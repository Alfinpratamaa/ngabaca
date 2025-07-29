<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        if (empty($user->password)) {
            $this->redirect('/settings/profile');
        }
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
            Auth::user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            $this->reset('current_password', 'password', 'password_confirmation');

            $this->dispatch('password-updated');
        } catch (ValidationException $e) {
            dd($e->getMessage());
            // $this->reset('current_password', 'password', 'password_confirmation');

            // $this->dispatch('password-update-failed', [
            //     'messages' => $e->validator->errors()->all(),
            // ]);
            // return;
        }
    }
}; ?>

<section class="w-full bg-surface">
    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <div>
                <label class="block text-secondary font-semibold mb-2">{{ __('Current password') }}</label>
                <input class="p-2 border border-gray-300 text-secondary w-full rounded-md placeholder:text-gray-500"
                    placeholder="{{ __('Current password') }}" wire:model="current_password" type="password" required
                    autocomplete="current-password" />
            </div>
            <div>
                <label class="block text-secondary font-semibold mb-2">{{ __('New Password') }}</label>
                <input class="p-2 border border-gray-300 text-secondary w-full rounded-md placeholder:text-gray-500"
                    placeholder="{{ __('New Password') }}" wire:model="password" type="password" required
                    autocomplete="new-password" />
            </div>
            <div>
                <label class="block text-secondary font-semibold mb-2">{{ __('Confirm new password') }}</label>
                <input class="p-2 border border-gray-300 text-secondary w-full rounded-md placeholder:text-gray-500"
                    placeholder="{{ __('Confirm new password') }}" wire:model="password_confirmation"
                    type="password" required autocomplete="new-password" />
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary/75 p-2 rounded-md text-white font-semibold">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </x-settings.layout>
</section>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('password-updated', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Password berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'OK',
            }).then(() => {
                window.location.href = '/settings/profile';
            });
        });

        Livewire.on('password-update-failed', (data) => {
            let allMessages = Array.isArray(data.messages) ? data.messages.join('\n') :
                'Terjadi kesalahan';

            Swal.fire({
                title: 'Gagal!',
                text: allMessages,
                icon: 'error',
                confirmButtonText: 'Coba Lagi',
            });
        });
    });
</script>
