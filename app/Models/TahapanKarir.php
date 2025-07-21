<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapanKarir extends Model
{
    use HasFactory;

    protected $table = 'tahapan_karirs';

    protected $fillable = [
        'karir_id',
        'tahapan_lamaran_id',
        'urutan',
        'deskripsi',
    ];

    /**
     * Relasi ke model Karir
     */
    public function karir()
    {
        return $this->belongsTo(Karir::class);
    }

    /**
     * Relasi ke model TahapanLamaran
     */
    public function tahapanLamaran()
    {
        return $this->belongsTo(TahapanLamaran::class);
    }
}
