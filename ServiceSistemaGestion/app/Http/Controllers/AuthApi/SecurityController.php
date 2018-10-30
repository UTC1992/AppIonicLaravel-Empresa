<?php

namespace App\Http\Controllers\AuthApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\User;

class SecurityController extends Controller
{

  public function __construct(){
    $this->middleware('auth:api');
  }
  //obtener datos usuario autenticado
  public function getUserData(){
    try {
        $user = auth()->user();
        return response()->json($user);
      } catch (\Exception $e) {
        return response()->json("Erro: ".$e);
      }
    }

}
