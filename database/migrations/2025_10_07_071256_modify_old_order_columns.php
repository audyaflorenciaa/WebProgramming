<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Needed for the DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Require doctrine/dbal for modifying column attributes
        // If you get an error here, run: composer require doctrine/dbal
        
        Schema::table('orders', function (Blueprint $table) {
            
            // FIX: Increase the status column length to prevent 'Data truncated' error
            if (Schema::hasColumn('orders', 'status')) {
                // Changing the column length requires doctrine/dbal and the 'string' modifier.
                // We are setting it to 50 characters long to ensure it fits "Pending Payment" and future states.
                $table->string('status', 50)->default('Pending Payment')->change();
            }

            // 1. Make old, unused columns nullable (Fixes Error 1364)
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->change();
            }
            
            if (Schema::hasColumn('orders', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->change();
            }
            
            if (Schema::hasColumn('orders', 'quantity')) {
                $table->integer('quantity')->nullable()->change();
            }

            // 2. Rename the old total_amount column if it conflicts with the new one
            // This section remains commented out as it was meant for handling conflicting column additions.
            if (Schema::hasColumn('orders', 'total_amount')) {
                 // Note: If you run into issues, the nuclear option is to drop the old conflicting columns
                 // DB::statement('ALTER TABLE orders DROP COLUMN total_amount_old'); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Since we are fixing old data structure issues, reversing these changes is not recommended.
        // Leave empty or reverse if necessary.
    }
};
