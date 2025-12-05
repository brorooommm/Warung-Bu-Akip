<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price'); // harga per item
            $table->integer('subtotal'); // quantity * price
            $table->unsignedBigInteger('cashier_id')->nullable(); // opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};
