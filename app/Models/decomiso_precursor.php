<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class decomiso_precursor extends Model
{
    use HasFactory;
    protected $table="decomiso_precursor";

    use SoftDeletes;

    use LogsActivity;

    protected $fillable = [
        'cantidad',
        'volumen',
        'decomiso_id',
        'precursor_id',
        'presentacion_precursor_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly([
        'cantidad',
        'volumen',
        'decomiso_id',
        'precursor_id',
        'presentacion_precursor_id',])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el decomiso de precursor")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }
}
