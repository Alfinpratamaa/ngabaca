<x-layouts.admin :title="__('Add Book')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <h1 class="text-2xl font-bold mb-4">{{ __('Add New Book') }}</h1>
        <form action="{{ route('admin.book.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="form-control">
                <label for="title" class="label">{{ __('Title') }}</label>
                <input type="text" name="title" id="title" class="input input-bordered w-full" required>
            </div>
            <div class="form-control">
                <label for="author" class="label">{{ __('Author') }}</label>
                <input type="text" name="author" id="author" class="input input-bordered w-full" required>
            </div>
            <div class="form-control">
                <label for="published_year" class="label">{{ __('Published Year') }}</label>
                <input type="number" name="published_year" id="published_year" class="input input-bordered w-full" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Create Book') }}</button>
        </form>
    </div>
</x-layouts.admin>
