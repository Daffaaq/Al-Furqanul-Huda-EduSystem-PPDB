<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pendaftarans';

    protected $fillable = [
        'nama_jadwal_pendaftaran',
        'tanggal_mulai_jadwal_pendaftaran',
        'tanggal_selesai_jadwal_pendaftaran',
        'tanggal_mulai_verifikasi',
        'tanggal_selesai_verifikasi',
        'gelombang_pendaftaran',
        'deadline_biodata_calon_siswa',
        'deadline_upload_pendaftaran',
        'pengumuman_hasil_seleksi',
        'kuota_akun',
        'kuota_pendaftaran',
        'kuota_penerimaan',
        'status_jadwal_pendaftaran',
        'biodata_ditutup',
        'upload_ditutup',
        'tampilkan_perangkingan',
        'periode_id',
    ];

    // Pastikan nama model 'Periode' sesuai dengan model yang ada
    public function periode()
    {
        return $this->belongsTo(Periode::class);  // Pastikan 'Periode' adalah nama model yang benar
    }

    // di dalam model JadwalPendaftaran.php
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function slotKosong()
    {
        return $this->hasOne(SlotKosong::class);
    }
}
