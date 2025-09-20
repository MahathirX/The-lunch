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
        Schema::create('expense_lists', function (Blueprint $table) {
            $table->id();
            $table->date('create_date')->nullable();
            $table->foreign('expense_category_id')->references('id')->on('expenses')->onDelete('cascade');
            $table->decimal('amount', 10, 2); 
            $table->longText('expense_note')->nullable();
            $table->enum('status',[0,1])->default(1)->comment('0 = Pending , 1 = Publish');
            $table->timestamps();
        });
        
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_lists');
    }
};
