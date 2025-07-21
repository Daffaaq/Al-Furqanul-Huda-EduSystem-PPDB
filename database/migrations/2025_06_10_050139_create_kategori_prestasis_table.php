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
        Schema::create('kategori_prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori_prestasi'); //etc : Internasional, Nasional, Provinsi, Kabupaten/kota
            $table->unsignedBigInteger('periode_id')->index();
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_prestasis');
    }
};
