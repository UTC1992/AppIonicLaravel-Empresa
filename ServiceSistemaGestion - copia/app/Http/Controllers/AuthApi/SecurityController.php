<?php

namespace App\Http\Controllers\AuthApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\User;
use App\Models\Empresa;
use App\Models\Historial;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{

  public function __construct(){
    $this->middleware('auth:api');
  }
  //obtener datos usuario autenticado
  public function getUserData(){
    try {
       $this->createHistoryUser("Login","Login user en cliente angular","auth");
        $user = auth()->user();
        $us=new User();
        if($us->validaEmpresaActiva($user->id_emp)){
          $us=new User();
          $usuario=$us->getUserInfo($user->id);
          return response()->json($usuario);
        }else{
            return response()->json(false);
        }
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
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

  private function getUserAuthId(){
    $user_auth = auth()->user();
    $ID_US=$user_auth->id;
    return $ID_US;
  }
    // editar nombre usuario
  public function editarNombre(Request $request){
    try {
      if(!is_null($request->nombre)){
        $this->createHistoryUser("Editar","Actualizacion nombre","auth");
        $user_id=$this->getUserAuthId();
        $user=User::find($user_id);
        $user->name=$request->nombre;
        $user->save();
        if($user){
          return response()->json(true);
        }else{
          return response()->json(false);
        }
      }else{
        return response()->json(1);
      }
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }
  // editar email
  public function editarEmail(Request $request){
    try {
      if(!is_null($request->email)){
        $this->createHistoryUser("Editar","Actualizacion email","auth");
        $user_id=$this->getUserAuthId();
        $user=User::find($user_id);
        $user->email=$request->email;
        $user->save();
        if($user){
          return response()->json(true);
        }else{
          return response()->json(false);
        }
      }else{
        return response()->json(1);
      }
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }
  // editar contraseña
  public function editarPassword(Request $request){
    try {
      if(!is_null($request->password)){
        $this->createHistoryUser("Editar","Actualizacion password","auth");
        $user_id=$this->getUserAuthId();
        $user=User::find($user_id);
        $user->password=Hash::make($request->password);
        $user->save();
        if($user){
          return response()->json(true);
        }else{
          return response()->json(false);
        }
      }else{
        return response()->json(1);
      }
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }
// editar nombre empresa
public function editarEmpresa(Request $request){
  try {
    if(!is_null($request->empresa)){
      $ID_EMP=$this->getIdEmpUserAuth();
      $empresa=Empresa::find($ID_EMP);
      $empresa->nombre=$request->empresa;
      $empresa->save();
      if($empresa){
        return response()->json(true);
      }else{
        return response()->json(false);
      }
    }else{
      return response()->json(1);
    }
  } catch (\Exception $e) {
    return response()->json("Error: ".$e);
  }

}

/**
 * crea historial de acciones de usuario de la aplicación
 */
private function createHistoryUser($accion,$observacion,$modulo){
  try {
    $user = auth()->user();
    $historial= new Historial();
    $historial->accion=$accion;
    $historial->observacion=$observacion;
    $historial->usuario=$user->id;
    $historial->modulo=$modulo;
    $historial->empresa=$this->getIdEmpUserAuth();
    $historial->save();
  } catch (\Exception $e) {
    return response()->json("Error: ".$e);
  }
}


}
