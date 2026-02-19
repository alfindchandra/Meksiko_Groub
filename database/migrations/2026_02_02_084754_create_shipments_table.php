<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique(); // SHP-20260201-001
            $table->foreignId('stock_transfer_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['internal_transfer', 'supplier', 'return'])->default('internal_transfer');
            $table->foreignId('from_outlet_id')->nullable()->constrained('outlets')->onDelete('restrict');
            $table->foreignId('to_outlet_id')->constrained('outlets')->onDelete('restrict');
            $table->enum('status', ['prepared', 'on_the_way', 'delivered'])->default('prepared');
            $table->string('courier_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'to_outlet_id']);
            $table->index('shipment_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};