<?php

namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadDiaria;
use Illuminate\Http\JsonResponse;

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

}
