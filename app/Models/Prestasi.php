<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasis';
    protected $fillable = [
        'nama_prestasi',
        'point_prestasi',
        'kategori_prestasi_id',
    ];

    public function kategori_prestasi()
    {
        return $this->belongsTo(KategoriPrestasi::class);
    }

    public function prestasiPendaftars()
    {
        return $this->hasMany(PrestasiPendaftar::class);
    }
}
