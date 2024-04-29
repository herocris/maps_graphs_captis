<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class decomiso_detenido extends Model
{
    use HasFactory;
    use SoftDeletes;

    use LogsActivity;

    protected $fillable = [
        'nombre',
        'alias',
        'identidad',
        'edad',
        'decomiso_id',
        'identificacion_id',
        'estructura_id',
        'ocupacion_id',
        'estado_civil_id',
        'pais_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['nombre','alias','identidad','edad','decomiso_id','identificacion_id','estructura_id','ocupacion_id','estado_civil_id','pais_id'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el detenido")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function decomiso(){
        return $this->belongsTo('App\Models\Decomiso')->withTrashed();
    }

    public function identificacion(){
        return $this->belongsTo('App\Models\Identificacion')->withTrashed();
    }

    public function estructura(){
        return $this->belongsTo('App\Models\EstructuraCriminal');
    }

    public function ocupacion(){
        return $this->belongsTo('App\Models\Ocupacion')->withTrashed();
    }

    public function estado_civil(){
        return $this->belongsTo('App\Models\EstadoCivil');
    }

    public function pais(){
        return $this->belongsTo('App\Models\Pais');
    }
}
