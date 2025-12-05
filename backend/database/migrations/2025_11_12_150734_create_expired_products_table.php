<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expired_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->date('entry_date'); // tanggal produk masuk
            $table->date('expired_date'); // tanggal kadaluarsa
            $table->integer('countdown_days')->default(30); // misal countdown default 30 hari
            $table->boolean('is_expired')->default(false); // status expired
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expired_products');
    }
};
