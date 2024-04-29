<?php

namespace App\Transformers;

use App\Models\Municipio;
use League\Fractal\TransformerAbstract;

class MunicipioTransformer extends TransformerAbstract
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
    public function transform(Municipio $municipio)
    {
        return [
            'identificador' => (int)$municipio->id,
            'descripcion' => (string)$municipio->nombre,
            'identificadorDepartamento' => (int)$municipio->departamento_id,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'descripcion' => 'nombre',
            'identificadorDepartamento' => 'departamento_id',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
