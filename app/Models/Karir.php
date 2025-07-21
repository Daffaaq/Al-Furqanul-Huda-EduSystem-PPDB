<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Karir extends Model
{
    use HasFactory;

    protected $table = 'karirs';

    protected $fillable = [
        'slug',
        'nama_karir',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    // Otomatis generate slug dari nama_karir saat membuat atau mengupdate data
    protected static function booted()
    {
        static::creating(function ($karir) {
            if (empty($karir->slug)) {
                $karir->slug = Str::slug($karir->nama_karir) . '-' . uniqid();
            }
        });

        static::updating(function ($karir) {
            if ($karir->isDirty('nama_karir')) {
                $karir->slug = Str::slug($karir->nama_karir) . '-' . uniqid();
            }
        });
    }


    // Optional: casting tanggal ke format date
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pertanyaan()
    {
        return $this->hasMany(PertanyaanKarir::class);
    }

    public function tahapanKarirs()
    {
        return $this->hasMany(TahapanKarir::class);
    }
}
