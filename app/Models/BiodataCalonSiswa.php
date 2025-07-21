<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataCalonSiswa extends Model
{
    use HasFactory;

    protected $table = 'biodata_calon_siswas';

    // Menentukan kolom-kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'nama_calon_siswa',
        'email_calon_siswa',
        'jenis_kelamin_calon_siswa',
        'tanggal_lahir_calon_siswa',
        'tempat_tanggal_lahir_calon_siswa',
        'alamat_rumah_calon_siswa',
        'agama_calon_siswa',
        'nomor_telepon_calon_siswa',
        'foto_formal_calon_siswa',
        'dokumen_pendukung',
        'user_id',
        'periode_id'
    ];

    // Relasi ke tabel 'users' (biodata calon siswa milik user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel 'periodes' (biodata calon siswa milik periode)
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
