<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'sold_count')) {
                $table->integer('sold_count')->default(0)->after('stock');
            }
            if (!Schema::hasColumn('books', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('sold_count');
            }
            if (!Schema::hasColumn('books', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('is_featured');
            }
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['sold_count', 'is_featured', 'rating']);
        });
    }
};
