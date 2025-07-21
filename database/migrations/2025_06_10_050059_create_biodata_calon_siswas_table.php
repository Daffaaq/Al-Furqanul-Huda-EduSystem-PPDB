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
        Schema::create('biodata_calon_siswas', function (Blueprint $table) {
            $table->id();
            $table->text('nama_calon_siswa')->nullable();
            $table->string('email_calon_siswa')->nullable();
            $table->enum('jenis_kelamin_calon_siswa', ['Laki-laki', 'Perempuan']);
            $table->date('tanggal_lahir_calon_siswa')->nullable();
            $table->string('tempat_tanggal_lahir_calon_siswa')->nullable();
            $table->text('alamat_rumah_calon_siswa')->nullable();
            $table->enum('agama_calon_siswa', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'])->nullable();
            $table->string('nomor_telepon_calon_siswa')->nullable();
            $table->string('foto_formal_calon_siswa')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('periode_id')->index();
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
            $table->string('dokumen_nilai_pendukung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_calon_siswas');
    }
};
