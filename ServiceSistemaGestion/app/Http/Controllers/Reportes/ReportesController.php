<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Reportes;


class ReportesController extends Controller
{

  public function __construct(){
    //$this->middleware('auth:api');
  }
  /**
   *
   */
  public function getEstadisticaDiariaCortes(Request $request){
    try {
      $empresa=$request->empresa;
      $inicio=$request->inicio;
      $fin=$request->fin;
       $reportes=Reportes::getEstadisticaActividadesDiaria($empresa,$inicio,$fin);
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
}
