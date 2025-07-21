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
        Schema::create('nilai_akademik_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_id')->index();
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftarans')->onDelete('cascade');
            $table->unsignedBigInteger('mata_pelajaran_seleksi_id')->index();
            $table->foreign('mata_pelajaran_seleksi_id')->references('id')->on('mata_pelajaran_seleksis')->onDelete('cascade');
            $table->decimal('nilai', 5, 2);
            $table->enum('status', ['Accept', 'Reject', 'Pending'])->default('Pending')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akademik_pendaftars');
    }
};
