<?php

namespace App\Http\Controllers\AuthApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\User;
use App\Models\Empresa;
use App\Models\Modulo;
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

}
