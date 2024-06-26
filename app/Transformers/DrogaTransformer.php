<?php

namespace App\Transformers;

use App\Models\Droga;
use League\Fractal\TransformerAbstract;

class DrogaTransformer extends TransformerAbstract
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
    public function transform(Droga $droga)
    {
        return [
            'identificador' => (int)$droga->id,
            'descripcion' => (string)$droga->descripcion,            
            'fechaCreacion' => (string)$droga->created_at,
            'fechaActualizacion' => (string)$droga->updated_at,
            'fechaEliminacion' => isset($droga->deleted_at) ? (string) $user->deleted_at : null,
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
