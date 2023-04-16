<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->timestamps();
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('stock_id')->constrained('stocks', 'id');

            $table->string('name');
            $table->integer('recommended_quantity');
            $table->integer('quantity');

            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('stock_id')->constrained('stocks', 'id');
            $table->foreignId('user_id')->nullable()->constrained('users', 'id');

            $table->timestamps();
        });

        // pivot tables
        Schema::create('product_stock', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('product_id')->constrained('products', 'id');
            $table->foreignId('stock_id')->constrained('stocks', 'id');

            $table->timestamps();
        });

        Schema::create('ingredient_product', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('product_id')->constrained('products', 'id');
            $table->uuid('ingredient_id')->constrained('ingredients', 'id');
            $table->integer('quantity');

            $table->timestamps();
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('order_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('ingredient_product');
        Schema::dropIfExists('product_stock');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('products');
        Schema::dropIfExists('stocks');
    }
};
