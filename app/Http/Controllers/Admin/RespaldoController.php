<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Arma;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RespaldoController extends Controller
{
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver respaldo'])->only('mostrar_generarRespaldo'); //auth:sanctum','verifieds
        $this->middleware(['permission:crear respaldo'])->only('generarRespaldo'); //auth:sanctum','verifieds
        //$this->middleware(['permission:ver respaldo'])->only('mostrar_restaurarRespaldo'); //auth:sanctum','verifieds
        //$this->middleware(['permission:ver respaldo'])->only('cargar_Respaldo');
    }
    public function mostrar_generarRespaldo()
    {
        return view('admin.respaldo.generar');  
    }

    public function generarRespaldo()
    {
        // $arch=fopen("crear.sql","a") or die ("Error al crear"); 
        // fwrite($arch,"create database if not exist 'base_captis2';");
        // fclose($arch);
        //$respuesta2=shell_exec('C:\xampp\mysql\bin\mysql -hlocalhost -uroot -p1234 -e "create database if not exists 6base_captis2" 2>&1');
        //$respuesta3=shell_exec('C:\xampp\mysql\bin\mysql -hlocalhost -uroot -p1234 6base_captis2 < C:\xampp\htdocs\captis\storage\app\public\restaurarBase\base_captis.sql  2>&1');
        //dd($respuesta3);
        //conversión de coleccion de base de datos a json
        /*$users1 = Arma::all();
        $users = json_encode($users1);
        debug($users);
        //creación de archivo json en la carpeta public
        $handler=fopen("arcvhivo.json","w+");
        fwrite($handler, $users);   
        fclose($handler); 
        
        //conversión de archivo json anteriormente generado en arreglo
        $json = json_decode(file_get_contents("arcvhivo.json"), true); 

         


        debug(collect($json));*/
        //$comando='/generador_backup.bat';
        // $process=new Process($comando);
        // $process->run();
        //$comando='generador_backup.bat';

        $respuesta=shell_exec('C:\xampp\mysql\bin\mysqldump -hlocalhost -uroot -p1234 base_captis > C:\xampp\htdocs\captis\public\respaldoBase\base_captis.sql');
        
        //dd($respuesta);
        // if (!$process->isSuccessfull()) {
        //     throw new ProcessFailedException($process);
        // }
        return response()->download('respaldoBase\base_captis.sql');////////////////////////////////////////////////para descargar archivo
        
        //    return view('admin.respaldo.generar');  
        }


    public function mostrar_restaurarRespaldo()
    {
        //dd("sdfs");
        return view('admin.respaldo.restaurar');  
    }

    public function cargar_Respaldo(Request $request)
    {
        //
        //dd($request->tipo);
        //$request['tipo']='';
        if ($request->base!=null) {
            if (pathinfo($request->base->getClientOriginalName(), PATHINFO_EXTENSION)=='sql') {
                $request['tipo']='sql';
                //dd($request);
            }
        }
        
        $rules = [
            
            'base'=> 'required',
        ];
        //dd($request->base);
        $messages = [
            'base.required' => 'debe cargar un archivo de base de datos',
            //'tipo.required' => 'El archivo debe ser de tipo sql',
        ];

        $this->validate($request, $rules, $messages);
        //$mostrar=false;
        $error="";
        $respuesta="";

        if($request->hasFile("base")){  
            $nombre=$request->file("base")->getClientOriginalName();
            $file=$request->file("base")->storeAs('public/restaurarBase',$nombre);
            
            
            // $arch=fopen("crear.sql","a") or die ("Error al crear"); 
            // fwrite($arch,"create database if not exist 'base_captis2';");
            // fclose($arch); 
            ///////////////////crea la base de datos
            $respuesta2=shell_exec('C:\xampp\mysql\bin\mysql -hlocalhost -uroot -p1234 -e "create database if not exists base_captis" 2>&1');
            ///////////////////////// llena la base de datos creada
            $respuesta=shell_exec('C:\xampp\mysql\bin\mysql -hlocalhost -uroot -p1234 base_captis < C:\xampp\htdocs\captis\storage\app\public\restaurarBase\base_captis.sql  2>&1');
            // if ($respuesta!=null) {
            //     $mostrar=true;
            //     $error="de crear la base de datos primero";
            // } else {
            //     $mostrar=false;
            // }
            
            
            
            // try {
            //     Schema::hasTable('users');
            //     $mostrar=false;
            //     //return view('auth.login',compact('mostrar','error'));
            // } catch (\Throwable $th) {
                
            // }
            //Storage::delete('restaurarBase/base_captis.sql');
            return view('auth.login');
        }else{
            $error="debe cargar un archivo";
            $mostrar=true;
            return view('admin.respaldo.restaurar',compact('error'));
        }
      
        
        
        
        
        // debug($respuesta);
        // debug($mostrar);
        // debug($respuesta);
        //     //dd($mostrar);
        //     debug($mostrar);
        //     debug($error);
        //     debug("llega aqui");
          
    }
}
