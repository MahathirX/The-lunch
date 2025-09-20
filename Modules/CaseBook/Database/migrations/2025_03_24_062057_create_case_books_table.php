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
        Schema::create('case_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            $table->double('amount');
            $table->enum('payment_type',[0,1])->comment('0 = Mobile Banking , 1 = Bank')->default(1);
            $table->date('payment_date')->nullable();
            $table->longText('note')->nullable();
            $table->enum('status',[0,1])->comment('0 = Pending , 1 = Publish')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_books');
    }
};
