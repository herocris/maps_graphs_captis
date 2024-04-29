<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class decomiso_droga extends Model
{
    use HasFactory;
    protected $table="decomiso_droga";

    use SoftDeletes;

    use LogsActivity;

    protected $fillable = [
        'cantidad',
        'peso',
        'decomiso_id',
        'droga_id',
        'presentacion_droga_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly([
        'cantidad',
        'peso',
        'decomiso_id',
        'droga_id',
        'presentacion_droga_id',])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el decomiso de droga")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    
}
