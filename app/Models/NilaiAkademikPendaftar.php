<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkademikPendaftar extends Model
{
    use HasFactory;

    protected $table = 'nilai_akademik_pendaftars';

    protected $fillable = [
        'pendaftaran_id',
        'mata_pelajaran_seleksi_id',
        'nilai',
        'status'
    ];

    // Relasi ke Pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    // Relasi ke Mata Pelajaran Seleksi
    public function mataPelajaranSeleksi()
    {
        return $this->belongsTo(MataPelajaranSeleksi::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(NilaiAkademikPendaftarStatusHistories::class);
    }

    public function lastStatusHistory()
    {
        return $this->hasOne(NilaiAkademikPendaftarStatusHistories::class)
            ->latestOfMany(); // Ambil yang terbaru berdasarkan created_at
    }
}
