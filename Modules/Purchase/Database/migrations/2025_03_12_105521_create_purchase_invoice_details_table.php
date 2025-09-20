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
        Schema::create('purchase_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('invoice_id');
            $table->integer('qty')->default(1);
            $table->integer('sales_qty')->default(0);
            $table->double('admin_buy_price')->default(0);
            $table->double('buy_price')->default(0);
            $table->double('admin_sub_total')->default(0);
            $table->double('sub_total')->default(0);
            $table->integer('batch_no')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoice_detailss');
    }
};
