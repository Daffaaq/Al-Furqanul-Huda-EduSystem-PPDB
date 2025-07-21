<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $table = 'quotes';

    protected $fillable = [
        'quotes',
        'author',
        'periode_id',
    ];

    /**
     * Relasi ke Periode (many-to-one)
     */
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
