<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPrestasi extends Model
{
    use HasFactory;

    protected $table = 'kategori_prestasis';
    protected $fillable = [
        'nama_kategori_prestasi',
        'periode_id',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class);
    }
}
