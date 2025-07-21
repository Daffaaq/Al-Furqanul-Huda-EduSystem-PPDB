<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'bobot_pendaftarans';

    protected $fillable = [
        'bobot_akademik',
        'bobot_non_akademik',
        'periode_id',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
