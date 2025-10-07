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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key to the Order
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            
            // Foreign Key to the Product being sold
            $table->foreignId('product_id')->constrained('products');

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Price of the item when sold
            $table->decimal('subtotal', 10, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
