<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $books = [
            [
            'title' => 'Petualangan di Ruang Angkasa',
            'author' => 'Astro Penjelajah',
            'description' => 'Kisah epik penjelajahan galaksi dan penemuan peradaban baru.',
            'price' => 125000.00,
            'stock' => 50,
            'cover_image_url' => 'https://picsum.photos/seed/book1/200/300',
            'preview_file_url' => 'https://example.com/preview_space.pdf',
            'secure_file_url' => 'https://example.com/secure_space.pdf',
            'category_name' => $categories->random()->name, 
            ],
            [
            'title' => 'Misteri Rumah Tua',
            'author' => 'Detektif Anonim',
            'description' => 'Sebuah thriller psikologis yang akan membuat Anda terus menebak.',
            'price' => 98500.00,
            'stock' => 30,
            'cover_image_url' => 'https://picsum.photos/seed/book2/200/300',
            'preview_file_url' => 'https://example.com/preview_mystery.pdf',
            'secure_file_url' => 'https://example.com/secure_mystery.pdf',
            'category_name' => $categories->random()->name,
            ],
            [
            'title' => 'Resep Rahasia Nenek',
            'author' => 'Chef Tradisional',
            'description' => 'Kumpulan resep warisan yang lezat dan mudah dibuat.',
            'price' => 75000.00,
            'stock' => 20,
            'cover_image_url' => 'https://picsum.photos/seed/book3/200/300',
            'preview_file_url' => 'https://example.com/preview_cookbook.pdf',
            'secure_file_url' => 'https://example.com/secure_cookbook.pdf',
            'category_name' => $categories->random()->name,
            ],
            [
            'title' => 'Bangkit dari Kegagalan',
            'author' => 'Motivator Sukses',
            'description' => 'Panduan praktis untuk mengubah kegagalan menjadi batu loncatan kesuksesan.',
            'price' => 110000.00,
            'stock' => 40,
            'cover_image_url' => 'https://picsum.photos/seed/book4/200/300',
            'preview_file_url' => 'https://example.com/preview_selfhelp.pdf',
            'secure_file_url' => 'https://example.com/secure_selfhelp.pdf',
            'category_name' => $categories->random()->name,
            ],
        ];

        // Generate 100 additional books using factory-like approach
        $bookTitles = [
            'Pemrograman Modern', 'Algoritma dan Struktur Data', 'Desain Database', 'Keamanan Siber',
            'Machine Learning Praktis', 'Artificial Intelligence', 'Cloud Computing', 'DevOps Fundamentals',
            'Mobile App Development', 'Web Development', 'Sistem Operasi', 'Jaringan Komputer',
            'Digital Marketing', 'E-Commerce Strategy', 'Social Media Marketing', 'Content Creation',
            'Photography Basics', 'Video Editing', 'Graphic Design', 'UI/UX Design',
            'Entrepreneurship 101', 'Business Strategy', 'Financial Management', 'Leadership Skills',
            'Time Management', 'Productivity Hacks', 'Personal Development', 'Public Speaking',
            'Komunikasi Efektif', 'Negosiasi Bisnis', 'Manajemen Proyek', 'Analisis Data',
            'Statistik Terapan', 'Research Methods', 'Academic Writing', 'Critical Thinking',
            'Creative Writing', 'Poetry Collection', 'Short Stories', 'Novel Writing',
            'Health and Wellness', 'Nutrition Guide', 'Fitness Training', 'Mental Health',
            'Mindfulness Practice', 'Meditation Techniques', 'Yoga for Beginners', 'Stress Management',
            'Travel Guide Indonesia', 'Cultural Studies', 'History of Indonesia', 'World History',
            'Geography Fundamentals', 'Environmental Science', 'Climate Change', 'Sustainability',
            'Renewable Energy', 'Green Technology', 'Organic Farming', 'Urban Planning'
        ];

        $authors = [
            'Dr. Ahmad Rahman', 'Prof. Siti Nurhaliza', 'Ir. Budi Santoso', 'Dr. Maya Sari',
            'Prof. Andi Wijaya', 'Dr. Rina Kusuma', 'Ir. Dedi Pratama', 'Dr. Lina Handayani',
            'Prof. Rudi Hermawan', 'Dr. Dewi Lestari', 'Ir. Arif Rachman', 'Dr. Nina Kartini',
            'Prof. Agus Setiawan', 'Dr. Eka Putri', 'Ir. Bayu Nugroho', 'Dr. Yuni Astuti',
            'Prof. Hadi Susanto', 'Dr. Fitri Amalia', 'Ir. Dian Permata', 'Dr. Adi Kurniawan',
            'Prof. Sari Indah', 'Dr. Gita Maharani', 'Ir. Rizki Pratama', 'Dr. Wulan Dari'
        ];

        $descriptions = [
            'Panduan lengkap dan praktis untuk memahami konsep dasar hingga tingkat lanjut.',
            'Buku yang komprehensif dengan contoh studi kasus dan implementasi nyata.',
            'Referensi terbaik dengan pendekatan pembelajaran yang mudah dipahami.',
            'Panduan step-by-step dengan ilustrasi dan diagram yang menarik.',
            'Buku wajib untuk profesional dan mahasiswa yang ingin menguasai bidang ini.',
            'Kombinasi teori dan praktik yang disajikan dengan bahasa yang mudah dimengerti.',
            'Kumpulan tips dan trik dari para ahli di bidangnya.',
            'Panduan praktis dengan pendekatan modern dan up-to-date.',
            'Buku yang mengupas tuntas dengan metode pembelajaran yang efektif.',
            'Referensi utama dengan studi kasus dari berbagai industri.'
        ];

        for ($i = 5; $i <= 104; $i++) {
            $books[] = [
            'title' => $bookTitles[($i - 5) % count($bookTitles)] . ' ' . ($i > 54 ? 'Volume ' . ceil(($i - 54) / 10) : ''),
            'author' => $authors[($i - 5) % count($authors)],
            'description' => $descriptions[($i - 5) % count($descriptions)],
            'price' => rand(50000, 200000),
            'stock' => rand(10, 100),
            'cover_image_url' => 'https://picsum.photos/seed/book' . $i . '/200/300',
            'preview_file_url' => 'https://example.com/preview_book' . $i . '.pdf',
            'secure_file_url' => 'https://example.com/secure_book' . $i . '.pdf',
            'category_name' => $categories->random()->name,
            ];
        }

        foreach ($books as $bookData) {
            $category = $categories->where('name', $bookData['category_name'])->first();

            if ($category) {
                Book::create([
                    'title' => $bookData['title'],
                    'author' => $bookData['author'],
                    'description' => $bookData['description'],
                    'price' => $bookData['price'],
                    'stock' => $bookData['stock'],
                    'cover_image_url' => $bookData['cover_image_url'],
                    'preview_file_url' => $bookData['preview_file_url'],
                    'secure_file_url' => $bookData['secure_file_url'],
                    'category_id' => $category->id, 
                ]);
            } else {
                $this->command->warn("Kategori '{$bookData['category_name']}' tidak ditemukan untuk buku '{$bookData['title']}'.");
            }
        }
    }
}
