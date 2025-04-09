<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancione;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{

    public function anadirFavoritos($id)
    {
        try {
            $cancion = Cancione::findOrFail($id);
            $user = Auth::user();
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
            $user = Auth::user();
            $user->favoritos()->detach($cancion);
            return response()->json(['message' => 'Canción eliminada de favoritos'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se ha podido eliminar de favoritos', 'debug' => $e->getMessage()], 404);
        }
    }
    public function verificarFavorito($id)
    {
        try {
            $user = Auth::user(); // Aquí obtienes al usuario. En producción, sería el usuario autenticado.
            $favorito = $user->favoritos()->where('cancione_id', $id)->exists();
            return response()->json(['esFavorito' => $favorito], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar favoritos', 'debug' => $e->getMessage()], 500);
        }
    }
    public function listarFavoritos()
    {
        try {
            $user = Auth::user();
            $cancionesFav = $user->favoritos()->with('genero', 'tonalidade', 'artista')->get();
            $cancionesPropias = $user->canciones()->with('genero', 'tonalidade', 'artista')->get();
        
            return response()->json(['message' => 'Favoritos obtenidos', 'cancionesfav' => $cancionesFav, 'cancionespro' => $cancionesPropias, 'DEBUG' => 'listarfav'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los favoritos', 'error' => $e->getMessage()], 400);
        }
    }

    public function anadirGuardados($id)
    {
        try {
            $cancion = Cancione::findOrFail($id);
            $user = Auth::user();
            $user->guardados()->attach($cancion);
            return response()->json(['message' => 'Canción añadida a guardados'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se ha podido añadir a guardados', 'debug' => $e->getMessage()], 404);
        }
    }
    public function verificarGuardados($id)
    {
        try {
            $user = User::findOrFail(1); // Aquí obtienes al usuario. En producción, sería el usuario autenticado.
            $guardado = $user->guardados()->where('cancione_id', $id)->exists();
            return response()->json(['guardado' => $guardado], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar guardados', 'debug' => $e->getMessage()], 500);
        }
    }
    public function quitarGuardados($id)
    {
        try {
            $cancion = Cancione::findOrFail($id);
            $user = Auth::user();
            $user->guardados()->detach($cancion);
            return response()->json(['message' => 'Canción eliminada de guardados'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se ha podido eliminar de guardados', 'debug' => $e->getMessage()], 404);
        }
    }
    public function listarUsuarios()
    {
        try {
            $usuarios = User::all();
            return response()->json(['message' => 'Usuarios obtenidos', 'usuarios' => $usuarios], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los usuarios', 'error' => $e->getMessage()], 400);
        }
    }
    public function listarGuardados()
    {
        try {
            $user = Auth::User();
            $guardados = $user->guardados;
            $canciones = Cancione::join('generos', 'canciones.genero_id', '=', 'generos.id')
                ->join('tonalidades', 'canciones.tonalidade_id', '=', 'tonalidades.id')
                ->join('artistas', 'canciones.artista_id', '=', 'artistas.id')
                ->join('guardados', 'canciones.id', '=', 'guardados.cancione_id')
                ->join('users', 'guardados.user_id', '=', 'users.id')
                ->where('users.id', $user->id)
                ->select('canciones.id', 'canciones.titulo', 'tonalidades.nombre', 'canciones.user_id', 'generos.nombre as genero', 'tonalidades.nombre as tonalidad', 'artistas.nombre as artista', 'canciones.rating as rating')
                ->get();
            return response()->json(['message' => 'Guardados obtenidos', 'canciones' => $canciones], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los guardados', 'error' => $e->getMessage()], 400);
        }
    }
    public function cambiarRol($id, Request $request)
    {
        try {
            $user = User::findOrFail($id);
            $nuevoRol = $request->input('rol');
            $user->role_id = $nuevoRol;
            $user->save();
            return response()->json(['message' => 'Rol cambiado correctamente', 'usuario' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al cambiar el rol', 'error' => $e->getMessage()], 400);
        }
    }
    public function eliminarUsuario($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el usuario', 'error' => $e->getMessage()], 400);
        }
    }
}
