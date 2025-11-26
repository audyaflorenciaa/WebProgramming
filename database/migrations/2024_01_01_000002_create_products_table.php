<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // NOTE: Using bigInteger below to prevent Error 150 until foreign keys are attached later
            // $table->bigInteger('user_id')->unsigned();
            // $table->bigInteger('category_id')->unsigned();

            // Foreign key ke users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Foreign key ke categories
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('condition', ['like_new', 'good', 'fair', 'poor']);
            $table->string('brand')->nullable();
            $table->json('images')->nullable();
            $table->json('video')->nullable();
            $table->boolean('is_sold')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};