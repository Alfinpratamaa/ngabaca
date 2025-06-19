<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $categories = [
            'Fiksi Ilmiah',
            'Fantasi',
            'Horor',
            'Misteri dan Thriller',
            'Romansa',
            'Biografi',
            'Sejarah',
            'Pendidikan',
            'Teknologi',
            'Kesehatan',
            'Seni dan Desain',
            'Agama dan Spiritualitas',
            'Sosial dan Politik',
            'Psikologi',
            'Sains',
            'Bisnis dan Ekonomi',
            'Pengembangan Diri',
            'Kuliner',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName), 
            ]);
        }
    }
}
