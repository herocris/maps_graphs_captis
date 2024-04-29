<?php

namespace App\Models;

use App\Transformers\DrogaTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Droga extends Model
{
    use HasApiTokens;
    use HasFactory;
    use SoftDeletes;

    use LogsActivity;

    public $transformer = DrogaTransformer::class;

    protected $fillable = [
        'descripcion',
        'tipo_droga_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['descripcion','tipo_droga_id'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " la droga")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function tipo_droga(){
        return $this->belongsTo('App\Models\TipoDroga')->withTrashed();
    }

    public function decomisos(){
        return $this->belongsToMany('App\Models\Decomiso')->withTrashed();
    }
    //funcion para obtener la presentación de droga en la tabla intermedia decomiso_drogas
    public function presentacion_de_droga($id){
        $prese=PresentacionDroga::withTrashed()->find($id);
        return $prese->descripcion;
    }
}
