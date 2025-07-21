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
        Schema::create('nikadtar_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nilai_akademik_pendaftar_id')->index();
            $table->foreign('nilai_akademik_pendaftar_id')->references('id')->on('nilai_akademik_pendaftars')->onDelete('cascade');
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->string('nama_petugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akademik_pendaftar_status_histories');
    }
};
