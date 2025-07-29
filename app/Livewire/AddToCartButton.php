<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class AddToCartButton extends Component
{
    public $book;
    public $cartItems;

    protected $listeners = [
        'cartUpdated' => 'refreshCart',
    ];

    public function mount($book)
    {
        $this->book = $book;
        $this->cartItems = Session::get('cart', []);
    }

    public function refreshCart()
    {
        $this->cartItems = Session::get('cart', []);
    }

    public function addToCart($bookId)
    {
        Log::info('Adding book to cart', ['book_id' => $bookId]);
        $cart = Session::get('cart', []);

        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity']++;
        } else {
            $cart[$bookId] = [
                "id" => $this->book->id,
                "title" => $this->book->title,
                "quantity" => 1,
                "price" => $this->book->price,
                "cover_image_url" => $this->book->cover_image_url,
            ];
        }

        Session::put('cart', $cart);
        $this->cartItems = $cart;
        $this->dispatch('cartUpdated');
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Buku berhasil ditambahkan ke keranjang!']);
    }

    public function increaseQuantity($bookId)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity']++;
            Session::put('cart', $cart);
            $this->cartItems = $cart;
            $this->dispatch('cartUpdated');
        }
    }

    public function decreaseQuantity($bookId)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$bookId])) {
            if ($cart[$bookId]['quantity'] <= 1) {
                // Langsung hapus tanpa konfirmasi untuk decrease
                unset($cart[$bookId]);
                Session::put('cart', $cart);
                $this->cartItems = $cart;
                $this->dispatch('cartUpdated');
                $this->dispatch('showToast', ['type' => 'success', 'message' => 'Buku berhasil dihapus dari keranjang!']);
            } else {
                // Directly decrease quantity if > 1
                $cart[$bookId]['quantity']--;
                Session::put('cart', $cart);
                $this->cartItems = $cart;
                $this->dispatch('cartUpdated');
            }
        }
    }

    public function removeFromCart($bookId)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$bookId])) {
            unset($cart[$bookId]);
            Session::put('cart', $cart);
            $this->cartItems = $cart;
            $this->dispatch('cartUpdated');
            $this->dispatch('showToast', ['type' => 'success', 'message' => 'Buku berhasil dihapus dari keranjang!']);
        } else {
            $this->dispatch('showToast', ['type' => 'error', 'message' => 'Buku tidak ditemukan di keranjang!']);
        }
    }





    public function render()
    {
        $this->cartItems = Session::get('cart', []);
        return view('livewire.add-to-cart-button');
    }
}
