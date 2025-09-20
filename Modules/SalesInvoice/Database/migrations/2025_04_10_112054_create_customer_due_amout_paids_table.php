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
        Schema::create('customer_due_amout_paids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amount_get_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('customers')->cascadeOnDelete();
            $table->double('amount');
            $table->double('paid_after_due')->default(0);
            $table->date('paid_date');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_due_amout_paids');
    }
};
