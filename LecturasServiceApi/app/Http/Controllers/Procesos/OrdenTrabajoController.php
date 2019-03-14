<?php
namespace App\Http\Controllers\Procesos;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Configuracion;
use App\Models\OrdenTrabajo;
use App\Traits\ApiResponser;

class OrdenTrabajoController extends Controller
{
  use ApiResponser;

    public function __construct()
    {

    }

    // metodo devuelve actividades del dia por idempresa y paginado
    public function getAllActivitiesByEmpresa(Request $request){
      try {
        $tabla="";
        $paginado=(int)$request->paginado;
        $idEmpresa= $request->idEmpresa;
        $configuracion=$this->getConfigCompany($idEmpresa);
        if($paginado<=0){
         return response()->json("error: Ingrese un número de paginado valido");
        }
        if(count($configuracion)<=0){
         return response()->json("error: No existen actividades para la empresa con ID: ".$idEmpresa." en la base de datos");
        }
        foreach ($configuracion as $key => $value) {
          if($value->key=="table"){
            $tabla=$value->value;
            break;
          }
        }
        $actividades=$this->getAll($tabla,$paginado);
        return response()->json($actividades);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }
    }

/**
 * Distribucion de actividades a tecnicos
 */
    public function distribuirActividades(Request $request){
      try {
        $tabla="";
        $campoFiltro=$request->columna;
        $valorCampoFiltro=$request->valor;
        $IdTecnico=$request->idTecnico;
        $idEmpresa=$request->idEmpresa;
        $configuracion=$this->getConfigCompany($idEmpresa);
        if(count($configuracion)<=0){
         return response()->json("error: No existen registros para la empresa con ID: ".$idEmpresa." en la base de datos");
        }
          foreach ($configuracion as $key => $value) {

            if($value->key=="table"){
              $tabla->$value->value;
            }
          if($value->key=="column" && $value->value==$campoFiltro){
            $campoFiltro=$value->value;
          }
        }
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }
    }


    // buscar en la  base de datos
  private function getAll($tabla, $paginado){
    try {
      $actividades = DB::table($tabla)->where('estado', 0)->paginate($paginado);
      return $actividades;
    } catch (\Exception $e) {
        return response()->json("error: ".$e);
    }

  }

  private function getConfigCompany($idCompany){
    $config = DB::table('configuraciones')->where('idEmpresa', $idCompany)->get();
    return $config;
  }

  private function getDataFilter($tabla,$campoFiltro,$valorCampoFiltro){
    try {
      $actividades = DB::table($tabla)->where($campoFiltro,$valorCampoFiltro)->where('estado',0)->get();
      return $actividades;
    } catch (\Exception $e) {
        return response()->json("error: ".$e);
    }
  }
}
