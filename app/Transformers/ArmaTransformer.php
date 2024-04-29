<?php

namespace App\Transformers;

use App\Models\Arma;
use League\Fractal\TransformerAbstract;

class ArmaTransformer extends TransformerAbstract
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
    public function transform(Arma $arma)
    {
        return [
            'identificador' => (int)$arma->id,
            'descripcion' => (string)$arma->descripcion,
            'fechaCreacion' => (string)$arma->created_at,
            'fechaActualizacion' => (string)$arma->updated_at,
            'fechaEliminacion' => isset($arma->deleted_at) ? (string) $user->deleted_at : null,
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
