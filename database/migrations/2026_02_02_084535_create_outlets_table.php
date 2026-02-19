<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // MX001, MX002
            $table->string('name');
            $table->text('address');
            $table->string('city');
            $table->string('phone')->nullable();
            $table->enum('type', ['ruko', 'warehouse'])->default('ruko');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};