<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatar;
    public $currentAvatar = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->currentAvatar = Auth::user()->avatar;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);

        // Handle avatar upload
        if ($this->avatar) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $this->avatar->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
            $this->currentAvatar = $avatarPath;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Reset avatar input
        $this->reset('avatar');

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Delete the current avatar.
     */
    public function deleteAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);
        $this->currentAvatar = null;

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name, email address, and avatar')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            <!-- Avatar Section -->
            <div class="space-y-4">
                <flux:label>{{ __('Avatar') }}</flux:label>

                <div class="flex items-center space-x-6">
                    <!-- Current Avatar Display -->
                    <div class="shrink-0">
                        @if ($currentAvatar)
                            <img class="h-20 w-20 object-cover rounded-full border-2 border-gray-200"
                                src="{{ Storage::url($currentAvatar) }}" alt="{{ __('Current avatar') }}">
                        @else
                            <div class="h-20 w-20 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Avatar Actions -->
                    <div class="space-y-2">
                        <!-- Upload New Avatar -->
                        <div>
                            <flux:input type="file" wire:model="avatar" accept="image/*" class="text-sm">
                            </flux:input>
                            @error('avatar')
                                <flux:text class="mt-1 text-red-600 text-sm">{{ $message }}</flux:text>
                            @enderror
                        </div>

                        <!-- Delete Avatar Button -->
                        @if ($currentAvatar)
                            <flux:button type="button" variant="danger" size="sm" wire:click="deleteAvatar"
                                wire:confirm="{{ __('Are you sure you want to delete your avatar?') }}">
                                {{ __('Delete Avatar') }}
                            </flux:button>
                        @endif
                    </div>
                </div>

                <!-- Avatar Preview -->
                @if ($avatar)
                    <div class="mt-4">
                        <flux:text class="text-sm text-gray-600 mb-2">{{ __('Preview:') }}</flux:text>
                        <img class="h-20 w-20 object-cover rounded-full border-2 border-blue-200"
                            src="{{ $avatar->temporaryUrl() }}" alt="{{ __('Avatar preview') }}">
                    </div>
                @endif
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus
                autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer"
                                wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
