<x-layouts.admin :title="__('Book Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">{{ __('Book Admin') }}</h1>
            <a href="{{ route('admin.book.create') }}" class=" p-2 rounded-lg font-bold bg-green-500 hover:bg-green-300">{{ __('Create Book') }}</a>
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
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->title }}</td>
                            <td>{{ $order->author }}</td>
                            <td>{{ $order->published_year }}</td>
                            <td>
                                <div class="flex gap-2 items-center">
                                        <flux:button
                                            :href="route('admin.book.edit', $order)"
                                            class="btn btn-secondary"
                                            icon="pencil"
                                            :label="__('Edit')"
                                        >Edit</flux:button>
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
