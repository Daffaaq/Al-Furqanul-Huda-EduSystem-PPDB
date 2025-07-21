<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanKarir extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_karirs';

    protected $fillable = [
        'pertanyaan',
        'urutan',
        'karir_id',
    ];

    /**
     * Relasi ke model Karir (many-to-one)
     */
    public function karir()
    {
        return $this->belongsTo(Karir::class);
    }
}
