<?php

namespace App\Http\Controllers\AuthApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{

  public function __construct(){
    $this->middleware('auth:api');
  }
  //obtener datos usuario autenticado
  public function getUserData(){
    try {
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


}
