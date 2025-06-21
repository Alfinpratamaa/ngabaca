<x-layouts.admin :title="__('Book Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">{{ __('Book Admin') }}</h1>
            <a href="{{ route('admin.book.create') }}" class="btn btn-primary">{{ __('Create Book') }}</a>
        </div>
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
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->published_year }}</td>
                            <td>
                                <div class="flex gap-2 items-center">
                                        <flux:button
                                            :href="route('admin.book.edit', $book)"
                                            class="btn btn-secondary"
                                            icon="pencil"
                                            :label="__('Edit')"
                                        >Edit</flux:button>
                                        <flux:button
                                            :href="route('admin.book.destroy', $book)"
                                            class="btn btn-error"
                                            icon="trash"
                                            :label="__('Delete')"
                                        >Delete</flux:button>
                                </div>
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
    </div>
</x-layouts.admin>
