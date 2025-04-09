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
        DB::unprepared('
CREATE PROCEDURE sp_obtener_recomendaciones_armonicas(
    IN p_previo INT,  -- NULLABLE
    IN p_actual INT,  -- REQUIRED
    IN p_limit INT
)
BEGIN
    DECLARE v_count INT DEFAULT 0;
    
    -- Caso con acorde previo (recomendación normal)
    IF p_previo IS NOT NULL THEN
        -- Primero intentamos encontrar en la misma línea
        CREATE TEMPORARY TABLE IF NOT EXISTS temp_recomendaciones (
            siguiente INT,
            frecuencia INT,
            probabilidad DECIMAL(10,4),
            search_priority INT
        );
        
        -- Buscar en misma línea, posición siguiente (acorde a3 como siguiente)
        INSERT INTO temp_recomendaciones
        SELECT
            a3.grado AS siguiente,
            COUNT(*) AS frecuencia,
            COUNT(*) / (SELECT COUNT(*) FROM lineas l 
                       JOIN acordes a ON a.id = l.acorde_id
                       WHERE a.grado = p_actual) AS probabilidad,
            1 AS search_priority
        FROM lineas l1
        JOIN lineas l2 ON l1.cancione_id = l2.cancione_id 
                      AND l1.n_linea = l2.n_linea
        JOIN lineas l3 ON l2.cancione_id = l3.cancione_id 
                      AND l3.n_linea = l2.n_linea
                      AND l3.posicion_en_compas > l2.posicion_en_compas
        JOIN acordes a1 ON a1.id = l1.acorde_id
        JOIN acordes a2 ON a2.id = l2.acorde_id
        JOIN acordes a3 ON a3.id = l3.acorde_id
        WHERE a1.id != 170 AND a2.id != 170 AND a3.id != 170
          AND a1.grado != 8 AND a2.grado != 8 AND a3.grado != 8
          AND a1.grado = p_previo
          AND a2.grado = p_actual
          AND a3.grado != p_actual  -- Esta línea asegura que no sea el acorde actual
        GROUP BY a3.grado;
        
        -- Verificar si tenemos suficientes resultados
        SELECT COUNT(*) INTO v_count FROM temp_recomendaciones;
        
        -- Si no hay suficientes, buscar en línea siguiente
        IF v_count < p_limit THEN
            INSERT INTO temp_recomendaciones
            SELECT
                a3.grado AS siguiente,
                COUNT(*) AS frecuencia,
                COUNT(*) / (SELECT COUNT(*) FROM lineas l 
                           JOIN acordes a ON a.id = l.acorde_id
                           WHERE a.grado = p_actual) AS probabilidad,
                2 AS search_priority
            FROM lineas l1
            JOIN lineas l2 ON l1.cancione_id = l2.cancione_id 
                          AND l2.n_linea = l1.n_linea + 1
            JOIN lineas l3 ON l2.cancione_id = l3.cancione_id 
                          AND l3.n_linea = l2.n_linea
            JOIN acordes a1 ON a1.id = l1.acorde_id
            JOIN acordes a2 ON a2.id = l2.acorde_id
            JOIN acordes a3 ON a3.id = l3.acorde_id
            WHERE a1.id != 170 AND a2.id != 170 AND a3.id != 170
              AND a1.grado != 8 AND a2.grado != 8 AND a3.grado != 8
              AND a1.grado = p_previo
              AND a2.grado = p_actual
              AND a3.grado != p_actual  -- Excluir el acorde actual
              AND l3.posicion_en_compas > 0  -- No el primer acorde de la línea
            GROUP BY a3.grado;
        END IF;
        
        -- Devolver resultados ordenados
        SELECT siguiente, frecuencia, probabilidad
        FROM temp_recomendaciones
        ORDER BY search_priority, probabilidad DESC
        LIMIT p_limit;
        
        DROP TEMPORARY TABLE IF EXISTS temp_recomendaciones;
        
    ELSE
        -- Caso sin acorde previo (inicio de canción)
        CREATE TEMPORARY TABLE IF NOT EXISTS temp_inicio (
            siguiente INT,
            frecuencia INT,
            probabilidad DECIMAL(10,4),
            search_priority INT
        );
        
        -- Buscar en misma línea, posición siguiente
        INSERT INTO temp_inicio
        SELECT
            a2.grado AS siguiente,
            COUNT(*) AS frecuencia,
            COUNT(*) / (SELECT COUNT(*) FROM lineas l 
                       JOIN acordes a ON a.id = l.acorde_id
                       WHERE a.grado = p_actual) AS probabilidad,
            1 AS search_priority
        FROM lineas l1
        JOIN lineas l2 ON l1.cancione_id = l2.cancione_id 
                      AND l1.n_linea = l2.n_linea
        JOIN acordes a1 ON a1.id = l1.acorde_id
        JOIN acordes a2 ON a2.id = l2.acorde_id
        WHERE a1.id != 170 AND a2.id != 170
          AND a1.grado != 8 AND a2.grado != 8
          AND a1.grado = p_actual
          AND l2.posicion_en_compas > l1.posicion_en_compas
          AND a2.grado != p_actual  -- Excluir el acorde actual
        GROUP BY a2.grado;
        
        -- Verificar si tenemos suficientes resultados
        SELECT COUNT(*) INTO v_count FROM temp_inicio;
        
        -- Si no hay suficientes, buscar en línea siguiente
        IF v_count < p_limit THEN
            INSERT INTO temp_inicio
            SELECT
                a1.grado AS siguiente,
                COUNT(*) AS frecuencia,
                COUNT(*) / (SELECT COUNT(*) FROM lineas l 
                           JOIN acordes a ON a.id = l.acorde_id
                           WHERE a.grado = p_actual) AS probabilidad,
                2 AS search_priority
            FROM lineas l1
            JOIN acordes a1 ON a1.id = l1.acorde_id
            WHERE a1.id != 170
              AND a1.grado != 8
              AND l1.n_linea > 1
              AND a1.grado != p_actual  -- Excluir el acorde actual
            GROUP BY a1.grado;
        END IF;
        
        -- Devolver resultados ordenados
        SELECT siguiente, frecuencia, probabilidad
        FROM temp_inicio
        ORDER BY search_priority, probabilidad DESC
        LIMIT p_limit;
        
        DROP TEMPORARY TABLE IF EXISTS temp_inicio;
    END IF;
END
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('
DROP PROCEDURE IF EXISTS sp_obtener_recomendaciones_armonicas');
    }
};
