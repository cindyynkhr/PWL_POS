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
        Schema::create('m_user', function (Blueprint $table) {
            $table->id('id_user');
            $table->unsignedBigInteger('level_id')->index(); //indexing untuk ForeignKey
            $table->string ('user_kode', 20)->unique(); //uniuqe untuk primary key
            $table->string('nama', 100);
            $table->string('password');
            $table->timestamps();

            //Mendefinisikan Foreign Key pada kolom level_id mengacu pada kolom level_id di table m_level
            $table->foreign('level_id')->references('id_level')->on('m_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
    }
};
