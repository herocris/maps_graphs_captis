<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
// use App\Models\PresentacionDroga; ////cambio a partir de instalación de laravel ide helper

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Decomiso extends Model
{
    use HasFactory;
    use SoftDeletes;

    use LogsActivity;

    protected $fillable = [
        'fecha',
        'observacion',
        'direccion',
        'municipio_id',
        'institucion_id',
        'latitud',
        'longitud',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['fecha','observacion','direccion','municipio_id','institucion_id','latitud','longitud'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el decomiso")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function institucion(){
        return $this->belongsTo('App\Models\Institucion');
    }

    public function instituciones(){
        return $this->belongsToMany('App\Models\Institucion');
    }

    public function drogas(){
        //dd("jij");
        return $this->belongsToMany('App\Models\Droga')->withTimestamps()->withTrashed()->withPivot('cantidad','peso','presentacion_droga_id','id','deleted_at');
    }


    // public function presentacion_drogas(){ ////cambio a partir de instalación de laravel ide helper
    //     return $this->belongsTo('App\Models\PresentacionDroga')->using('App\Models\DecomisoDroga');
    // }

    public function precursores(){
        return $this->belongsToMany('App\Models\Precursor')->withTimestamps()->withTrashed()->withPivot('cantidad','volumen','presentacion_precursor_id','id','deleted_at');
    }

    // public function presentacion_precursores(){
    //     return $this->belongsTo('App\Models\PresentacionPrecursor')->withTrashed();
    // }

    public function armas(){
        return $this->belongsToMany('App\Models\Arma')->withTimestamps()->withTrashed()->withPivot('cantidad','id','deleted_at');
    }

    public function municiones(){
        return $this->belongsToMany('App\Models\TipoMunicion')->withTimestamps()->withTrashed()->withPivot('cantidad','id','deleted_at');
    }

    public function detenidos(){
        return $this->hasMany('App\Models\decomiso_detenido')->withTrashed();
    }

    public function transportes(){
        return $this->hasMany('App\Models\decomiso_transporte')->withTrashed();
    }
    // public function identificaciones(){
    //     return $this->belongsToMany('App\Models\Identificacion')->withTrashed();
    // }

    // public function estructuras(){
    //     return $this->belongsToMany('App\Models\EstructuraCriminal')->withTrashed();
    // }

    // public function ocupaciones(){
    //     return $this->belongsToMany('App\Models\Ocupacion')->withTrashed();
    // }

    // public function estados(){
    //     return $this->belongsToMany('App\Models\EstadoCivil')->withTrashed();
    // }

    // public function paises(){
    //     return $this->belongsToMany('App\Models\Pais');
    // }

    public function departamentos(){
        return $this->belongsTo('App\Models\Departamento');
    }

    public function municipio(){
        return $this->belongsTo('App\Models\Municipio');
    }

    

    
    
}
