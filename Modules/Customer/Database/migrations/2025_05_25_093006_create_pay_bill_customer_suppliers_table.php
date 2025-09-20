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
        Schema::create('pay_bill_customer_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('user_id');
            $table->decimal('total_amount')->default(0);
            $table->decimal('pay_amount')->default(0);
            $table->decimal('due')->default(0);
            $table->timestamps('pay_date');
            $table->string('image')->nullable();
            $table->longText('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_bill_customer_suppliers');
    }
};
