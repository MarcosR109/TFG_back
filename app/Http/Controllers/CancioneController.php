<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancione;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Runner\DeprecationCollector\Collector;
use App\Http\Resources\CancionResource;
use App\Models\Letra;
use App\Models\Linea;

class CancioneController extends Controller
{
    public function index()
    {
        return CancionResource::collection(Cancione::with(['genero', 'letras', 'lineas', 'tonalidade', 'user'])->get());
    }
    /* public function lineas()
    {
        return $this->hasMany(Linea::class);
    }
    public function tonalidad()
    {
        return $this->hasOne(Tonalidad::class);
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
    }*/
    public function store(Request $request)
    {
        try {
            $request = $request->all();
            $cancion = new Cancione();
            $cancion->titulo = $request['titulo'];
            $cancion->metrica = $request['metrica'];
            $cancion->capo = $request['capo'];
            $cancion->comentario = $request['comentario'];
            $cancion->var = $request['var'];
            $cancion->artista_id = $request['artista_id'];
            $cancion->genero_id = $request['genero_id'];
            $cancion->tonalidade_id = $request['tonalidade_id'];
            $cancion->user_id = $request['user_id'];
            $cancion->save();
            foreach ($request['lineas'] as $linea) {
                $letra = new Letra();
                $letra->texto = $linea['letra'];
                $letra->n_linea = $linea['n_linea'];
                foreach ($linea['acordes'] as $acorde) {
                    $line = new Linea();
                    $line->n_linea = $linea['n_linea'];
                    $line->acorde_id = $acorde['id'];
                    $line->posicion_en_compas = $acorde['posicion_en_compas'];
                    $line->variacion = $acorde['variacion'] ?? '';
                    $line->cancione_id = $cancion->id;
                    $line->save();
                }
                $letra->cancione_id = $cancion->id;
                $letra->save();
            }
        } catch (\Exception $e) {
            $cancion->delete();
            return response()->json(['message' => 'Error al crear la canción', 'error' => $e->getMessage(), 'request' => $request], 400);
        }
        return response()->json(['message' => 'Canción creada', 'cancion' => $cancion], 200);
    }
}
