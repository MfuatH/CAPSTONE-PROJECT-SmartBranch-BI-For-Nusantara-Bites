<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('unit', 50);
            $table->decimal('price_per_unit', 10, 2);
            $table->timestamps();
        });

        Schema::create('product_raw_material', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('raw_material_id')->constrained()->cascadeOnDelete();
            $table->decimal('qty_needed', 10, 2); 
            $table->timestamps();
        });

        Schema::create('raw_material_store', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('raw_material_id')->constrained()->cascadeOnDelete();
            $table->decimal('current_stock', 12, 2)->default(0.00); 
            $table->decimal('minimum_stock', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_material_store');
        Schema::dropIfExists('product_raw_material');
        Schema::dropIfExists('raw_materials');
    }
};