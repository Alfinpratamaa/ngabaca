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
    public $cover_image_deleted = false; // Flag untuk track apakah image sudah dihapus
    public $book_file_deleted = false; // Flag untuk track apakah book file sudah dihapus

    public $categories;

    public function mount($bookId)
    {

        $this->bookId = $bookId;
        $this->categories = Category::all();

        // Load existing book data
        $book = Book::findOrFail($bookId);



        Log::info('Loading book data for editing', [
            'book_id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'cover_image_url' => $book->cover_image_url,
            'private_file_path' => $book->private_file_path,
        ]);

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
        $this->dispatch('book-updating');

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

            // Handle Cover Image Upload - hanya jika ada image baru
            if ($this->cover_image) {
                // Delete old cover image if exists
                if ($book->cover_image_url) {
                    $oldPath = str_replace(config('app.url') . '/storage/', '', $book->cover_image_url);
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $this->cover_image->store('covers', 'public');
                $book->cover_image_url = config('app.url') . '/storage/' . $path;
            }
            // Jika cover image sudah dihapus sebelumnya, set ke null
            elseif ($this->cover_image_deleted) {
                $book->cover_image_url = null;
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
            // Jika book file sudah dihapus sebelumnya, set ke null
            elseif ($this->book_file_deleted) {
                $book->private_file_path = null;
            }

            $book->save();

            $this->dispatch('book-updated');

            session()->flash('success', 'Book updated successfully!');

            return redirect()->route('admin.book.index');
        } catch (\Exception $e) {
            $this->dispatch('book-update-error', ['message' => 'Error updating book: ' . $e->getMessage()]);
            Log::error('Error updating book: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
        }
    }

    // Method untuk show konfirmasi delete cover image
    public function confirmDeleteCoverImage()
    {
        $this->dispatch('confirm-delete-cover-image');
    }

    // Method untuk show konfirmasi delete book file
    public function confirmDeleteBookFile()
    {
        $this->dispatch('confirm-delete-book-file');
    }

    // Listeners untuk events dari JavaScript
    protected $listeners = [
        'delete-cover-image' => 'deleteCoverImage',
        'delete-book-file' => 'deleteBookFile'
    ];

    // Method untuk delete cover image LANGSUNG dari database dan storage
    public function deleteCoverImage()
    {
        try {
            $book = Book::findOrFail($this->bookId);

            if ($book->cover_image_url) {
                // Delete file dari storage
                $oldPath = str_replace(config('app.url') . '/storage/', '', $book->cover_image_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                // Update database langsung
                $book->cover_image_url = null;
                $book->save();

                // Update component state
                $this->current_cover_image = null;
                $this->cover_image_deleted = true;

                $this->dispatch('cover-image-deleted');
            }
        } catch (\Exception $e) {
            $this->dispatch('image-delete-error', ['message' => 'Failed to delete cover image']);
            Log::error('Error deleting cover image: ' . $e->getMessage());
        }
    }

    // Method untuk delete book file LANGSUNG dari database dan storage
    public function deleteBookFile()
    {
        try {
            $this->dispatch('book-file-processing');

            $book = Book::findOrFail($this->bookId);

            if ($book->private_file_path && !filter_var($book->private_file_path, FILTER_VALIDATE_URL)) {
                // Delete file dari storage
                if (Storage::disk('local')->exists($book->private_file_path)) {
                    Storage::disk('local')->delete($book->private_file_path);
                }

                // Update database langsung
                $book->private_file_path = null;
                $book->save();

                // Update component state
                $this->current_book_file = null;
                $this->book_file_deleted = true;
                $this->private_file_path = null; // Clear URL input juga

                $this->dispatch('book-file-deleted');
            } elseif ($book->private_file_path && filter_var($book->private_file_path, FILTER_VALIDATE_URL)) {
                // Jika itu URL, langsung hapus dari database
                $book->private_file_path = null;
                $book->save();

                // Update component state
                $this->current_book_file = null;
                $this->book_file_deleted = true;
                $this->private_file_path = null;

                $this->dispatch('book-file-deleted');
            }
        } catch (\Exception $e) {
            $this->dispatch('file-delete-error', ['message' => 'Failed to delete book file']);
            Log::error('Error deleting book file: ' . $e->getMessage());
        }
    }

    // Method untuk restore cover image jika user cancel update
    public function restoreCoverImage()
    {
        try {
            // Reload data dari database untuk mendapatkan current state
            $book = Book::findOrFail($this->bookId);
            $this->current_cover_image = $book->cover_image_url;
            $this->cover_image_deleted = false;

            $this->dispatch('cover-image-restored');
        } catch (\Exception $e) {
            Log::error('Error restoring cover image: ' . $e->getMessage());
        }
    }

    // Method untuk restore book file jika user cancel update
    public function restoreBookFile()
    {
        try {
            // Reload data dari database untuk mendapatkan current state
            $book = Book::findOrFail($this->bookId);
            $this->current_book_file = $book->private_file_path;
            $this->private_file_path = $book->private_file_path;
            $this->book_file_deleted = false;

            $this->dispatch('book-file-restored');
        } catch (\Exception $e) {
            Log::error('Error restoring book file: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-book-form', [
            'categories' => $this->categories,
        ]);
    }
}
