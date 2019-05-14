<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Reportes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{

  public function __construct(){
    $this->middleware('auth:api');
  }
  /**
   *
   */
  public function getEstadisticaDiariaCortes(Request $request){
    try {
      $empresa=$request[0]['empresa'];
      $inicio=$request[0]['inicio'];
      $fin=$request[0]['fin'];
       $reportes=Reportes::getEstadisticaActividadesDiaria($empresa,$inicio,$fin);
       if(count($reportes)>0){
         return response()->json($reportes,200);
       }
       return response()->json('No data', 206);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e,500);
    }

  }

/**
 *
 */
  public function getEstadisticaMesCortes(Request $request){
    try {
      $empresa=$request->empresa;
      $mes=$request->mes;
       $reportes=Reportes::getEstadisticaActividadesPorMes($empresa,$mes);
       if(count($reportes)>0){
         return response()->json($reportes,200);
       }
       return response()->json('No Data', 206);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e,500);
    }

  }

/**
 *
 */
  public function getEstadisticaTecnicosCortes(Request $request){
    try {
      $empresa=$request->empresa;
      $inicio=$request->inicio;
      $fin=$request->fin;
       $reportes=Reportes::getEstadisticaActividadesTecnicos($empresa,$inicio,$fin);
       if(count($reportes)>0){
         return response()->json($reportes,200);
       }
       return response()->json('No Data', 206);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e,500);
    }

  }

/**
 *
 */
  public function getEstadisticaTecnicosMesCortes(Request $request){
    try {
      $empresa=$request->empresa;
      $mes=$request->mes;
       $reportes=Reportes::getEstadisticaActividadesTecnicosMes($empresa,$mes);
       if(count($reportes)>0){
         return response()->json($reportes,200);
       }
       return response()->json('No Data', 206);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e,500);
    }

  }

  /**
   * reporte de estado actividades por medidor
   */

   public function reporteMedidorFecha(Request $request){
     try {
       $empresa=$request->empresa;
       $inicio=$request->fecha_inicio;
       $fin=$request->fecha_fin;
       $medidor=$request->medidor;
       $reportes=Reportes::getReporteMedidor($empresa,$inicio,$fin,$medidor);
       if(count($reportes)>0){
         return response()->json($reportes,200);
       }
       return response()->json('No Data', 206);
     } catch (\Exception $e) {
       return response()->json("Error: ".$e,500);
     }

   }

   /**
    * obtener reporte de actividades diarias
    */
    public function productividadTecnico($fecha)
    {
      try {
        $ID_EMP=$this->getIdEmpUserAuth();
        $tecnicos = DB::table('tbl_tecnico as T0')
                   ->join('tbl_ordentrabajo as T1', 'T0.id_tecn','=','T1.id_tecn')
                   ->select('T0.nombres','T0.apellidos','T0.id_tecn','T1.created_at')
                   ->where('T0.borrado',0)
                   ->where('T0.id_emp', $ID_EMP)
                   ->where('T1.created_at','like','%'.$fecha.'%')
                   ->where('T0.actividad','cortes')
                   ->groupBy('T0.id_tecn')->get();
        $prodArray=array();
        $cont=0;
        foreach ($tecnicos as $key => $value) {
          $prodArray[$cont]["id_tecn"]=$value->id_tecn;
          $prodArray[$cont]["tecnico"]=$value->nombres.' '.$value->apellidos;
           $prodArray[$cont]["fecha"]=date("Y-m-d",strtotime($value->created_at));
          $prodArray[$cont]["asignadas"] = DB::table('tbl_actividaddiaria as T0')
                     ->join('tbl_ordentrabajo as T1', 'T0.id_act','=','T1.id_act')
                     ->where('id_emp', $ID_EMP)
                     ->where('T1.id_tecn',$value->id_tecn)->count();
         $actArray=array();
         $prodArray[$cont]["orden_trabajo"]=Reportes::getActividadByTecnico($value->id_tecn,$fecha);
         $prodArray[$cont]["obtenidas_app"]=DB::table('tbl_actividaddiaria as T0')
                    ->join('tbl_ordentrabajo as T1', 'T0.id_act','=','T1.id_act')
                    ->where('id_emp', $ID_EMP)
                    ->where('T1.discharged',1)
                    ->where('T1.id_tecn',$value->id_tecn)->count();
         $prodArray[$cont]["enviadas_app"]=DB::table('tbl_actividaddiaria as T0')
                     ->join('tbl_ordentrabajo as T1', 'T0.id_act','=','T1.id_act')
                     ->where('id_emp', $ID_EMP)
                     ->where('T1.sent',1)
                     ->where('T1.id_tecn',$value->id_tecn)->count();
         $prodArray[$cont]["realizadas"]=DB::table('tbl_actividaddiaria as T0')
                    ->join('tbl_ordentrabajo as T1', 'T0.id_act','=','T1.id_act')
                    ->where('id_emp', $ID_EMP)
                    ->where('T0.estado',2)
                    ->where('T1.id_tecn',$value->id_tecn)->count();
         $prodArray[$cont]["faltantes"]=DB::table('tbl_actividaddiaria as T0')
                    ->join('tbl_ordentrabajo as T1', 'T0.id_act','=','T1.id_act')
                    ->where('id_emp', $ID_EMP)
                    ->where('T0.estado',3)
                    ->where('T1.id_tecn',$value->id_tecn)->count();

          $cont++;
        }
        return response()->json($prodArray);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e,500);
      }

    }
   
   // obtener id empresa de usuario autenticado
   private function getIdEmpUserAuth(){
     try {
       $user_auth = auth()->user();
       $ID_EMP=$user_auth->id_emp;
       return $ID_EMP;
     } catch (\Exception $e) {
       return response()->json("Error: ".$e);
     }
   }
}
