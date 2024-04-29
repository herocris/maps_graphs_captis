<?php

namespace App\Models;

use App\Transformers\PaisTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    public $transformer = PaisTransformer::class;

    protected $fillable = [
        'nombre',
    ];

    public function departamentos(){
        return $this->hasMany('App\Models\Departamento');
    }

    public function detenidos(){
        return $this->hasMany('App\Models\decomiso_detenido')->withTrashed();
    }

    public function transportes(){
        return $this->hasMany('App\Models\decomiso_transporte')->withTrashed();
    }
}
