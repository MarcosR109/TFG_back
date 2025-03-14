<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Linea;

class LineaController extends Controller
{
    //    protected $fillable = ['n_linea', 'posicion_en_compas'];
    public function store(Request $request)
    {
        $linea = new Linea();
        $linea->n_linea = $request->n_linea;
        $linea->cancione_id = $request->cancione_id;
        $linea->acorde_id = $request->acorde_id;
        $linea->posicion_en_compas = $request->posicion_en_compas;
        $linea->variacion = $request->variacion;
        $linea->save();
        return response()->json($linea);
    }
}
