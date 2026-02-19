<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique(); // TRF-20260201-001
            $table->foreignId('from_outlet_id')->constrained('outlets')->onDelete('restrict');
            $table->foreignId('to_outlet_id')->constrained('outlets')->onDelete('restrict');
            $table->foreignId('requested_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('sent_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_transit', 'received'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'from_outlet_id', 'to_outlet_id']);
            $table->index('transfer_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};