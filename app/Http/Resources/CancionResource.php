<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CancionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'titulo'     => $this->titulo,
            'metrica'    => $this->metrica,
            'capo'       => $this->capo,
            'comentario' => $this->comentario,
            'var'        => $this->var,
            'usuario'    => [
                'id'    => $this->user->id,
                'nombre' => $this->user->name,
                'email' => $this->user->email,
            ],
            'artista_id' => $this->artista_id,
            'artista'    => $this->artista?->nombre,
            'genero'     => $this->genero?->nombre,
            'tonalidad'  => $this->tonalidade?->nombre,
            'rating'    => $this->rating,
            'privada'=> $this->privada,
            'lineas' => $this->letras->map(fn($letra) => [
                'n_linea' => $letra->n_linea,
                'texto'   => $letra->texto,
                'acordes' => array_values(
                    $this->lineas
                        ->where('n_linea', $letra->n_linea)
                        ->map(fn($linea) => [
                            'posicion_en_compas' => $linea->posicion_en_compas,
                            'id'       => $linea->acorde_id,
                            'variacion'       => $linea->variacion ?? '',
                            'acorde' => $linea->acordes->where('id', $linea->acorde_id)->pluck('nombre')[0] ?? ''
                        ])
                        ->toArray() // Convertir en array para evitar índices en la salida JSON
                )
            ]),
        ];
    }
}
