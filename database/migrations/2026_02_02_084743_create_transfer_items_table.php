<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity_requested');
            $table->integer('quantity_sent')->nullable();
            $table->integer('quantity_received')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('stock_transfer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_items');
    }
};