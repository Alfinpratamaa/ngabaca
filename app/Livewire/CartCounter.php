<?php

namespace App\Livewire;

use Livewire\Component;

class CartCounter extends Component
{

    public $itemCount = 0;

    // Listener untuk event 'cartUpdated'
    // Setiap kali event ini di-dispatch, metode updateCartCount akan dipanggil
    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        // Ambil data keranjang dari session
        $cart = session()->get('cart', []);
        $this->itemCount = count($cart); // Hitung jumlah item unik di keranjang
        // Jika Anda ingin menghitung total kuantitas, Anda bisa lakukan loop:
        // $totalQuantity = 0;
        // foreach ($cart as $item) {
        //     $totalQuantity += $item['quantity'];
        // }
        // $this->itemCount = $totalQuantity;
    }
    public function render()
    {
        return view('livewire.cart-counter');
    }
}
