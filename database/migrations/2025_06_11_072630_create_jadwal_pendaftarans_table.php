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
        Schema::create('jadwal_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jadwal_pendaftaran');
            $table->date('tanggal_mulai_jadwal_pendaftaran');
            $table->date('tanggal_selesai_jadwal_pendaftaran');
            $table->date('tanggal_mulai_verifikasi');
            $table->date('tanggal_selesai_verifikasi');
            $table->string('gelombang_pendaftaran');
            $table->date('deadline_biodata_calon_siswa');
            $table->date('deadline_upload_pendaftaran');
            $table->boolean('biodata_ditutup')->default(false);
            $table->boolean('upload_ditutup')->default(false);
            $table->date('pengumuman_hasil_seleksi');
            $table->integer('kuota_akun');
            $table->integer('kuota_pendaftaran');
            $table->integer('kuota_penerimaan');
            $table->enum('status_jadwal_pendaftaran', ['Opened', 'Ongoing', 'Closed'])->default('Opened');
            $table->boolean('tampilkan_perangkingan')->default(false);
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
        Schema::dropIfExists('jadwal_pendaftarans');
    }
};
