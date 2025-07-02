<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class AddBookForm extends Component
{
    use WithFileUploads;

    public $title;
    public $author;
    public $published_year;
    public $category_id;
    public $description;
    public $price;
    public $stock = 0;
    public $cover_image;
    public $book_file;
    public $private_file_path;

    public $categories;

    public function mount()
    {
        $this->categories = Category::all();
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'book_file' => 'nullable|file|max:10240|mimes:pdf,epub,mobi',
            'private_file_path' => 'nullable|url',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            $book = new Book();
            $book->title = $this->title;
            $book->author = $this->author;
            $book->published_year = $this->published_year;
            $book->category_id = $this->category_id;
            $book->description = $this->description;
            $book->price = $this->price;
            $book->stock = $this->stock;

            // 1. Tangani Upload Cover Image (Opsional)
            if ($this->cover_image) {
                $filePath = $this->cover_image->store('covers', 'public');
                $book->cover_image_url = config('app.url') . '/storage/covers/' . basename($filePath);
            }

            // 2. Tangani Upload Book File atau URL
            if ($this->book_file && $this->book_file instanceof \Illuminate\Http\UploadedFile) {
                // Upload file ke storage lokal dan simpan dengan app URL
                $filePath = $this->book_file->store('books_private', 'local');
                $book->private_file_path = config('app.url') . '/storage/books_private/' . basename($filePath);
            } elseif (!empty($this->private_file_path)) {
                // Gunakan URL yang diinput
                $book->private_file_path = $this->private_file_path;
            } else {
                // Jika tidak ada file atau URL, tetap lanjutkan eksekusi
                $book->private_file_path = null;
            }

            $book->save();

            // Dispatch event untuk SweetAlert
            $this->dispatch('book-created');

            $this->reset([
                'title',
                'author',
                'published_year',
                'category_id',
                'description',
                'price',
                'stock',
                'cover_image',
                'book_file',
                'private_file_path'
            ]);

            // Use session flash and regular redirect instead of navigate
            session()->flash('success', 'Book created successfully!');

            // Remove the navigate parameter or use redirectRoute
            return redirect()->route('admin.book.index');
        } catch (\Exception $e) {
            $this->dispatch('book-create-error', ['message' => 'Error creating book: ' . $e->getMessage()]);
            Log::error('Error saving book: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
        }
    }

    public function render()
    {
        return view('livewire.add-book-form', [
            'categories' => $this->categories,
        ]);
    }
}
