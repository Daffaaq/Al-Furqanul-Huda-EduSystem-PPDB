<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateViewBiodataCalonSiswaDenganUser extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_biodata_calon_siswa_dengan_user AS
            SELECT 
                bcs.id AS biodata_id,
                bcs.nama_calon_siswa,
                bcs.tanggal_lahir_calon_siswa,
                bcs.jenis_kelamin_calon_siswa,
                bcs.tempat_tanggal_lahir_calon_siswa,
                bcs.alamat_rumah_calon_siswa,
                bcs.nomor_telepon_calon_siswa,
                bcs.agama_calon_siswa,
                bcs.email_calon_siswa AS email_calon_siswa,
                u.id AS user_id,
                u.name AS user_name,
                u.email AS user_email,
                bcs.periode_id,
                bcs.created_at,
                bcs.updated_at
            FROM biodata_calon_siswas bcs
            JOIN users u ON bcs.user_id = u.id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_biodata_calon_siswa_dengan_user");
    }
}
