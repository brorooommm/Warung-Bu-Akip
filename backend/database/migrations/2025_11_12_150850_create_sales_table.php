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
    $table->unsignedBigInteger('product_id');
    $table->integer('quantity');
    $table->decimal('price', 10, 2);
    $table->decimal('total', 10, 2);
    $table->unsignedBigInteger('cashier_id')->nullable(); // bisa pakai users table
    $table->timestamps();

    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
