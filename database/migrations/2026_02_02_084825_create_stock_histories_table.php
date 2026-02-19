<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('outlet_id')->constrained()->onDelete('restrict');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer_in', 'transfer_out']);
            $table->integer('quantity_before');
            $table->integer('quantity_change');
            $table->integer('quantity_after');
            $table->string('reference_type')->nullable(); // StockTransfer, Audit, Manual
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'outlet_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};