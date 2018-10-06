<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrdenTemp;
use App\Models\Tecnico;
use App\Models\OrdenTrabajo;
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

  //actualiza datos enviados desde aplicativo movil
  public function updateActivities(Request $request){
    try {
      if(!is_null($request)){
        $input=$request->json()->all();
        $con=0;
        foreach ($input as $key => $value) {
          $con++;
            $actividad=ActividadDiaria::find($value['id_act']);
            $actividad->n9leco=$value['n9leco'];
            $actividad->estado=$value['estado'];
              if($value['estado']=='2'){
                $actividad->referencia="Finalizado";
              }
            $actividad->save();

            $ordenTrabajo=new OrdenTrabajo();
            $res=$ordenTrabajo->where('id_act','=',$value['id_act'])->first();
            if(!is_null($value['observacion'])){
              $res->observacion=$value['observacion'];
            }else{
              $res->observacion="Sin novedad";
            }
            $res->estado=1;
            $res->foto=$value['foto'];
            $res->save();

            $tecnico=Tecnico::find($value['id_tecn']);
            $tecnico->asignado=0;
            $tecnico->save();
        }
        if($con>0){
          return response()->json(true);
        }
      }else{
        return response()->json(false);
      }
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
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
