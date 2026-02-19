<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('outlet_id')->constrained()->onDelete('restrict');
            $table->integer('quantity')->default(0);
            $table->integer('reserved')->default(0); // Untuk barang yang sedang dalam transfer
            $table->integer('available')->storedAs('quantity - reserved'); // Virtual column
            $table->timestamps();
            
            // Composite unique: satu produk hanya punya satu record per outlet
            $table->unique(['product_id', 'outlet_id']);
            $table->index(['outlet_id', 'quantity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};