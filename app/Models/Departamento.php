<?php

namespace App\Models;

use App\Transformers\DepartamentoTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    
    public $transformer = DepartamentoTransformer::class;
    
    protected $fillable = [
        'nombre',
    ];

    public function municipios(){
        return $this->hasMany('App\Models\Municipio');
    }

    public function pais(){
        return $this->belongsTo('App\Models\Pais');
    }

    public function decomisos(){
        return $this->belongsToMany('App\Models\Decomiso')->withTrashed(); ////cambio a partir de instalación de laravel ide helper
    }
}
