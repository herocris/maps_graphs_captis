<?php

namespace App\Transformers;

use App\Models\EstructuraCriminal;
use League\Fractal\TransformerAbstract;

class EstructuraTransformer extends TransformerAbstract
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
    public function transform(EstructuraCriminal $estructura)
    {
        return [
            'identificador' => (int)$estructura->id,
            'descripcion' => (string)$estructura->descripcion,            
            'fechaCreacion' => (string)$estructura->created_at,
            'fechaActualizacion' => (string)$estructura->updated_at,
            'fechaEliminacion' => isset($estructura->deleted_at) ? (string) $user->deleted_at : null,
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
