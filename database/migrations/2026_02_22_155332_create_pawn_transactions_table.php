<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pawn_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('pawn_number')->unique();
            $table->foreignId('outlet_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // petugas
            
            // Customer info
            $table->string('customer_name');
            $table->string('customer_id_number'); // KTP
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();
            
            // Item info
            $table->string('item_name');
            $table->string('item_category'); // emas, elektronik, kendaraan, dll
            $table->text('item_description')->nullable();
            $table->decimal('item_weight', 10, 2)->nullable(); // untuk emas (gram)
            $table->string('item_photos')->nullable(); // JSON array of photo paths
            
            // Transaction amounts
            $table->decimal('appraisal_value', 15, 2); // nilai taksir
            $table->decimal('loan_amount', 15, 2); // pinjaman
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->decimal('interest_rate', 5, 2); // % per bulan
            $table->integer('loan_period_days'); // tenor (hari)
            
            // Status & dates
            $table->enum('status', ['active', 'extended', 'redeemed', 'defaulted'])->default('active');
            $table->date('start_date');
            $table->date('due_date');
            $table->date('redeemed_at')->nullable();
            
            // Redemption
            $table->decimal('total_interest', 15, 2)->nullable();
            $table->decimal('total_payment', 15, 2)->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });

        // Pawn extensions (perpanjangan)
        Schema::create('pawn_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pawn_transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->integer('extension_days');
            $table->decimal('extension_fee', 15, 2);
            $table->date('new_due_date');
            $table->timestamps();
        });

        // Pawn payments (cicilan bunga)
        Schema::create('pawn_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pawn_transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_type', ['interest', 'principal', 'full_redemption']);
            $table->string('payment_method')->default('cash');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pawn_payments');
        Schema::dropIfExists('pawn_extensions');
        Schema::dropIfExists('pawn_transactions');
    }
};