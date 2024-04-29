<?php

namespace App\Transformers;

use App\Models\Identificacion;
use League\Fractal\TransformerAbstract;

class IdentificacionTransformer extends TransformerAbstract
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
    public function transform(Identificacion $identificacion)
    {
        return [
            'identificador' => (int)$identificacion->id,
            'descripcion' => (string)$identificacion->descripcion,            
            'fechaCreacion' => (string)$identificacion->created_at,
            'fechaActualizacion' => (string)$identificacion->updated_at,
            'fechaEliminacion' => isset($identificacion->deleted_at) ? (string) $user->deleted_at : null,
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
