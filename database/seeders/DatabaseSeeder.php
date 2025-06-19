<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * cara pake nya tinggal di uncomment aja seeder mana yang mau diseed terus jalanin php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            // CategorySeeder::class,
            // BookSeeder::class,
            // OrderSeeder::class,
            // OrderItemSeeder::class,
            // PaymentSeeder::class,
        ]);
    }
}
