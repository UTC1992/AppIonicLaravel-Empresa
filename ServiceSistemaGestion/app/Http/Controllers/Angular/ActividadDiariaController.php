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
}
