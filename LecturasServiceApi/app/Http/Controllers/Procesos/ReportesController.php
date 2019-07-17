<?php
namespace App\Http\Controllers\Procesos;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportesController extends Controller
{


    public function __construct()
    {

    }

    /**
     * 1.- consulta como en el de cortes con filtros
    	*de fecha
    	*lector
    	*agencia
    	*estado
    	*en la consulta debe traerse los datos con el lector que los iso
    	*similar al de cortes
      *
     */
     public function consultarLecturas(Request $request){
       try {
         $fecha=$request[0]['fecha'];
         $lector=$request[0]['lector'];
         $agencia=$request[0]['agencia'];
         $estado=$request[0]['estado'];
         $result = DB::table("decobo_orden_temp")
                  ->where("mes",$fecha)
                  ->where(function($query) use($agencia){
                    if($agencia!="empty")
                      $query->where("agencia",$agencia);
                    })
                  ->where(function($query) use($lector){
                    if($lector!="empty")
                      $query->where("tecnico_id",$lector);
                    })
                  ->where(function($query) use($estado){
                    if($estado!="empty")
                        $query->where("estado",$estado);
                      })
                  ->get();
          return response()->json($result);
       } catch (\Exception $e) {
         return response()->json("error: ".$e);
       }

     }
   /**
    * consulta de errores de consumo
    */
    public function consultarErroresConsumo(){
      try {
        $result = DB::table("decobo_orden_temp")->where("alerta",2)->get();
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }
    /**
     * consula errores de lecturas
     */
    public function consultarErroresLecturas(){
      try {
        $result = DB::table("decobo_orden_temp")->where("alerta",1)->get();
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }

    /**
     * consulta envios y avance del mes
     */
    public function consultarEnvios($mes){
      try {
        $result = DB::table("decobo_orden_temp")
                  ->select(DB::raw('agencia,sector,ruta,tecnico_id,count(tecnico_id) as cantidad'))
                  ->where("mes",$mes)
                  ->groupBy("agencia")
                  ->groupBy("sector")
                  ->groupBy("ruta")
                  ->groupBy("tecnico_id")
                  ->get();
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }

}
