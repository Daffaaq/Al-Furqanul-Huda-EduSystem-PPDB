<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkademikPendaftarStatusHistories extends Model
{
    use HasFactory;

    protected $table = 'nikadtar_status_histories';

    protected $fillable = [
        'nilai_akademik_pendaftar_id',
        'status',
        'catatan',
        'nama_petugas',
    ];

    public function nilai_akademik_pendaftar()
    {
        return $this->belongsTo(NilaiAkademikPendaftar::class);
    }
}
