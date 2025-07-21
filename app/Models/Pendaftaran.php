<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';

    protected $fillable = [
        'nomer_pendaftaran',
        'jadwal_pendaftaran_id',
        'biodata_calon_siswa_id',
        'status_final',
        'status_accept',
        'status_cadangan',
        'status_aktif',
        'is_final',
        'status_diskualifikasi',
        'tanggal_pendaftaran'
    ];

    /**
     * Relasi ke jadwal pendaftaran
     */
    public function jadwalPendaftaran()
    {
        return $this->belongsTo(JadwalPendaftaran::class);
    }

    /**
     * Relasi ke biodata calon siswa
     */
    public function biodataCalonSiswa()
    {
        return $this->belongsTo(BiodataCalonSiswa::class);
    }

    public function nilaiAkademik()
    {
        return $this->hasMany(NilaiAkademikPendaftar::class);
    }

    public function prestasiPendaftars()
    {
        return $this->hasMany(PrestasiPendaftar::class);
    }

    public function diskualifikasi()
    {
        return $this->hasOne(DiskualifikasiPendaftar::class);
    }
}
