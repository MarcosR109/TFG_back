<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    protected $fillable = ['n_linea', 'posicion_en_compas', 'variacion', 'acorde', 'variacion'];
    public function cancione()
    {
        return $this->belongsTo(Cancione::class);
    }
    public function acordes()
    {
        return $this->hasMany(Acorde::class);
    }
}
