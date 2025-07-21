<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotKosong extends Model
{
    use HasFactory;

    protected $table = 'slot_kosongs';

    protected $fillable = [
        'jadwal_pendaftaran_id',
        'jumlah_kosong',
    ];

    // Relasi ke JadwalPendaftaran
    public function jadwalPendaftaran()
    {
        return $this->belongsTo(JadwalPendaftaran::class);
    }
}
