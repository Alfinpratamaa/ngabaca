<!-- filepath: c:\Users\alfin\Desktop\ngabaca\resources\views\admin\book\edit.blade.php -->
<x-layouts.admin :title="__('Edit Book')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @livewire('edit-book-form', ['bookId' => $book->id])
    </div>

    <script>
        // Check if this is the first load
        if (!sessionStorage.getItem('page_refreshed')) {
            // Set the flag to prevent infinite refresh
            sessionStorage.setItem('page_refreshed', 'true');
            // Refresh the page
            window.location.reload();
        } else {
            // Reset the flag after some time for next visit
            setTimeout(() => {
                sessionStorage.removeItem('page_refreshed');
            }, 100);
        }
    </script>
</x-layouts.admin>
