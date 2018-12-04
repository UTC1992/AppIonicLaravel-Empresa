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
        $result=$orden->getDataActividadesTecnico($res[0]['id_tecn'],$res[0]['id_emp']);
        return response()->json($result);
    }else{
      return response()->json(false);
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
            if($value['estado']=='2' && $value['n9leco']>0){
              $actividad->n9leco=$value['n9leco'];
              $actividad->n9lect=$value['n9leco'];
              $actividad->estado=$value['estado'];
              $actividad->referencia="Finalizado";
              $actividad->n9feco=date('Y')."".date('m')."".date('d');
              $actividad->n9fecl=date('Y')."".date('m')."".date('d');
            } else {
              $actividad->estado=3;
              $actividad->referencia="No Finalizado";
              $actividad->n9leco=0;
              $actividad->n9lect=0;
              $actividad->n9feco=0;
              $actividad->n9fecl=0;
            }
            $actividad->save();

            $ordenTrabajo=new OrdenTrabajo();
            $res=$ordenTrabajo->where('id_act','=',$value['id_act'])->first();
            $res->observacion=$value['observacion'];
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

  public function insertReconexionManual(Request $request){
    try {
      if(!is_null($request)){
        $input=$request->json()->all();
        //obtener cantidad de datos enviados
        $cantidad = count($input);
        //contador de registros
        $contador = 0;
        //obteniendo el id del tecnico
        $tecnico= new Tecnico();
        $resTecnico = $tecnico->where('cedula','=',$input[$cantidad-1]['cedula'])->first();
        $idTec = $resTecnico->id_tecn;
        $idEmpTec=$resTecnico->id_emp;

        for ($i = 0; $i < $cantidad-1; $i++)
        {
          if($contador < $cantidad-1){
            $recManual= new ReconexionManual();
            $recManual->lectura = $input[$i]['lectura'];
            $recManual->medidor = $input[$i]['medidor'];
            $recManual->observacion = $input[$i]['observacion'];
            $recManual->foto = $input[$i]['foto'];
            $recManual->id_tecn = $idTec;
            $recManual->estado = 0;
            $recManual->id_emp=$idEmpTec;
            $recManual->save();
          }
          $contador++;
        }
        if($contador == $cantidad-1){
          return response()->json(true);
        }
      }else{
        return response()->json(false);
      }
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }






}
