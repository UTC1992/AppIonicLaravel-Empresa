<?php

namespace App\Http\Controllers\Gateway\Lecturas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LecturasAppService;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Hash;

class LecturasAppController extends Controller
{
  //inject lecturas service
    public $lecturasAppServices;

    public function __construct(LecturasAppService $lecturasAppServices){
    //  $this->middleware('auth:api');
      $this->lecturasAppServices=$lecturasAppServices;
    }


    /**
     * login técnicos de lecturas
     */
    public function login(Request $request){
      try {
        $cedula=$request->cedula;
        $password=$request->password;
        $result=Tecnico::where('cedula',$cedula)->where('password',$password)->first();
        if($result){
            return response()->json($result);
        }
        return response()->json(false);
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }

    }


    /**
     * obtiene rutas de los técnicos
     */
    public function index($idEmpresa,$idTecnico){
      try {
        $result=$this->lecturasAppServices->getDataDistributionByTecnicoService($idEmpresa,$idTecnico);
        return response($result);
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }
    }

    /**
     * envio de lecturas al servicio de proceso lecturas
     */
     public function updateLecturas(){
       try {
         
       } catch (\Exception $e) {
         return response()->json("Error :".$e);
       }

     }


}
