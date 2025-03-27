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
    public function lineas()
    {
        return $this->hasMany(Linea::class, 'acorde_id'); // La clave foránea en la tabla "lineas"
    }
}
