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
        Schema::create('slot_kosongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_pendaftaran_id')->index();
            $table->foreign('jadwal_pendaftaran_id')->references('id')->on('jadwal_pendaftarans')->onDelete('cascade');
            $table->integer('jumlah_kosong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_kosongs');
    }
};
