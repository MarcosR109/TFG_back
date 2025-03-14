<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letra extends Model
{
    protected $fillable = ['n_linea', 'orden'];
    public function cancion()
    {
        return $this->belongsTo(Cancione::class);
    }
}
