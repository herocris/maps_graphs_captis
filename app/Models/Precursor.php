<?php

namespace App\Models;

use App\Transformers\PrecursorTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Precursor extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    use LogsActivity;

    public $transformer = PrecursorTransformer::class;

    protected $fillable = [
        'descripcion',
        'presentacion',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['descripcion','presentacion'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el precursor")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function decomisos(){
        return $this->belongsToMany('App\Models\Decomiso')->withTrashed();
    }

    //funcion para obtener la presentación de droga en la tabla intermedia decomiso_drogas
    public function presentacion_de_precursor($id){
        $prese=PresentacionPrecursor::withTrashed()->find($id);
        return $prese->descripcion;
    }
}
