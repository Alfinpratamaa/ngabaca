<div class="flex flex-col p-8">
    <div class="mb-6">
        <flux:heading size="xl" level="1">{{ __('Settings') }}</flux:heading>
        <flux:subheading size="lg">{{ __('Manage your profile and account settings') }}</flux:subheading>
    </div>


    <div class="flex items-start gap-8 max-md:flex-col">
        <div class="w-full pb-4 md:w-[220px] md:flex-shrink-0">
            <flux:navlist>
                <flux:navlist.item class="text-secondary" :href="route('settings.profile')" wire:navigate>
                    {{ __('Profile') }}
                </flux:navlist.item>
                @if (auth()->user()->password)
                    <flux:navlist.item :href="route('settings.password')" wire:navigate>
                        {{ __('Password') }}
                    </flux:navlist.item>
                @endif
            </flux:navlist>
        </div>

        <flux:separator class="md:hidden" />

        <div class="flex-1 max-md:pt-6">
            @if (isset($heading))
                <flux:heading class="mb-2">{{ $heading }}</flux:heading>
            @endif

            @if (isset($subheading))
                <flux:subheading class="mb-5">{{ $subheading }}</flux:subheading>
            @endif

            <div class="w-full max-w-xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
