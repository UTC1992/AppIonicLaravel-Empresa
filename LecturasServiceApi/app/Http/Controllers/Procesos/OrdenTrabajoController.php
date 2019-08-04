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


    /**
     * obtiene rutas a distribuir a los tecnicos
     */
    public function getRutasElepco(){
      try {
        $result= DB::table("rutas_elepco")->get();
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }



    //===== le modifique para que cambie el estado del tecnico al asignar

    public function distribuirRutasTecnicos(Request $request){
          try {
            $cont=0;
            $input=$request->all();

            foreach ($input as $key => $value) {
              $cedula=$this->obtenerCedulaTecnico($value['idTecnico']);
              $dataInsert=array();
              $dataInsert["tecnico_id"]=$value["idTecnico"];
              $dataInsert["agencia"]=$value["agencia"];
              $dataInsert["sector"]=$value["sector"];
              $dataInsert["ruta"]=$value["ruta"];
              $dataInsert["cedula"]=$cedula;
              DB::table("rutas_tecnicos_decobo")->insert($dataInsert);
              DB::table("decobo_orden_temp")->where("agencia",$value["agencia"])
                                            ->where("sector",$value["sector"])
                                            ->where("ruta",$value["ruta"])
                                            ->update(["tecnico_id"=>$value['idTecnico'],"cedula_tecnico"=>$cedula]);
              DB::table("empresa_db.tbl_tecnico")->where("id_tecn",$value['idTecnico'])
                                                  ->update(["asignado"=>1]);
              $cont++;
            }

            $dataResponse=array();
            if($cont>0){
              $dataResponse["mensaje"]= "Ruta asignada correctamente ";
              $dataResponse["cantididad"]=$cont;
              $dataResponse["status"]= true;
              return response()->json($dataResponse);
            }
            $dataResponse["mensaje"]= "Error no se pudo asignar las rutas";
            $dataResponse["status"]= false;

            return response()->json($dataResponse);
          } catch (\Exception $e) {
            return response()->json("error: ".$e);
          }
        }

    private function obtenerCedulaTecnico($id_tecnico)
    {
      $tecnico= DB::table("empresa_db.tbl_tecnico")->where("id",$id_tecnico)->first();
      return $tecnico->cedula;
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


  public function obtenerRutasDecobo(){
    try {
      $result=DB::table("decobo_orden_temp")
                  ->select('agencia','sector','ruta',DB::raw('count(*) as cantidad'))
                  ->groupBy('agencia')
                  ->groupBy('sector')
                  ->groupBy('ruta')
                  ->get();
      return response()->json($result);
    } catch (\Exception $e) {
      return response()->json("error: ".$e);
    }

  }

  public function obtenerDistribucionTecnicos(){
    try {
      $result= DB::table("rutas_tecnicos_decobo")->get();
      return response()->json($result);
    } catch (\Exception $e) {
      return response()->json("error: ".$e);
    }

  }


  /*
      eliminacion de asignacion de una ruta a un tecnico
      si ya no esta asignado a mas rutas se cambia el estado
    */
    public function deleteRutaTecnico(Request $request){
      try {
        $input=$request[0];
        $result1= DB::table("rutas_tecnicos_decobo")
                    ->where("agencia",$input["agencia"])
                    ->where("sector",$input["sector"])
                    ->where("ruta",$input["ruta"])
                    ->where("tecnico_id",$input["idTecnico"])
                    ->delete();
        $result2=DB::table("decobo_orden_temp")
                    ->where("agencia",$input["agencia"])
                    ->where("sector",$input["sector"])
                    ->where("ruta",$input["ruta"])
                    ->where("tecnico_id",$input["idTecnico"])
                    ->update(["tecnico_id"=>0]);

        $result3= DB::table("rutas_tecnicos_decobo")
                    ->select("*")
                    ->where("tecnico_id",$input["idTecnico"])
                    ->get();

        if(count($result3) == 0){
          $result4 = DB::table("dashboard_db.tbl_tecnico")
                    ->where("id_tecn",$input['idTecnico'])
                    ->update(["asignado"=>0]);
        }

        if($result1 == 1 && $result2 > 0){
          return response()->json(true);
        } else {
          return response()->json(false);
        }

      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }
    }

    /**
     * actualiza distribucion
     */
    public function actualizarDistribuciones(){
      try {
        $result= DB::table("rutas_tecnicos_decobo")->get();
        foreach ($result as $key => $value) {
            $id_tecn=$value->tecnico_id;
            $cedula=$value->cedula;
            $res=DB::table("decobo_orden_temp")
                ->where("agencia",$value->agencia)
                ->where("sector",$value->sector)
                ->where("ruta",$value->ruta)
                ->where("tecnico_id",0)
                ->update(["tecnico_id"=>$id_tecn,"cedula_tecnico"=>$cedula]);
        }

        return response()->json(true);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }


/**
 * borrar orden temp decobo
 */
 public function truncateTableDecobo(){
   try {
     DB::table('decobo')->truncate();
     return response()->json(true);
   } catch (\Exception $e) {
     return response()->json("error: ".$e);
   }

 }
}
