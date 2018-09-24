<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrdenTemp;
use App\Models\Tecnico;
use App\Models\ReconexionManual;
use Illuminate\Http\JsonResponse;

class MobileController extends Controller
{

  public function getTechnicalData($cedula){
    $tecnico=new Tecnico();
    if($tecnico->where('cedula',$cedula)->count()>0){
        $res=$tecnico->where('cedula',$cedula)->get();
        $orden=new OrdenTemp();
        $result=$orden->where('id_tecn',$res[0]['id_tecn'])->where('estado',0)->get();
        return response()->json($result);
    }else{
      return response()->json("Técnico no encontrado");
    }

  }
  //actualiza datos temporales
  public function updateOrdenTemp(Request $request){
    $orden=OrdenTemp::find($request->id_or);
    $input=$request->all();
    $orden->update($input);
  }

  //insertar reconnexion desde movil
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
