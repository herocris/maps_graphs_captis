<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Parametro extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'descripcion',
        'tipo_parametro_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['descripcion','presentacion'])
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el parametro")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }
    public function tipo_parametro(){
        return $this->belongsTo('App\Models\Tipo_parametro');
    }
}
