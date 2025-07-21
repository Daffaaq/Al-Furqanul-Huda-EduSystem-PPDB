<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskualifikasiPendaftar extends Model
{
    use HasFactory;

    protected $table = 'diskualifikasi_pendaftars';

    protected $fillable = [
        'pendaftaran_id',
        'alasan_diskualifikasi',
        'tanggal_diskualifikasi',
        'bukti_diskualifikasi',
        'file_type',
        'petugas_id',
    ];

    // Relasi ke Pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    // Relasi ke User (petugas)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
