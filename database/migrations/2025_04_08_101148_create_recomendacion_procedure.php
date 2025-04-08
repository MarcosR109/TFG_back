<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
CREATE PROCEDURE sp_obtener_recomendaciones_armonicas(
    IN p_previo INT,  -- NULLABLE
    IN p_actual INT,  -- REQUIRED
    IN p_limit INT
)
BEGIN
    IF p_previo IS NOT NULL THEN
        -- Caso con acorde previo (recomendación normal)
        SELECT
            a3.grado AS siguiente,
            COUNT(*) AS frecuencia,
            COUNT(*) / SUM(COUNT(*)) OVER () AS probabilidad
        FROM lineas l1
        JOIN lineas l2 ON l1.cancione_id = l2.cancione_id AND l2.n_linea = l1.n_linea + 1
        JOIN lineas l3 ON l2.cancione_id = l3.cancione_id AND l3.n_linea = l2.n_linea + 1
        JOIN acordes a1 ON a1.id = l1.acorde_id
        JOIN acordes a2 ON a2.id = l2.acorde_id
        JOIN acordes a3 ON a3.id = l3.acorde_id
        WHERE a1.id != 170 AND a2.id != 170 AND a3.id != 170 
          AND a1.grado != a3.grado 
          AND a1.grado != 8 AND a2.grado != 8 AND a3.grado != 8
          AND a1.grado = p_previo
          AND a2.grado = p_actual
        GROUP BY a3.grado
        ORDER BY probabilidad DESC
        LIMIT p_limit;
    ELSE
        -- Caso sin acorde previo (inicio de canción)
        SELECT
            a2.grado AS siguiente,
            COUNT(*) AS frecuencia,
            COUNT(*) / SUM(COUNT(*)) OVER () AS probabilidad
        FROM lineas l1
        JOIN lineas l2 ON l1.cancione_id = l2.cancione_id AND l2.n_linea = l1.n_linea + 1
        JOIN acordes a1 ON a1.id = l1.acorde_id
        JOIN acordes a2 ON a2.id = l2.acorde_id
        WHERE a1.id != 170 AND a2.id != 170
          AND a1.grado != 8 AND a2.grado != 8
          AND a1.grado = p_actual  -- Aquí usamos solo el actual como "previo" para el inicio
        GROUP BY a2.grado
        ORDER BY probabilidad DESC
        LIMIT p_limit;
    END IF;
END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recomendacion_procedure');
    }
};
