<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiPendaftarStatusHistories extends Model
{
    use HasFactory;

    protected $table = 'prestar_status_histories';
    protected $fillable = [
        'prestasi_pendaftar_id',
        'status',
        'catatan',
        'nama_petugas',
    ];

    public function prestasi_pendaftar()
    {
        return $this->belongsTo(PrestasiPendaftar::class);
    }
}
