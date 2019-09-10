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

         if($this->validaMesActual($fecha)){
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
         }else{
           $result = DB::table("decobo_historial")
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
         }

       } catch (\Exception $e) {
         return response()->json("error: ".$e);
       }

     }

     private function validaMesActual($mes){

       $result= DB::table("decobo_orden_temp")->where("mes",$mes)->exists();
       return $result;

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

/**
 * consulta de data por filtro de errores y agencia
 */
    public function consultarErroresDataTemp(Request $request){
      try {

        $agencia=$request['agencia'];
        $sector=$request['sector'];
        $tipo_error=$request['tipo'];
      //  $revision=$request["revision"];
        //  return response()->json($agencia)
        /*                        ->where(function($query) use($revision){
                                  if($revision!="empty")
                                    $query->where("revision",$revision);
                                  })*/
        if($agencia!="" && $sector!="" && $tipo_error!=""){
          $result = DB::table("decobo_orden_temp")
                        ->where("agencia",$agencia)
                        ->where("sector",$sector)
                        ->where("alerta",$tipo_error)
                        ->get();
          return response()->json($result);
        }

        return response()->json(false);

      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }
    }



/*reporte catastros */


public function consultarCatastrosProcesados($mes,$anio,$agencia,$sector){
  try {

    $result='';
    if($this->compruebaFechaEnTemporal($mes,$anio)){
      $result = DB::table("catastros as T0")
                    ->join("decobo_orden_temp as T1", 'T0.medidor','=','T1.medidor')
                    ->where("T1.mes",$mes)
                    ->where("T1.fecha_lectura",'like','%'.$anio.'%')
                    ->where("T1.agencia",$agencia)
                    ->where("T1.sector",$sector)
                    ->get();
    }else{
      $result = DB::table("catastros as T0")
                    ->join("decobo_historial as T1", 'T0.medidor','=','T1.medidor')
                    ->where("T1.mes",$mes)
                    ->where("T1.fecha_lectura",'like','%'.$anio.'%')
                    ->where("T1.agencia",$agencia)
                    ->where("T1.sector",$sector)
                    ->get();
    }

  return response()->json($result);

  } catch (\Exception $e) {
    return response()->json("error: ".$e);
  }

}

/**
 * consultar catastros nuevos
 */

 public function consultaCatastrosNuevos($mes){
   try {

     return $result = DB::table("catastros")
                  ->where("mes",$mes)
                  ->where("estado",0)
                  ->get();

   } catch (\Exception $e) {
     return response()->json("error: ".$e);
   }

 }


/**
 * comprueba fecha donde consultar
 */
private function compruebaFechaEnTemporal($mes,$anio){
  try {
    return $res = DB::table("decobo_orden_temp")
                      ->where("mes",$mes)
                      ->where("fecha_lectura",'like','%'.$anio.'%')
                      ->exists();
  } catch (\Exception $e) {
      return response()->json("error: ".$e);
  }

}

/*fin catastros*/

}
