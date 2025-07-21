<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateViewJadwalPendaftaranDenganPeriode extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_jadwal_pendaftaran_dengan_periode AS
            SELECT 
                jp.id AS jadwal_pendaftaran_id,
                jp.nama_jadwal_pendaftaran,
                jp.tanggal_mulai_jadwal_pendaftaran,
                jp.tanggal_selesai_jadwal_pendaftaran,
                jp.tanggal_mulai_verifikasi,
                jp.tanggal_selesai_verifikasi,
                jp.status_jadwal_pendaftaran,
                jp.gelombang_pendaftaran,
                jp.deadline_upload_pendaftaran,
                jp.deadline_biodata_calon_siswa,
                jp.pengumuman_hasil_seleksi,
                jp.kuota_pendaftaran,
                jp.kuota_penerimaan,
                p.id AS periode_id,
                p.nama_periode,
                p.status_periode
            FROM jadwal_pendaftarans jp
            JOIN periodes p ON jp.periode_id = p.id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_jadwal_pendaftaran_dengan_periode");
    }
}
