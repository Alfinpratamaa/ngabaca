<div>
    {{-- Notifikasi (Opsional, tapi bagus untuk feedback) --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Author') }}</th>
                    <th>{{ __('Published Year') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr wire:key="book-{{ $book->id }}" class="hover:bg-gray-100 text-center ">
                        <td>{{ $books->firstItem() + $loop->index }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->published_year }}</td>
                        <td class="p-1">
                            <flux:button.group class="flex justify-end gap-1">
                                <flux:button :href="route('admin.book.edit', $book)" variant="primary" color="sky"
                                    size="sm" icon="pencil" />
                                <flux:button variant="danger" color="red" size="sm" icon="trash" x-data
                                    class="cursor-pointer"
                                    @click="if(confirm('Are you sure you want to delete this book?')) { $wire.deleteBook({{ $book->id }}) }" />
                            </flux:button.group>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">{{ __('No books found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Link paginasi Livewire --}}
    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>
