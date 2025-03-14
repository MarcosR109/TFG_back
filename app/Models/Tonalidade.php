<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tonalidade extends Model
{
    protected $fillable = ['nombre'];
    public function acordes()
    {
        return $this->hasMany(Acorde::class);
    }
    public function canciones()
    {
        return $this->hasMany(Cancione::class);
    }
}
