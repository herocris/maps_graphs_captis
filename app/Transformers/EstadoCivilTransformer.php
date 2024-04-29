<?php

namespace App\Transformers;

use App\Models\EstadoCivil;
use League\Fractal\TransformerAbstract;

class EstadoCivilTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(EstadoCivil $estado)
    {
        return [
            'identificador' => (int)$estado->id,
            'descripcion' => (string)$estado->descripcion,            
            'fechaCreacion' => (string)$estado->created_at,
            'fechaActualizacion' => (string)$estado->updated_at,
            'fechaEliminacion' => isset($estado->deleted_at) ? (string) $user->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'descripcion' => 'descripcion',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
