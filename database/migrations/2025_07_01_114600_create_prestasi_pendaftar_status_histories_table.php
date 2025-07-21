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
        Schema::create('prestar_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestasi_pendaftar_id')->index();
            $table->foreign('prestasi_pendaftar_id')->references('id')->on('prestasi_pendaftars')->onDelete('cascade');
            $table->string('status'); // status baru: accepted, rejected, pending
            $table->text('catatan')->nullable(); // optional catatan (misal alasan reject)
            $table->string('nama_petugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_pendaftar_status_histories');
    }
};
