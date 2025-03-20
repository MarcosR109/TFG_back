<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancione;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Runner\DeprecationCollector\Collector;
use App\Http\Resources\CancionResource;
use App\Models\Letra;
use App\Models\Linea;
use Exception;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Stmt\Return_;

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

class CancioneController extends Controller
{
    public function index()
    {
        try {

            return CancionResource::collection(Cancione::with(['genero', 'letras', 'lineas', 'tonalidade', 'user',])->get());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las canciones', 'error' => $e->getMessage()], 400);
        }
    }
    public function list()
    {
        try {
            $canciones = Cancione::join('generos', 'canciones.genero_id', '=', 'generos.id')
                ->join('tonalidades', 'canciones.tonalidade_id', '=', 'tonalidades.id')
                ->join('artistas', 'canciones.artista_id', '=', 'artistas.id')
                ->select('canciones.titulo', 'generos.nombre as genero', 'tonalidades.nombre as tonalidad', 'artistas.nombre as artista')
                ->distinct()
                ->get();
            return response()->json(['message' => 'Canciones obtenidas', 'canciones' => $canciones], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las canciones', 'error' => $e->getMessage()], 400);
        }
    }
    public function listarCancion($title)
    {
        try {
            $canciones = Cancione::join('generos', 'canciones.genero_id', '=', 'generos.id')
                ->join('tonalidades', 'canciones.tonalidade_id', '=', 'tonalidades.id')
                ->join('artistas', 'canciones.artista_id', '=', 'artistas.id')
                ->where('canciones.titulo', $title)
                ->select('canciones.id', 'canciones.titulo', 'canciones.tonalidade_id', 'canciones.user_id', 'generos.nombre as genero', 'tonalidades.nombre as tonalidad', 'artistas.nombre as artista')
                ->get();

            return response()->json(['message' => 'Versiones obtenidas', 'canciones' => $canciones], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las versiones', 'error' => $e->getMessage()], 400);
        }
    }
    public function show($id)
    {
        try {
            $cancion = Cancione::with(['genero', 'letras', 'lineas', 'tonalidade', 'user'])->find($id);
            if ($cancion == null) {
                return response()->json(['message' => 'Canción no encontrada'], 404);
            }
            return response()->json(['cancion' => CancionResource::make($cancion)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener la canción', 'error' => $e->getMessage()], 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $request = $request->all();
            $cancion = Cancione::find($id);
            $cancion->update($request);
            $cancion->titulo = $request['titulo'];
            $cancion->metrica = $request['metrica'];
            $cancion->capo = $request['capo'];
            $cancion->comentario = $request['comentario'];
            $cancion->var = $request['var'];
            $cancion->artista_id = $request['artista_id'];
            $cancion->genero_id = $request['genero_id'];
            $cancion->tonalidade_id = $request['tonalidade_id'];
            $cancion->user_id = $request['user_id'];
            try {
                $letras = $cancion->letras;
                $lineas = $cancion->lineas;
                while ($letras->count() > count($request['lineas'])) {
                    $cancion->letras->last()->delete();
                }
                foreach ($request['lineas'] as $linea) {
                    $line = $letras->where('n_linea', $linea['n_linea'])->first();
                    if ($line) {
                        $line->texto = $linea['letra'];
                        $line->save();
                    } else {
                        $letra = new Letra();
                        $letra->texto = $linea['letra'];
                        $letra->n_linea = $linea['n_linea'];
                        $letra->cancione_id = $cancion->id;
                        $letra->save();
                    }
                    foreach ($linea['acordes'] as $acorde) {
                        $acordeN = $lineas->where('posicion_en_compas', $acorde['posicion_en_compas'])->where('n_linea', $linea['n_linea'])->first();
                        $acordeN->acorde_id = $acorde['id'];
                        $acordeN->variacion = $acorde['variacion'] ?? '';
                        $acordeN->save();
                    }
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error al actualizar la canción', 'error' => $e->getMessage()], 400);
            }
            return response()->json(['message' => 'Canción actualizada', 'cancion' => $cancion], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la canción', 'error' => $e->getMessage()], 400);
        }
    }
    public function delete($id)
    {
        try {
            $cancion = Cancione::find($id);
            $cancion->delete();
            return response()->json(['message' => 'Canción eliminada', 'cancion' => $cancion], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar la canción', 'error' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request = $request->all();
            $cancion = new Cancione();
            if ($cancionExists = Cancione::where('titulo', $request['titulo'])->where('artista_id', $request['artista_id'])->first()) {
                return $this->storeIfExists($request, $cancionExists);
            }
            $cancion->titulo        = $request['titulo'];
            $cancion->metrica       = $request['metrica'];
            $cancion->capo           = $request['capo'];
            $cancion->comentario    = $request['comentario'];
            $cancion->var           = $request['var'];
            $cancion->artista_id     = $request['artista_id'];
            $cancion->genero_id     = $request['genero_id'];
            $cancion->tonalidade_id = $request['tonalidade_id'];
            $cancion->user_id       = $request['user_id'];
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
    public function storeIfExists(array $request, Cancione $cancion)
    {
        try {
            $can = new Cancione();
            $can->titulo =  $cancion->titulo;
            $can->metrica = $request['metrica'];
            $can->capo =    $request['capo'] ?? 0;
            $can->comentario = $request['comentario'] ?? '';
            $can->var = true; // Marca como una variación
            $can->cancion_original_id = $cancion->id; // Relaciona con la canción original
            $can->user_id = 2; // El ID del usuario que crea la variación
            $can->genero_id = $cancion->genero_id;
            $can->tonalidade_id = $request['tonalidade_id'];
            $can->artista_id = $cancion->artista_id;
            $can->save();
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
                    $line->cancione_id = $can->id;
                    $line->save();
                }
                $letra->cancione_id = $can->id;
                $letra->save();
            }
            return response()->json(['message' => 'Canción/Var creada', 'cancion' => $can], 200);
        } catch (Exception $e) {
            $can->delete();
            $letra->delete();
            $line->save();
            return response()->json(['message' => 'Error al crear la canción', 'error' => $e->getMessage(), 'request' => $request], 400);
        }
    }


    public function crearVariacion($id)
    {
        try {
            $cancion = Cancione::find($id); // Encuentra la canción original
            $variacion = new Cancione(); // Crea una nueva instancia de Cancione (con un nuevo ID)
            // Asigna los valores para la variación
            $variacion->titulo = $cancion->titulo; // Puedes copiar cualquier campo que desees
            $variacion->metrica = $cancion->metrica;
            $variacion->capo = $cancion->capo;
            $variacion->comentario = $cancion->comentario;
            $variacion->var = true; // Marca como una variación
            $variacion->cancion_original_id = $cancion->id; // Relaciona con la canción original
            $variacion->user_id = 2; // El ID del usuario que crea la variación
            $variacion->genero_id = $cancion->genero_id;
            $variacion->tonalidade_id = $cancion->tonalidade_id;
            $variacion->artista_id = $cancion->artista_id;
            $variacion->save(); // Guarda la variación en la base de datos, generando un nuevo ID
        } catch (\Exception $e) {
            $variacion->delete();
            return response()->json(['message' => 'Error al crear la variación', 'error' => $e->getMessage(), "cancion" => $cancion], 400);
        }
        return response()->json(['message' => 'Variación creada', 'cancion' => $cancion], 200);
    }
}
