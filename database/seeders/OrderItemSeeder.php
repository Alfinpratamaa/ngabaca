<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order1 = Order::first(); 
        $book1 = Book::first();   
        $book2 = Book::skip(1)->first(); 

        if (!$order1 || !$book1 || !$book2) {
            $this->command->warn("Tidak cukup Order atau Book untuk membuat OrderItems. Jalankan OrderSeeder dan BookSeeder terlebih dahulu.");
            return;
        }

        OrderItem::create([
            'order_id' => $order1->id,
            'book_id' => $book1->id,
            'quantity' => 2,
            'price_per_item' => $book1->price, 
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'book_id' => $book2->id,
            'quantity' => 1,
            'price_per_item' => $book2->price,
        ]);

        
    }
}
