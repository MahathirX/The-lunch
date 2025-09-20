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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('page_name')->unique();
            $table->string('slug');
            $table->longText('page_heading');
            $table->mediumText('page_link');
            $table->longText('product_overview');
            $table->mediumText('slider_title');
            $table->longText('features');
            $table->string('old_price')->nullable();
            $table->string('new_price')->nullable();
            $table->string('phone');
            $table->longText('extra_content')->nullable();
            $table->enum('status',[0,1])->comment('0 = Pending , 1 = Publish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
