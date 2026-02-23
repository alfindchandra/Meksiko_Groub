<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_discount_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('min_quantity');
            $table->decimal('discount_percentage', 5, 2); // 0.00 - 100.00
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'min_quantity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_discount_tiers');
    }
};