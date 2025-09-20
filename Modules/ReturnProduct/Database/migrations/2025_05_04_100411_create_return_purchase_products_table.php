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
        Schema::create('return_purchase_products', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('create_date');
            $table->double('total_amount');
            $table->double('due_amount')->nullable();
            $table->longText('note')->nullable();
            $table->integer('paid_amount')->nullable();
            $table->integer('discount')->nullable();
            $table->enum('status',[0,1])->comment('0 = Pending , 1 = Publish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_purchase_products');
    }
};
