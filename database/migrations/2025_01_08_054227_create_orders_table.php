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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->string('user_id');
            $table->decimal('charge');
            $table->decimal('amount');
            $table->decimal('quantity');
            $table->decimal('discount')->default(0.00);
            $table->string('payment_status');
            $table->string('adress');
            $table->string('phone');
            $table->string('customer');
            $table->enum('shippingtype',[1,2])->comment('1 = Inside Dhaka, 2 = Outside Dhaka');
            $table->string('couriertype')->nullable();;
            $table->mediumText('couriertrakingid')->nullable();
            $table->string('pickdate')->nullable();
            // $table->string('costomercity')->nullable();
            // $table->mediumText('costomerzone')->nullable();
            $table->string('cityid')->nullable();
            $table->string('zoneid')->nullable();
            $table->string('weight')->nullable();
            $table->longText('note')->nullable();
            $table->enum('status',[1,2,3,4,5,6])->comment('1 = Pending , 2 = Processing , 3 = On The Way , 4 = On Hold , 5 = Complate , 6 = Cancel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
