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
        Schema::create('prestasi_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_id')->index();
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftarans')->onDelete('cascade');
            $table->unsignedBigInteger('prestasi_id')->index();
            $table->foreign('prestasi_id')->references('id')->on('prestasis')->onDelete('cascade');
            $table->integer('jumlah_prestasi');
            $table->string('dokumen_prestasi_pendukung')->nullable();
            $table->string('file_type')->nullable();
            $table->enum('status', ['Accept', 'Reject', 'Pending'])->default('Pending')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_pendaftars');
    }
};
