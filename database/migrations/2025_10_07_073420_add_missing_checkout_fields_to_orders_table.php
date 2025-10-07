<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This adds the necessary columns for the checkout process to the 'orders' table.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            
            // These columns are needed for the OrderController@store method to save data
            // We are adding them AFTER the existing columns from the older migrations.
            
            // Status and Totals (Ensuring we don't conflict with any old columns that might exist)
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status', 50)->default('Pending Payment')->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'total_subtotal')) {
                $table->decimal('total_subtotal', 10, 2)->after('status');
            }
            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->after('total_subtotal');
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->after('shipping_cost');
            }
            
            // Delivery and Payment methods
            if (!Schema::hasColumn('orders', 'delivery_address')) {
                $table->string('delivery_address', 255)->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'delivery_method')) {
                $table->string('delivery_method', 50)->after('delivery_address');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 50)->after('delivery_method');
            }
            
            // Cleanup: Ensuring old fields we stopped using are now nullable
            if (Schema::hasColumn('orders', 'product_id')) {
                 $table->unsignedBigInteger('product_id')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'quantity')) {
                 $table->integer('quantity')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Only drop columns that are guaranteed to have been added by this migration
            // and remove the nullable changes if rolling back.
        });
    }
};
