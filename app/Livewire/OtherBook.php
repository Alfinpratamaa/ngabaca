<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;

class OtherBook extends Component
{
    public $book;

    public function mount($book)
    {
        $this->book = $book;
    }
    
    public function render(){
        $relatedBooks = Book::where('category_id', $this->book->category_id)
                            ->where('id', '!=', $this->book->id)
                            ->latest()
                            ->take(6)
                            ->get();

        return view('livewire.other-book', [
            'books' => $relatedBooks,
        ]);
    }
}
