<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookCard extends Component
{
    /**
     * The book data.
     *
     * @var array
     */
    public $book;

    /**
     * Create a new component instance.
     */
    public function __construct(array $book)
    {
        $this->book = $book;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.book-card');
    }
}