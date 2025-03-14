<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artista extends Model
{
    protected $fillable = ['nombre'];

    public function canciones()
    {
        return $this->hasMany(Cancione::class);
    }
}
