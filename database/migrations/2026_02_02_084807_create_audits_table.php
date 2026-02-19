<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_number')->unique(); // AUD-20260201-001
            $table->foreignId('outlet_id')->constrained()->onDelete('restrict');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('audited_by')->constrained('users')->onDelete('restrict');
            $table->integer('system_quantity'); // Stok di sistem
            $table->integer('physical_quantity'); // Stok fisik yang ditemukan
            $table->integer('difference')->storedAs('physical_quantity - system_quantity');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('audited_at');
            $table->timestamps();
            
            $table->index(['outlet_id', 'audited_at']);
            $table->index('audit_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};