<?php

namespace App\Transformers;

use App\Models\Departamento;
use League\Fractal\TransformerAbstract;

class DepartamentoTransformer extends TransformerAbstract
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
    public function transform(Departamento $departamento)
    {
        return [
            'identificador' => (int)$departamento->id,
            'descripcion' => (string)$departamento->nombre,
            'identificadorDePais' => (int)$departamento->pais_id,            
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'descripcion' => 'nombre',
            'identificadorDePais' => 'pais_id',
        ];
        
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
