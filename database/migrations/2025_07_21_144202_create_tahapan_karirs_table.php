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
        Schema::create('tahapan_karirs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karir_id');
            $table->foreign('karir_id')->references('id')->on('karirs')->onDelete('cascade');
            $table->unsignedBigInteger('tahapan_lamaran_id');
            $table->foreign('tahapan_lamaran_id')->references('id')->on('tahapan_lamarans')->onDelete('cascade');
            $table->integer('urutan');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahapan_karirs');
    }
};
