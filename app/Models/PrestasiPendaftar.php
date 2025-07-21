<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiPendaftar extends Model
{
    use HasFactory;

    protected $table = 'prestasi_pendaftars';

    protected $fillable = [
        'pendaftaran_id',
        'prestasi_id',
        'jumlah_prestasi',
        'dokumen_prestasi_pendukung',
        'file_type',
        'status'
    ];

    // Relasi ke Pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    // Relasi ke Prestasi
    public function prestasi()
    {
        return $this->belongsTo(Prestasi::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(PrestasiPendaftarStatusHistories::class);
    }

    public function lastStatusHistory()
    {
        return $this->hasOne(PrestasiPendaftarStatusHistories::class)
            ->latestOfMany(); // Ambil yang terbaru berdasarkan created_at
    }
}
