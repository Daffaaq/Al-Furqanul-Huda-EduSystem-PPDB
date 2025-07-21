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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('nomer_pendaftaran')->unique()->nullable();
            $table->unsignedBigInteger('jadwal_pendaftaran_id')->index();
            $table->foreign('jadwal_pendaftaran_id')->references('id')->on('jadwal_pendaftarans')->onDelete('cascade');
            $table->unsignedBigInteger('biodata_calon_siswa_id')->index();
            $table->foreign('biodata_calon_siswa_id')->references('id')->on('biodata_calon_siswas')->onDelete('cascade');
            $table->enum('status_final', ['Lolos', 'Tidak Lolos', 'Pending'])->default('Pending')->nullable();
            $table->enum('status_accept', ['Accept', 'Reject', 'Pending'])->default('Pending')->nullable();
            $table->dateTime('tanggal_pendaftaran')->nullable();
            $table->boolean('is_final')->default(false)->nullable();
            $table->boolean('status_cadangan')->default(false)->nullable();
            $table->boolean('status_aktif')->default(false)->nullable();
            $table->boolean('status_diskualifikasi')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
