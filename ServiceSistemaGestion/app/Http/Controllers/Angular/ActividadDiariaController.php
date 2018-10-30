<?php

namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadDiaria;
use Illuminate\Http\JsonResponse;
use App\Models\Tecnico;
use App\Models\ReconexionManual;
use App\Models\OrdenTrabajo;

class ActividadDiariaController extends Controller
{
  public function __construct(){
    $this->middleware('auth:api');
  }
  
    public function getActivitadesTecnico(){
      $actividades=new ActividadDiaria();
      $result=$actividades->getViewActivities();
      return response()->json($result);
    }

    public function getActivitiesTecnico($id_tecnico){
      $orden=new ActividadDiaria();
      $result=$orden->getDataActividadesTecnico($id_tecnico);
      return response()->json($result);
    }

    //validar y completar las actividades del tecnico
    public function validateActivitiesByTecnico($id_tecnico){
      try {
        if(!is_null($id_tecnico)){
          $tecnico=Tecnico::find($id_tecnico);
          $tecnico->asignado=0;
          $tecnico->save();
          if($tecnico){
            return response()->json(true);
          }else{
            return response()->json(false);
          }
        }
      } catch (\Exception $e) {
        return response()->json("Erro: ".$e);
      }
    }

    public function getActivitiesToDay($fecha,$id_tecnico,$actividad,$estado){
      try {
        $actividades=new ActividadDiaria();
        $result=$actividades->getAllActivitiesFilter($fecha,$id_tecnico,$actividad,$estado);
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }
    //obtener cantones de distribucion
    function getCantonesActividades($type){
      try {
        $actividades=new ActividadDiaria();
        $result=$actividades->getCantonstByActivityType($type);
        if($result){
          return response()->json($result);
        }else{
          return response()->json(false);
        }
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }
    // obtener sectores de canton
    public function getSectores($tipo_actividad,$canton){
      try {
        $actividad=new ActividadDiaria();
        $result=$actividad->getSectorsByActivities($tipo_actividad,$canton);
        if($result){
          return response()->json($result);
        }else{
          return response()->json(false);
        }

      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }

    }
    // obtener cantidad de Actividades_tecnico
    public function getActivitiesBySectors($tipo_actividad,$canton,$sector){
      try{
          $actividad=new ActividadDiaria();
          $result=$actividad->getActivitiesBySectors($tipo_actividad,$canton,$sector);
          if($result){
            return response()->json($result);
          }else{
            return response()->json(false);
          }
      }catch (\Exception $e){
        return response()->json("Error: ".$e);
      }
    }
    //actualizar actividades manuales
    public function validarActividadesManuales(){
      try {
        $actividad=new ActividadDiaria();
        $result=$actividad->where('estado',0)->where('n9cono','like','%40%')->get();
        $reconexion=new ReconexionManual();
        $result_rec=$reconexion->where('estado',0)->get();
        foreach ($result as $key => $value) {
          foreach ($result_rec as $key => $value_rec) {
              if($value->n9meco==$value_rec->medidor){
                  $act=ActividadDiaria::find($value->id_act);
                  $act->n9leco=$value_rec->lectura;
                  $act->estado=2;
                  $act->save();
                  // insertar registro orden trabajo tecnico
                  $ordenTrabajo=new OrdenTrabajo();
                  $ordenTrabajo->id_tecn=$value_rec->id_tecn;
                  $ordenTrabajo->id_act=$value->id_act;
                  $ordenTrabajo->estado=1;
                  $ordenTrabajo->fecha=date('Y-m-d');
                  $ordenTrabajo->observacion="Orden de trabajo finalizado";
                  $ordenTrabajo->tipo_actividad="40";
                  $ordenTrabajo->save();
              }
              //actualiza estado rec manuales
              $rec=ReconexionManual::find($value_rec->id_rec);
              $rec->estado=1;
              $rec->save();
          }
        }
        return response()->json(true);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }
//obtiene reconexiones manuales sin procesar
    public function getRecManualesSinProcesar(){
      try {
          $res=new ReconexionManual();
          $result=$res->where('estado',0)->get();
          $con=count($result);
          return response()->json($con);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }

  // consolidar actividades diarias
  public function consolidarActividadesDiarias($date){
    try {
      $actividad= new ActividadDiaria();
      $result=$actividad->where('estado',0)->where('created_at','like','%'.$date.'%')->get();
      foreach ($result as $key => $value) {
        if($value->estado==0){
          $act=ActividadDiaria::find($value->id_act);
          $act->estado=3;
          $act->referencia="Consolidado sin realizar";
          $act->save();
        }
      }
      return response()->json(true);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }

  // obtdener actividades consolidadas
  public function getActividadesByDate($date){
    try {
      $actividad=new ActividadDiaria();
      $result=$actividad->where('created_at','like','%'.$date.'%')->where('estado','!=',0)->get();
      return response()->json($result);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }


}
