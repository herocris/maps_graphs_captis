<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class decomiso_transporte extends Model
{
    use HasFactory;
    use SoftDeletes;

    use LogsActivity;

    protected $fillable = [
        'tipo',
        'marca',
        'modelo',
        'color',
        'placa',
        'decomiso_id',
        'pais_pro_id',
        'pais_des_id',
        'dep_pro_id',
        'dep_des_id',
        'mun_pro_id',
        'mun_des_id',

    ];

    public function getActivitylogOptions(): LogOptions
    {
        $even_=new LogOptions;
        
        return LogOptions::defaults()
        ->logOnly(['tipo',
        'marca',
        'modelo',
        'color',
        'placa',
        'decomiso_id',
        'pais_pro_id',
        'pais_des_id',
        'dep_pro_id',
        'dep_des_id',
        'mun_pro_id',
        'mun_des_id',])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "Se ha " . $even_->nombre_evento($eventName). " el transporte")
        ->useLogName(Auth::user()->name);
        // Chain fluent methods for configuration options
    }

    public function decomiso(){
        return $this->belongsTo('App\Models\Decomiso')->withTrashed();
    }

    public function pais(){
        return $this->belongsTo('App\Models\Pais');
    }

    public function paiss($id){
        $pais=Pais::find($id);
        //dd($pais);
        return $pais->nombre;
    }

    public function depto($id){
        return (Departamento::find($id)!=null)?(Departamento::find($id)->nombre):"";
    }

    public function muni($id){
        return (Municipio::find($id)!=null)?(Municipio::find($id)->nombre):"";
    }

    public function departamento(){
        return $this->belongsTo('App\Models\Departamento');
    }

    public function municipio(){
        return $this->belongsTo('App\Models\Municipio');
    }

}
