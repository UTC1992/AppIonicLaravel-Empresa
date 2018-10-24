<?php

namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadDiaria;
use Illuminate\Http\JsonResponse;
use App\Models\Tecnico;

class ActividadDiariaController extends Controller
{
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

}
