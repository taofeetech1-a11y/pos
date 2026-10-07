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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('shipping', 10, 2)->default(0.00);
            $table->decimal('grand_total', 10, 2);
            $table->enum('payment_method', ['pos', 'wire', 'cash']);
            $table->unsignedBigInteger('cashier_id');
            $table->enum('payment_status', ['paid', 'pending', 'refunded']);
            $table->string('receipt_number')->unique();
            $table->timestamps();

            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('receipt_number');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
