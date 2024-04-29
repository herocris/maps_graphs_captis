<?php

namespace App\Transformers;

use App\Models\Precursor;
use League\Fractal\TransformerAbstract;

class PrecursorTransformer extends TransformerAbstract
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
    public function transform(Precursor $precursor)
    {
        return [
            'identificador' => (int)$precursor->id,
            'descripcion' => (string)$precursor->descripcion,            
            'fechaCreacion' => (string)$precursor->created_at,
            'fechaActualizacion' => (string)$precursor->updated_at,
            'fechaEliminacion' => isset($precursor->deleted_at) ? (string) $user->deleted_at : null,
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
