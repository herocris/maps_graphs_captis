<?php

namespace App\Transformers;

use App\Models\TipoMunicion;
use League\Fractal\TransformerAbstract;

class MunicionTransformer extends TransformerAbstract
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
    public function transform(TipoMunicion $municion)
    {
        return [
            'identificador' => (int)$municion->id,
            'descripcion' => (string)$municion->descripcion,            
            'fechaCreacion' => (string)$municion->created_at,
            'fechaActualizacion' => (string)$municion->updated_at,
            'fechaEliminacion' => isset($municion->deleted_at) ? (string) $user->deleted_at : null,
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
