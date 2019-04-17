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
    public function getAllRutasByEmpresa(Request $request){
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
 * Distribucion de rutas de lecturas a técnicos de empresa
 */
    public function distribuirRutas(Request $request){
      try {
        $idTecnico= $request->idTecnico;
        $idEmpresa=$request->idEmpresa;
        $lecturasArray=array();
        $lecturasArray= $request->lecturas;
        $tablaLecturasCompany=$this->getTableCompany($idEmpresa);
        $dataInsert=array();
        $cont=0;

        $ids=array();
        foreach ($lecturasArray as $key => $value) {
          $data=array();
          $data["estado"]=0;
          $data["id_lectura"]=$value["id"];
          $data["id_empresa"]=$idEmpresa;
          $data["id_tecnico"]=$idTecnico;
          $dataInsert[$cont]=$data;

          $ids[$cont]=$value["id"];

          if($cont>=1000){
            DB::table('orden_trabajo')->insert($dataInsert);

            DB::table($tablaLecturasCompany)
                   ->whereIn('id',$ids)
                   ->update(['estado' => 1]);

            $ids=array();
            $dataInsert=array();
            $cont=0;
          }
          $cont++;
        }
        if(count($dataInsert)>0){
          DB::table('orden_trabajo')->insert($dataInsert);
          DB::table($tablaLecturasCompany)
                 ->whereIn('id',$ids)
                 ->update(['estado' => 1]);
          $dataInsert=array();
        }

        return response()->json(true);

      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }
    }



 /**
  * obtiene nombre de tabla de actividades de la configuración de la empresa
  */
     private function getTableCompany($idEmpresa){
       try {
         $tabla="";
         $config = DB::table('configuraciones')->where('idEmpresa', $idEmpresa)->get();
         foreach ($config as $key => $value) {
           if($value->key=="table"){
             $tabla=$value->value;
             break;
           }
         }
         return $tabla;
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

  /**
   * obtiene datos de asignacion de tecnicos
   */
  public function getOrdenTrabajoTecnicos($idEmpresa){
    try {
      $tabla=$this->getTableCompany($idEmpresa);
      $result=OrdenTrabajo::getOrdenTrabajoAsignado($tabla);
      return response()->json($result,200);
    } catch (\Exception $e) {
      return response()->json("error: ".$e);
    }

  }

}
