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
        Schema::create('product_supplies', function (Blueprint $table) {
            $table->id();
             $table->string('invoice_number')->unique();
            $table->date('supply_date')->default(now());
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');
            $table->decimal('unit_price', 10, 2);
            $table->integer('qty_supplied');
            $table->decimal('total_amount', 10, 2); // unit_price * qty_supplied
            $table->enum('status', ['approved', 'pending', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_supplies');
    }
};
