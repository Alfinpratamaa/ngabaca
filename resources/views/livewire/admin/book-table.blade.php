<div>
    {{-- Notifikasi --}}
    @if (session()->has('message'))
        <div id="notification" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
            <button type="button" class="absolute cursor-pointer top-0 right-0 px-4 py-3" onclick="closeNotification()">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif

    <script>
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }
    </script>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Author') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Published Year') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($books as $book)
                    <tr wire:key="book-{{ $book->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $books->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $book->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $book->author }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $book->published_year }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Edit Button -->
                                <flux:button wire:navigate :href="route('admin.book.edit', $book)" variant="primary" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold"
                                    size="sm" icon="pencil" />
                                
                                <!-- Delete Button -->
                                <button type="button" 
                                    onclick="confirmDelete({{ $book->id }})"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ __('No books found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $books->links() }}
    </div>

    {{-- JavaScript untuk SweetAlert --}}
    <script>
        function confirmDelete(bookId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call Livewire method
                    @this.deleteBook(bookId);
                    
                }
            });
        }

        // Listen untuk Livewire events
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('book-deleted', (data) => {
                Swal.fire(
                    'Deleted!',
                    'Book has been deleted successfully.',
                    'success'
                );
            });

            Livewire.on('delete-error', (data) => {
                Swal.fire(
                    'Error!',
                    data[0].message || 'Something went wrong.',
                    'error'
                );
            });
        });
    </script>
</div>