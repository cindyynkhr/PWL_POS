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
        Schema::create('t_stok', function (Blueprint $table) {
            $table->id('stok_id');
            $table->unsignedBigInteger('barang_id')->index(); //indexing untuk ForeignKey
            $table->unsignedBigInteger('user_id')->index(); //indexing untuk ForeignKey
            $table->datetime('stok_tanggal');
            $table->integer('stok_jumlah');
            $table->timestamps();

            //Mendefinisikan Foreign Key pada kolom barang_id mengacu pada kolom barang_id di table m_barang
            $table->foreign('barang_id')->references('barang_id')->on('m_barang');
            //Mendefinisikan Foreign Key pada kolom userid mengacu pada kolom id_user di table m_user
            $table->foreign('user_id')->references('id_user')->on('m_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_stok');
    }
};
