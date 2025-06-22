<x-layouts.admin :title="__('Book Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">{{ __('Book Admin') }}</h1>
            <a href="{{ route('admin.book.create') }}" class="btn btn-primary">{{ __('Create Book') }}</a>
        </div>
        @livewire('admin.book-table')
    </div>
</x-layouts.admin>
