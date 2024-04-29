<?php

namespace App\Transformers;

use App\Models\Ocupacion;
use League\Fractal\TransformerAbstract;

class OcupacionTransformer extends TransformerAbstract
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
    public function transform(Ocupacion $ocupacion)
    {
        return [
            'identificador' => (int)$ocupacion->id,
            'descripcion' => (string)$ocupacion->descripcion,            
            'fechaCreacion' => (string)$ocupacion->created_at,
            'fechaActualizacion' => (string)$ocupacion->updated_at,
            'fechaEliminacion' => isset($ocupacion->deleted_at) ? (string) $user->deleted_at : null,
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
