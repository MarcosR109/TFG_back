<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancione extends Model
{
    protected $fillable = ['titulo', 'metrica', 'capo', 'comentario', 'var'];

    public function lineas()
    {
        return $this->hasMany(Linea::class);
    }
    public function tonalidade()
    {
        return $this->belongsTo(Tonalidade::class);
    }
    public function artista()
    {
        return $this->belongsTo(Artista::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function genero()
    {
        return $this->belongsTo(Genero::class);
    }
    public function letras()
    {
        return $this->hasMany(Letra::class);
    }
    public function favoritos()
    {
        return $this->belongsToMany(User::class, 'favoritos');
    }
    // Relación con la canción original (si es una variación)
    public function cancionOriginal()
    {
        return $this->belongsTo(Cancione::class, 'cancion_original_id');
    }

    // Relación con las variaciones de esta canción
    public function variaciones()
    {
        return $this->hasMany(Cancione::class, 'cancion_original_id');
    }
}
