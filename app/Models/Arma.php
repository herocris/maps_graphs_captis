<?php

namespace App\Models;

use App\Transformers\ArmaTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arma extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    use LogsActivity;

    public $transformer = ArmaTransformer::class;
    
    protected $fillable = [
        'descripcion',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['descripcion'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el arma")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function decomisos(){
        return $this->belongsToMany('App\Models\Decomiso')->withTrashed();
    }
}
