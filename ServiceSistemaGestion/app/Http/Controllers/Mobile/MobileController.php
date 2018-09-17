<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrdenTemp;
use App\Models\Tecnico;
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

}
