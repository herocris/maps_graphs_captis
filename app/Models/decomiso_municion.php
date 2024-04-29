<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class decomiso_municion extends Model
{
    use HasFactory;

    protected $table="decomiso_tipo_municion";

    use SoftDeletes;

    use LogsActivity;

    protected $fillable = [
        'cantidad',
        'decomiso_id',
        'tipo_municion_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly([
        'cantidad',
        'decomiso_id',
        'tipo_municion_id',])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el decomiso del arma")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }
}
