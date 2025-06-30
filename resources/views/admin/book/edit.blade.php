<!-- filepath: c:\Users\alfin\Desktop\ngabaca\resources\views\admin\book\edit.blade.php -->
<x-layouts.admin :title="__('Edit Book')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @livewire('edit-book-form', ['bookId' => $book->id])
    </div>
</x-layouts.admin>