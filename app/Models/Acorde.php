<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acorde extends Model
{
    protected $fillable = ['nombre'];

    private function tonalidade()
    {
        return $this->belongsTo(Tonalidade::class);
    }
}
