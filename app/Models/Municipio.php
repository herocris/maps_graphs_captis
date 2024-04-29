<?php

namespace App\Models;

use App\Transformers\MunicipioTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    public $transformer = MunicipioTransformer::class;

    protected $fillable = [
        'nombre',
        'departamento_id',
    ];

    public function departamento(){
        return $this->belongsTo('App\Models\Departamento');
    }

    public function decomisos(){
        return $this->hasMany('App\Models\Decomiso')->withTrashed();
    }
}
