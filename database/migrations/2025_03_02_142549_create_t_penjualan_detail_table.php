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
        Schema::create('t_penjualan_detail', function (Blueprint $table) {
            $table->id('detail_id');
            $table->unsignedBigInteger('penjualan_id')->index(); //indexing untuk ForeignKey
            $table->unsignedBigInteger('barang_id')->index(); //indexing untuk ForeignKey
            $table->integer('harga');
            $table->integer('jumlah');
            $table->timestamps();


            //Mendefinisikan Foreign Key pada kolom penjualan_id mengacu pada kolom id_penjualan di table t_penjualan
            $table->foreign('penjualan_id')->references('id_penjualan')->on('t_penjualan');
            //Mendefinisikan Foreign Key pada kolom barang_id mengacu pada kolom barang_id di table m_barang
            $table->foreign('barang_id')->references('barang_id')->on('m_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan_detail');
    }
};
