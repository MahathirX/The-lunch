<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_purchase_product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_purchase_id')->constrained('return_purchase_products')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('invoice_id');
            $table->date('create_date');
            $table->integer('cost');
            $table->integer('qty');
            $table->integer('batch_no')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_purchase_product_details');
    }
};
