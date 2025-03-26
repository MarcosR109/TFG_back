<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancione;
use App\Models\User;

class UserController extends Controller
{

    public function anadirFavoritos($id)
    {
        try {
            $cancion = Cancione::findOrFail($id);
            $user = User::findOrFail(1);
            $user->favoritos()->attach($cancion);
            return response()->json(['message' => 'Canción añadida a favoritos'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se ha podido añadir a favoritos', 'debug' => $e->getMessage()], 404);
        }
    }
    public function quitarFavoritos($id)
    {
        try {
            $cancion = Cancione::findOrFail($id);
            $user = User::findOrFail(1);
            $user->favoritos()->detach($cancion);
            return response()->json(['message' => 'Canción eliminada de favoritos'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se ha podido eliminar de favoritos', 'debug' => $e->getMessage()], 404);
        }
    }
    public function verificarFavorito($id)
    {
        try {
            $user = User::findOrFail(1); // Aquí obtienes al usuario. En producción, sería el usuario autenticado.
            $favorito = $user->favoritos()->where('cancione_id', $id)->exists();
            return response()->json(['esFavorito' => $favorito], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar favoritos', 'debug' => $e->getMessage()], 500);
        }
    }
    public function listarFavoritos()
    {
        try {
            $user = User::find(1);
            $favoritos = $user->favoritos;
            $canciones = Cancione::join('generos', 'canciones.genero_id', '=', 'generos.id')
                ->join('tonalidades', 'canciones.tonalidade_id', '=', 'tonalidades.id')
                ->join('artistas', 'canciones.artista_id', '=', 'artistas.id')
                ->join('favoritos', 'canciones.id', '=', 'favoritos.cancione_id')
                ->join('users', 'favoritos.user_id', '=', 'users.id')
                ->where('users.id', $user->id)
                ->select('canciones.id', 'canciones.titulo', 'tonalidades.nombre', 'canciones.user_id', 'generos.nombre as genero', 'tonalidades.nombre as tonalidad', 'artistas.nombre as artista', 'canciones.rating as rating')
                ->get();
            return response()->json(['message' => 'Favoritos obtenidos', 'canciones' => $canciones], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los favoritos', 'error' => $e->getMessage()], 400);
        }
    }
}
