<?php

namespace App\Http\Controllers;

use App\Models\Arma;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Droga;
use App\Models\EstadoCivil;
use App\Models\EstructuraCriminal;
use App\Models\Identificacion;
use App\Models\Institucion;
use App\Models\Pais;
use App\Models\Municipio;
use App\Models\Ocupacion;
use App\Models\Precursor;
use App\Models\presentacionDroga;
use App\Models\presentacionPrecursor;
use App\Models\TipoMunicion;
use App\Models\User;
use App\Http\Controllers\Admin\ApiController;
use Illuminate\Support\Facades\Auth;

class ApiParametrosController extends ApiController
{
    public function __construct()
    {
         parent::__construct();
         $this->middleware('client.credentials')->only(['Drogas','Precursores']);
         $this->middleware('scope:primer-scope')->only('Drogas');
         $this->middleware('scope:segundo-scope')->only('Precursores');
        
    }
   
    

    public function Departamentos()
    {
        $departamentos=Departamento::get();
        return $this->showAll($departamentos);
    }

    public function Municipios()
    {
        $municipios=Municipio::get();
        return $this->showAll($municipios);
    }

    public function Paises()
    {
        $paises=Pais::get();
        return $this->showAll($paises);
    }

    public function Pais(Pais $pais)
    {
        //$pais=Pais::findOrFail($id);//findOrFail en lugar de find para que la exepcion ModelNotFoundException se pueda ejecutar
        return $this->showOne($pais);
    }

    public function Drogas()
    {
        $drogas=Droga::get();
        //return response()->json($drogas);
        return $this->showAll($drogas);
    }
    public function Droga(Droga $droga)
    {
        //$pais=Droga::findOrFail($id);
        return $this->showOne($droga);
    }

    public function Precursores()
    {
        $precursores=Precursor::get();
        return $this->showAll($precursores);
    }

    public function PresentacionesDroga()
    {
        $presentaciones=presentacionDroga::get();
        return $this->showAll($presentaciones);
    }

    public function PresentacionesPrecursor()
    {
        $presentaciones=presentacionPrecursor::get();
        return $this->showAll($presentaciones);
    }

    public function Armas()
    {
        $armas=Arma::get();
        return $this->showAll($armas);
    }

    public function Municiones()
    {
        $municiones=TipoMunicion::get();
        return $this->showAll($municiones);
    }

    public function EstadoCiviles()
    {
        $estados=EstadoCivil::get();
        return $this->showAll($estados);
    }

    public function Identificaciones()
    {
        $identificaciones=Identificacion::get();
        return $this->showAll($identificaciones);
    }

    public function Ocupaciones()
    {
        $ocupaciones=Ocupacion::get();
        return $this->showAll($ocupaciones);
    }

    public function EstructurasCriminales()
    {
        $estructuras=EstructuraCriminal::get();
        return $this->showAll($estructuras);
    }

    public function Instituciones()
    {
        $instituciones=Institucion::get();
        return $this->showAll($instituciones);
    }

    public function Usuarios()
    {
        $usuarios=User::get();
        return $this->showAll($usuarios);
    }


}
