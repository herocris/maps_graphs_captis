<?php

namespace App\Models;

use App\Transformers\OcupacionTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ocupacion extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    use LogsActivity;

    public $transformer = OcupacionTransformer::class;

    protected $fillable = [
        'descripcion',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['descripcion'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " la ocupación")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function detenidos(){
        return $this->hasMany('App\Models\decomiso_detenido')->withTrashed();
    }
}
