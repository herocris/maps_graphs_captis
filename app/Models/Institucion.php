<?php

namespace App\Models;

use App\Transformers\InstitucionTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    use LogsActivity;

    public $transformer = InstitucionTransformer::class;
    protected $fillable = [
        'nombre',
        'contacto',
        'telefono',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['nombre','contacto','telefono'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " la institución")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function decomisos(){
        return $this->hasMany('App\Models\Decomiso')->withTrashed();
    }
    public function decomisos_(){
        return $this->belongsToMany('App\Models\Decomiso')->withTrashed();
    }

    public function users(){
        return $this->hasMany('App\Models\User');
    }
}
