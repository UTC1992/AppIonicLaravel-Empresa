<?php
namespace App\Http\Controllers\Procesos;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Configuracion;
use App\Traits\ApiResponser;
use App\Models\OrdenTrabajo;

class LecturasController extends Controller
{
  use ApiResponser;

    public function __construct()
    {

    }

/**
 * obtiene lecuras por mes consolidados
 */
    public function index($idEmpresa,$mes){
      try {
        $tabalaCompany=$this->getTableCompany($idEmpresa);
        $res= OrdenTrabajo::getLecturasConsolidadas($tabalaCompany,$mes);
        return $res;
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


      public function validarConsumosDecobo(){
        try {
          $datalecturas= DB::table("decobo_orden_temp")->get();
          foreach ($datalecturas as $key => $value) {
            if($value->nueva_lectura > $value->lectura){
              DB::table("decobo_orden_temp")->where("medidor",$value->medidor)->update(["alerta"=>1]);
            }
            if($value->lectura == "0" ){

            }
          }
        } catch (\Exception $e) {
          return response()->json("error: ".$e);
        }

      }


}
