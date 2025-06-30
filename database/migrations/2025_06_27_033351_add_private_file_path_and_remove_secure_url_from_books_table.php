<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Hapus kolom secure_file_url
            $table->dropColumn('secure_file_url');
            // Tambahkan kolom untuk jalur file privat
            $table->string('private_file_path')->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('private_file_path');
            // Tambahkan kembali secure_file_url jika di-rollback
            $table->string('secure_file_url')->nullable(); // Sesuaikan dengan definisi aslinya
        });
    }
};
