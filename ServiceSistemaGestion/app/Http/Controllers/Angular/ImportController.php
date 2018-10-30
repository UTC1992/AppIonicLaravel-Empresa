<?php

namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadDiaria;
use Illuminate\Support\Facades\Storage;
use Excel;
class ImportController extends Controller
{

  public function __construct(){
    $this->middleware('auth:api');
  }
    public function index(){
      return view('import');
    }
    public function getExcel(Request $request){
      $archivo = $request->file('archivo');
       $nombre_original=$archivo->getClientOriginalName();
	     $extension=$archivo->getClientOriginalExtension();
       $r1=Storage::disk('public')->put($nombre_original,  \File::get($archivo) );
       $ruta  =  'public/storage/'.$nombre_original;

       if($r1){
            Excel::selectSheetsByIndex(0)->load($ruta, function($hoja) {
		        $hoja->each(function($fila) {
				      	$actividad=new ActividadDiaria;
                $actividad->n9sepr=$fila->n9sepr;
                $actividad->n9cono=$fila->n9cono;
                $actividad->n9cocu=$fila->n9cocu;
                $actividad->n9selo=$fila->n9selo;
                $actividad->n9cozo=$fila->n9cozo;
                $actividad->n9coag=$fila->n9coag;
                $actividad->n9cose=$fila->n9cose;
                $actividad->n9coru=$fila->n9coru;
                $actividad->n9seru=$fila->n9seru;
                $actividad->n9vano=$fila->n9vano;
                $actividad->n9plve=$fila->n9plve;
                $actividad->n9vaca=$fila->n9vaca;
                $actividad->n9esta=$fila->n9esta;
                $actividad->n9cocn=$fila->n9cocn;
                $actividad->n9fech=$fila->n9fech;
                $actividad->n9meco=$fila->n9meco;
                $actividad->n9seri=$fila->n9seri;
                $actividad->n9feco=$fila->n9feco;
                $actividad->n9leco=$fila->n9leco;
                $actividad->n9manp=$fila->n9manp;
                $actividad->n9cocl=$fila->n9cocl;
                $actividad->n9nomb=$fila->n9nomb;
                $actividad->n9cedu=$fila->n9cedu;
                $actividad->n9prin=$fila->n9prin;
                $actividad->n9nrpr=$fila->n9nrpr;
                $actividad->n9refe=$fila->n9refe;
                $actividad->n9tele=$fila->n9tele;
                $actividad->n9medi=$fila->n9medi;
                $actividad->n9fecl=$fila->n9fecl;
                $actividad->n9lect=$fila->n9lect;
                $actividad->n9cobs=$fila->n9cobs;
                $actividad->n9cob2=$fila->n9cob2;
                $actividad->n9ckd1=$fila->n9ckd1;
                $actividad->n9ckd2=$fila->n9ckd2;
                $actividad->cusecu=$fila->cusecu;
                $actividad->cupost=$fila->cupost;
                $actividad->cucoon=$fila->cucoon;
                $actividad->cucooe=$fila->cucooe;
                $actividad->cuclas=$fila->cuclas;
                $actividad->cuesta=$fila->cuesta;
                $actividad->cutari=$fila->cutari;
                $actividad->estado=false;
                $actividad->referencia="doc excel";
                $actividad->id_emp=1;
		            $actividad->save();


		        });

            });

            return view("mensajes.msj_correcto")->with("msj"," Usuarios Cargados Correctamente");

       }
       else
       {
       	    return view("mensajes.msj_rechazado")->with("msj","Error al subir el archivo");
       }

    }

    public function importCsvFile(){
      $archivo = $request->file('archivo');
       $nombre_original=$archivo->getClientOriginalName();
	     $extension=$archivo->getClientOriginalExtension();
       $path = $request->file('archivo')->getRealPath();


            Excel::selectSheetsByIndex(0)->load($path, function($hoja) {
		        $hoja->each(function($fila) {
				      	$actividad=new ActividadDiaria;
                $actividad->n9sepr=$fila->n9sepr;
                $actividad->n9cono=$fila->n9cono;
                $actividad->n9cocu=$fila->n9cocu;
                $actividad->n9selo=$fila->n9selo;
                $actividad->n9cozo=$fila->n9cozo;
                $actividad->n9coag=$fila->n9coag;
                $actividad->n9cose=$fila->n9cose;
                $actividad->n9coru=$fila->n9coru;
                $actividad->n9seru=$fila->n9seru;
                $actividad->n9vano=$fila->n9vano;
                $actividad->n9plve=$fila->n9plve;
                $actividad->n9vaca=$fila->n9vaca;
                $actividad->n9esta=$fila->n9esta;
                $actividad->n9cocn=$fila->n9cocn;
                $actividad->n9fech=$fila->n9fech;
                $actividad->n9meco=$fila->n9meco;
                $actividad->n9seri=$fila->n9seri;
                $actividad->n9feco=$fila->n9feco;
                $actividad->n9leco=$fila->n9leco;
                $actividad->n9manp=$fila->n9manp;
                $actividad->n9cocl=$fila->n9cocl;
                $actividad->n9nomb=$fila->n9nomb;
                $actividad->n9cedu=$fila->n9cedu;
                $actividad->n9prin=$fila->n9prin;
                $actividad->n9nrpr=$fila->n9nrpr;
                $actividad->n9refe=$fila->n9refe;
                $actividad->n9tele=$fila->n9tele;
                $actividad->n9medi=$fila->n9medi;
                $actividad->n9fecl=$fila->n9fecl;
                $actividad->n9lect=$fila->n9lect;
                $actividad->n9cobs=$fila->n9cobs;
                $actividad->n9cob2=$fila->n9cob2;
                $actividad->n9ckd1=$fila->n9ckd1;
                $actividad->n9ckd2=$fila->n9ckd2;
                $actividad->cusecu=$fila->cusecu;
                $actividad->cupost=$fila->cupost;
                $actividad->cucoon=$fila->cucoon;
                $actividad->cucooe=$fila->cucooe;
                $actividad->cuclas=$fila->cuclas;
                $actividad->cuesta=$fila->cuesta;
                $actividad->cutari=$fila->cutari;
                $actividad->estado=false;
                $actividad->referencia="sksk";
                $actividad->id_emp=1;
		            $actividad->save();
		        });

            });

            return view("mensajes.msj_correcto")->with("msj"," Usuarios Cargados Correctamente");


    }

    public function getExcel2(Request $request){
       if($request->hasFile('archivo')){
         $path = $request->file('archivo')->getRealPath();
         $data = \Excel::load($path)->get();
         if($data->count()){
           foreach ($data as $key => $value) {
             $actividades_list[] = [
               'n9sepr'=> $value->n9sepr,
               'n9cono'=> $value->n9cono,
               'n9cocu'=> $value->n9cocu,
               'n9selo'=> $value->n9selo,
               'n9cozo'=> $value->n9cozo,
               'n9coag'=> $value->n9coag,
               'n9cose'=> $value->n9cose,
               'n9coru'=> $value->n9coru,
               'n9seru'=> $value->n9seru,
               'n9vano'=> $value->n9vano,
               'n9plve'=> $value->n9plve,
               'n9vaca'=> $value->n9vaca,
               'n9esta'=> $value->n9esta,
               'n9cocn'=> $value->n9cocn,
               'n9fech'=> $value->n9fech,
               'n9meco'=> $value->n9meco,
               'n9seri'=> $value->n9seri,
               'n9feco'=> $value->n9feco,
               'n9leco'=> $value->n9leco,
               'n9manp'=> $value->n9manp,
               'n9cocl'=> $value->n9cocl,
               'n9nomb'=> $value->n9nomb,
               'n9cedu'=> $value->n9cedu,
               'n9prin'=> $value->n9prin,
               'n9nrpr'=> $value->n9nrpr,
               'n9refe'=> $value->n9refe,
               'n9tele'=> $value->n9tele,
               'n9medi'=> $value->n9medi,
               'n9fecl'=> $value->n9fecl,
               'n9lect'=> $value->n9lect,
               'n9cobs'=> $value->n9cobs,
               'n9cob2'=> $value->n9cob2,
               'n9ckd1'=> $value->n9ckd1,
               'n9ckd2'=> $value->n9ckd2,
               'cusecu'=> $value->cusecu,
               'cupost'=> $value->cupost,
               'cucoon'=> $value->cucoon,
               'cucooe'=> $value->cucooe,
               'cuclas'=> $value->cuclas,
               'cuesta'=> $value->cuesta,
               'cutari'=> $value->cutari,
               'estado'=> false,
               'referencia'=>"archivo csv",
               'id_emp'=>1
             ];
           }
           if(!empty($actividades_list)){
               ActividadDiaria::insert($actividades_list);
               return view("mensajes.msj_correcto")->with("msj"," Actividades Cargados Correctamente");
           }
         }else{
           return view("mensajes.msj_rechazado")->with("msj","Error al subir el archivo");
         }
       }
    }
}
