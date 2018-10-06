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

    public function getActivitiesToDay($fecha){
      try {
        $actividades=new ActividadDiaria();
        $result=$actividades->where('created_at','like','%'.$fecha.'%')->get();
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }
}
