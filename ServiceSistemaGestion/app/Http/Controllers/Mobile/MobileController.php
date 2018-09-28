<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrdenTemp;
use App\Models\Tecnico;
use App\Models\ActividadDiaria;
use App\Models\ReconexionManual;
use Illuminate\Http\JsonResponse;

class MobileController extends Controller
{

//obtiene data para los tecnicos
  public function getTechnicalData($cedula){
    $tecnico=new Tecnico();
    if($tecnico->where('cedula',$cedula)->count()>0){
        $res=$tecnico->where('cedula',$cedula)->get();
        $orden=new ActividadDiaria();
        $result=$orden->getDataActividadesTecnico($res[0]['id_tecn']);
        return response()->json($result);
    }else{
      return response()->json("Técnico no encontrado");
    }

  }
  //actualiza datos temporales
  public function updateActivities(Request $request){
    foreach ($request as $key => $value) {

    }
  }

  //insertar reconexion desde movil
  public function insertReconexionManual(Request $request){
      $input = $request->all();
      if(!is_null($input)){
        $res=ReconexionManual::create($input);
        if($res){
          return response()->json(true);
        }else{
          return response()->json(false);
        }
      }else{
        return response()->json("Data Empty");
      }
  }


  
}
