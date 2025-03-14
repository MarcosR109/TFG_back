<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acorde;
use Illuminate\Support\Facades\DB;

class AcordeController extends Controller
{
    public function index()
    {
        $results = DB::select("SELECT tonalidades.nombre as tonalidad ,acordes.id,acordes.nombre as acorde,grado FROM ACORDES INNER JOIN TONALIDADES ON TONALIDADES.ID = ACORDES.TONALIDADE_ID order by acordes.id");
        return response()->json($results);
    }
}
