<x-layouts.admin :title="__('Add Book')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Ini adalah cara untuk menyertakan komponen Livewire --}}
        @livewire('add-book-form')

    </div>
</x-layouts.admin>