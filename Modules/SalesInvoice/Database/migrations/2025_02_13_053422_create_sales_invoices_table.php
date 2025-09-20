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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id');
            $table->date('create_date');
            $table->string('customer_name');
            $table->double('total_amount');
            $table->double('sub_total');
            $table->double('due_amount')->nullable();
            $table->longText('note')->nullable();
            $table->string('attachment')->nullable();
            $table->string('customer_phone');
            $table->string('customer_address')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('paid_amount')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('previous_due')->nullable();
            $table->enum('status',[0,1])->default(1)->comment('1 = active , 0 = inactive');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
