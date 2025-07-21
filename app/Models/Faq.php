<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = ['question', 'answer', 'is_active', 'order', 'periode_id'];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
