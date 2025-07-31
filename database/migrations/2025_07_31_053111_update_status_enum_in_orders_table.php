<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus constraint lama
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");

        // Tambahkan constraint baru dengan value yang diinginkan
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('pending','diproses', 'dikirim', 'selesai', 'batal'))");

        // Set default jadi 'diproses'
        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'diproses'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('diproses', 'terpenuhi', 'batal'))");
        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'diproses'");
    }
};
