<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grafica extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_ini',
        'fecha_fin',
        'tipo_tiempo',
        'tipo_decomiso',
        'drogas',
        'pres_drogas',
        'parametro',
        'tipo_gr',
    ];
}
