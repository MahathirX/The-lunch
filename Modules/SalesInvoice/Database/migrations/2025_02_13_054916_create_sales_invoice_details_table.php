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
        Schema::create('sales_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->date('create_date');
            $table->foreignId('sales_id')->constrained('sales_invoices')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->bigInteger('invoice_id');
            $table->integer('cost');
            $table->integer('qty');
            $table->double('orginal_profit')->default(0);
            $table->double('profit')->default(0);
            $table->integer('batch_no')->default(0);
            $table->enum('status',[1,0])->default(1)->comment('1 == active , 0 == inactive');
            $table->softDeletes();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoice_details');
    }
};
