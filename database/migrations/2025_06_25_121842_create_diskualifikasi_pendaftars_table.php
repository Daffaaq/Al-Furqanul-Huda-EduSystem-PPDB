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
        Schema::create('diskualifikasi_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_id')->index();
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftarans')->onDelete('cascade');
            $table->text('alasan_diskualifikasi');
            $table->date('tanggal_diskualifikasi');
            $table->string('bukti_diskualifikasi')->nullable();
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('petugas_id')->index();
            $table->foreign('petugas_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskualifikasi_pendaftars');
    }
};
