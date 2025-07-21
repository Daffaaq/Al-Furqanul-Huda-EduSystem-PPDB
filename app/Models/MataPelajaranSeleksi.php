<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaranSeleksi extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran_seleksis';

    protected $fillable = [
        'nama_mata_pelajaran_seleksi',
        'periode_id',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function nilaiPendaftar()
    {
        return $this->hasMany(NilaiAkademikPendaftar::class);
    }
}
