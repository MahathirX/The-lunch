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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_id');
            $table->date('invoice_date');
            $table->double('admin_sub_total')->default(0);
            $table->double('sub_total')->default(0);
            $table->double('total_qnt')->default(0);
            $table->double('discount')->default(0);
            $table->double('tax')->default(0);
            $table->double('paid_amount')->default(0);
            $table->double('due_amount')->default(0);
            $table->longText('note')->nullable();
            $table->enum('status',[0,1,2,3])->comment('0 = Partial ,1 = Ordered , 2 = Pending , 3 = Received');
            $table->string('purchase_by')->default('admin');
            $table->enum('purchase_type',[0,1])->comment('0 = Local ,1 = Supplier')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
