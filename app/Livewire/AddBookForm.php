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
    public $slug;
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
            'slug' => 'nullable|string|max:255|unique:books,slug',
        ];
    }

    // Method untuk generate slug secara real-time (opsional)
    public function updatedTitle()
    {
        if (!empty($this->title)) {
            $this->slug = Book::generateUniqueSlug($this->title);
        }
    }

    // Method untuk manual generate slug
    public function generateSlug()
    {
        if (!empty($this->title)) {
            $this->slug = Book::generateUniqueSlug($this->title);
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Slug berhasil di-generate: ' . $this->slug
            ]);
        } else {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => 'Harap isi title terlebih dahulu'
            ]);
        }
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

            // Generate unique slug jika belum ada
            if (empty($this->slug)) {
                $book->slug = Book::generateUniqueSlug($this->title);
            } else {
                // Validasi ulang slug yang diinput manual
                $uniqueSlug = Book::generateUniqueSlug($this->slug);
                $book->slug = $uniqueSlug;
            }

            // 1. Tangani Upload Cover Image (Opsional)
            if ($this->cover_image) {
                $book->cover_image_url = $this->cover_image->store('covers', 'public');
            }

            // 2. Tangani Upload Book File atau URL
            if ($this->book_file && $this->book_file instanceof \Illuminate\Http\UploadedFile) {
                // Upload file ke storage lokal
                $filePath = $this->book_file->store('books_private', 'local');
                $book->private_file_path = $filePath;
            } elseif (!empty($this->private_file_path)) {
                // Gunakan URL yang diinput
                $book->private_file_path = $this->private_file_path;
            } else {
                // Error jika tidak ada file atau URL
                $this->addError('book_file', 'Please provide either a book file or a secure URL');
                return;
            }

            $book->save();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Buku berhasil ditambahkan!'
            ]);

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
                'private_file_path',
                'slug'

            ]);
            $this->redirect(route('admin.book.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
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
