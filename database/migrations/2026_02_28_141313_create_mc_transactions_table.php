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
        Schema::create('mc_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->enum('order_type', ['online', 'mitra', 'offline']);
            $table->foreignId('partner_id')->nullable()->constrained('mc_partners')->nullOnDelete();
            $table->enum('status', ['pending', 'proses', 'selesai', 'diambil'])->default('pending');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mc_transactions');
    }
};
