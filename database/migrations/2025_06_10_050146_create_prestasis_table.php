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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prestasi'); //etc : Juara 1, Juara 2, Juara 3
            $table->integer('point_prestasi');
            $table->unsignedBigInteger('kategori_prestasi_id')->index();
            $table->foreign('kategori_prestasi_id')->references('id')->on('kategori_prestasis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
