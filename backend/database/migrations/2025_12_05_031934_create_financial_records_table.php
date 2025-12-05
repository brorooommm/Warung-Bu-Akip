<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();

            // Nilai pendapatan (nullable)
            $table->decimal('pendapatan', 15, 2)->nullable();

            // Nilai pengeluaran (nullable)
            $table->decimal('pengeluaran', 15, 2)->nullable();

            // Jenis transaksi: 'pendapatan' atau 'pengeluaran'
            $table->enum('jenis', ['pendapatan', 'pengeluaran']);

            // Keterangan tambahan
            $table->string('keterangan')->nullable();

            // ID referensi (opsional)
            $table->unsignedBigInteger('reference_id')->nullable(); 
            $table->string('reference_type')->nullable(); // contoh: 'product', 'transaction'

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_records');
    }
};
