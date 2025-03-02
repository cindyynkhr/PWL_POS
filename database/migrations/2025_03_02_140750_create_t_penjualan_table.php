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
        Schema::create('t_penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->unsignedBigInteger('user_id')->index(); //indexing untuk ForeignKey
            $table->string('pembeli',50);
            $table->string('penjualan_kode',20)->unique();
            $table->datetime('penjualan_tanggal');
            $table->timestamps();

            //Mendefinisikan Foreign Key pada kolom user_id mengacu pada kolom id_user di table m_user
            $table->foreign('user_id')->references('id_user')->on('m_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan');
    }
};
