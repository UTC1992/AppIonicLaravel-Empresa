<?php

namespace App\Http\Controllers\AuthApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\User;
use App\Models\Empresa;
use App\Models\Modulo;
use App\Models\Observacion;
use Illuminate\Support\Facades\Hash;

class PermisosController extends Controller
{
  public function __construct(){
    $this->middleware('auth:api');
  }

  /**
   * obtener modulos a de empresa asignados
   */
  public function getModulosEmpresa(){
    try {
      $ID_EMP=$this->getIdEmpUserAuth();
      $modulos= new Modulo();
      $result= $modulos->getModulosEmpresa($ID_EMP);
      return response()->json($result);
    } catch (\Exception $e) {
        return response()->json("Error de servidor: ".$e);
    }

  }
  // obtener id empresa de usuario autenticado
  private function getIdEmpUserAuth(){
    try {
      $user_auth = auth()->user();
      $ID_EMP=$user_auth->id_emp;
      return $ID_EMP;
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }

/**
 *
 */
  public function getPlanEmpresa(){
    try {
      $ID_EMP=$this->getIdEmpUserAuth();
      $planes=Empresa::getPlanesEmpresa($ID_EMP);
      return response()->json($planes);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }

  }
//CRUD DE OBSERVACIONES
  /**
   * obtener observaciones
   */
   public function getObservacionesEmpresa(){
     try {
       $ID_EMP=$this->getIdEmpUserAuth();
       $observaciones=Observacion::where('id_emp',$ID_EMP)->get();
       return response()->json($observaciones);
     } catch (\Exception $e) {
       return response()->json("Error: ".$e);
     }

   }

/**
 * crear observación
 */
 public function crearObservacion(Request $request){
   try {
     $ID_EMP=$this->getIdEmpUserAuth();
     $observacion= new Observacion();
     $observacion->codigo=$request->codigo;
     $observacion->descripcion=$request->descripcion;
     $observacion->tipo=$request->tipo;
     $observacion->permite_lec=$request->permite_lec;
     $observacion->id_emp=$ID_EMP;
     $result= $observacion->save();
     if($result){
       return response()->json(true);
     }
      return response()->json(false);
   } catch (\Exception $e) {
     return response()->json("Error: ".$e);
   }

 }

/**
 *
 */
 public function actualizarObservacion(Request $request){
   try {
      $ID_EMP=$this->getIdEmpUserAuth();
      $observacion= Observacion::find($request->id_obs);
      $observacion->codigo=$request->codigo;
      $observacion->descripcion=$request->descripcion;
      $observacion->tipo=$request->tipo;
      $observacion->permite_lec=$request->permite_lec;
      $observacion->id_emp=$ID_EMP;
     $result= $observacion->save();
     if($result){
       return response()->json(true);
     }
      return response()->json(false);
   } catch (\Exception $e) {
     return response()->json("Error: ".$e);
   }

 }

 /**
  * borrar observacion
  */
  public function borrarObservacion($id_obs){
    try {
      $observacion= Observacion::find($id_obs);
      $res=$observacion->delete();
      if($res){
        return response()->json(true);
      }
        return response()->json(true);
    } catch (\Exception $e) {
       return response()->json("Error: ".$e);
    }

  }

  public function getObservacionById($id_obs)
  {
    $observacion=Observacion::find($id_obs);
    return response()->json($observacion);
  }


}
