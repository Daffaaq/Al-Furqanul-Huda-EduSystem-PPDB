<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewJadwalPendaftaranDenganPeriode extends Model
{
    use HasFactory;
    protected $table = 'view_jadwal_pendaftaran_dengan_periode';
    public $incrementing = false;
    public $timestamps = false;
}
