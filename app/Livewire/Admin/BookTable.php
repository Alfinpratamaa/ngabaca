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

    // Untuk memastikan paginasi tetap berjalan saat ada perubahan properti
    protected $queryString = ['page'];

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
        Book::destroy($bookId);
        session()->flash('message', 'Book successfully deleted.');
        // Refresh komponen untuk memperbarui daftar buku
        $this->dispatch('$refresh'); 
    }
}