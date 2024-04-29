<?php

namespace App\Transformers;

use App\Models\Institucion;
use League\Fractal\TransformerAbstract;

class InstitucionTransformer extends TransformerAbstract
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
    public function transform(Institucion $institucion)
    {
        return [
            'identificador' => (int)$institucion->id,
            'descripcion' => (string)$institucion->nombre,            
            'contacto' => (string)$institucion->contacto,
            'telefono' => (string)$institucion->telefono,
            'fechaCreacion' => (string)$institucion->created_at,
            'fechaActualizacion' => (string)$institucion->updated_at,
            'fechaEliminacion' => isset($institucion->deleted_at) ? (string) $user->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'descripcion' => 'nombre',            
            'contacto' => 'contacto',
            'telefono' => 'telefono',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
