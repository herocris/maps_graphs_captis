<?php

namespace App\Transformers;

use App\Models\Pais;
use League\Fractal\TransformerAbstract;

class PaisTransformer extends TransformerAbstract
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
    public function transform(Pais $pais)
    {
        return [
            'identificador' => (int)$pais->id,
            'descripcion' => (string)$pais->nombre,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'descripcion' => 'nombre',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
