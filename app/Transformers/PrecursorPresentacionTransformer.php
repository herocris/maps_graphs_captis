<?php

namespace App\Transformers;

use App\Models\presentacionPrecursor;
use League\Fractal\TransformerAbstract;

class PrecursorPresentacionTransformer extends TransformerAbstract
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
    public function transform(presentacionPrecursor $presentacion)
    {
        return [
            'identificador' => (int)$presentacion->id,
            'descripcion' => (string)$presentacion->descripcion,            
            'fechaCreacion' => (string)$presentacion->created_at,
            'fechaActualizacion' => (string)$presentacion->updated_at,
            'fechaEliminacion' => isset($presentacion->deleted_at) ? (string) $user->deleted_at : null,
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
