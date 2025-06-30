<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination; // Penting untuk paginasi

class BookTable extends Component
{
    use WithPagination;

    // Properti untuk pencarian atau sorting jika kamu mau di masa depan
    // public $search = '';
    // public $sortField = 'title';
    // public $sortAsc = true;


    public function render()
    {
        // Mengambil buku dengan paginasi melalui Livewire
        $books = Book::paginate(10);

        return view('livewire.admin.book-table', [
            'books' => $books,
        ]);
    }

    // Metode untuk menghapus buku (akan kita panggil dari Alpine.js)
    public function deleteBook($bookId)
    {
        try {
            $book = Book::findOrFail($bookId);
            $book->delete();

            $this->dispatch('book-deleted');
            session()->flash('message', 'Book deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('delete-error', ['message' => 'Failed to delete book.']);
        }
    }
}
