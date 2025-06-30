<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditBookForm extends Component
{
    use WithFileUploads;

    public $bookId;
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
    public $current_cover_image;
    public $current_book_file;

    public $categories;

    public function mount($bookId)
    {
        $this->bookId = $bookId;
        $this->categories = Category::all();

        // Load existing book data
        $book = Book::findOrFail($bookId);
        $this->title = $book->title;
        $this->author = $book->author;
        $this->published_year = $book->published_year;
        $this->category_id = $book->category_id;
        $this->description = $book->description;
        $this->price = $book->price;
        $this->stock = $book->stock;
        $this->current_cover_image = $book->cover_image_url;
        $this->current_book_file = $book->private_file_path;
        $this->private_file_path = $book->private_file_path;
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
            $book = Book::findOrFail($this->bookId);
            $book->title = $this->title;
            $book->author = $this->author;
            $book->published_year = $this->published_year;
            $book->category_id = $this->category_id;
            $book->description = $this->description;
            $book->price = $this->price;
            $book->stock = $this->stock;

            // Handle Cover Image Upload
            if ($this->cover_image) {
                // Delete old cover image if exists
                if ($book->cover_image_url) {
                    Storage::disk('public')->delete($book->cover_image_url);
                }
                $book->cover_image_url = $this->cover_image->store('covers', 'public');
            }

            // Handle Book File Upload or URL
            if ($this->book_file && $this->book_file instanceof \Illuminate\Http\UploadedFile) {
                // Delete old book file if exists
                if ($book->private_file_path && !filter_var($book->private_file_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('local')->delete($book->private_file_path);
                }
                $filePath = $this->book_file->store('books_private', 'local');
                $book->private_file_path = $filePath;
            } elseif (!empty($this->private_file_path) && $this->private_file_path !== $this->current_book_file) {
                // Update with new URL
                $book->private_file_path = $this->private_file_path;
            }

            $book->save();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Book updated successfully!'
            ]);

            return $this->redirect(route('admin.book.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
            Log::error('Error updating book: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
        }
    }

    public function deleteCoverImage()
    {
        try {
            $book = Book::findOrFail($this->bookId);
            if ($book->cover_image_url) {
                Storage::disk('public')->delete($book->cover_image_url);
                $book->cover_image_url = null;
                $book->save();
                $this->current_cover_image = null;

                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => 'Cover image deleted successfully!'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Failed to delete cover image'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.edit-book-form', [
            'categories' => $this->categories,
        ]);
    }
}
