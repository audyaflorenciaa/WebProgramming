<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Changes the product_id and other no-longer-used columns to be nullable.
     */
    public function up(): void
    {
        // This requires installing the doctrine/dbal package if you haven't already.
        // composer require doctrine/dbal
        Schema::table('orders', function (Blueprint $table) {
            
            // Fix: Make product_id nullable so the single-product requirement is dropped.
            if (Schema::hasColumn('orders', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->change();
            }
            
            // If you had 'quantity' or 'total_amount' defined on the *main* order table
            // before our new fields, you might need to fix them too, but product_id is the primary error.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We generally don't reverse changes to nullable() unless necessary, 
        // but for safety, you can leave the down() method empty or just reverse product_id if possible.
    }
};
