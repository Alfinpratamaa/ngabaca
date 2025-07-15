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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
            $table->string('transaction_id')->unique()->nullable();
            $table->decimal('total_price', 10, 2);
            $table->string('currency', 3)->default('IDR');
            $table->string('payment_method', 50)->nullable();
            $table->string('proof_url')->nullable();
            $table->enum('status', ['pending', 'verified', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->string('payment_status_gateway', 50)->nullable();
            $table->jsonb('payment_gateway_response')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
