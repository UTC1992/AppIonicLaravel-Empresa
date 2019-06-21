<?php

namespace App\Http\Controllers\Gateway\Lecturas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LecturasAppService;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Hash;
use App\Models\Observacion;

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
        $result=Tecnico::where('cedula',$cedula)->where('password',$password)->where('borrado',0)->first();
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
        return response($result)->header('Content-Type', 'applicationIjson');
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }
    }

    /**
     * envio de lecturas al servicio de proceso lecturas
     */
     public function updateLecturas(Request $request){
       try {

        if(!$request->listTareas){
          $data=array();
          $data["mensaje"]="Debe enviar por lo menos una lectura para procesar";
          $data["status"]=false;
          return response($data,404)->header('Content-Type', 'application/json');
        }

        $id_tecnico= $request->id_tecn;

        $data = array(
           'id_tecn' => $request->id_tecn,
           'id_emp' => $request->id_emp,
           'listTareas' => json_encode($request->listTareas, true)
          );

        $result= json_decode($this->lecturasAppServices->updateLecturasService($data), true);

        if($result["status"]){
          $tecnico= Tecnico::find($id_tecnico);
          $tecnico->asignado=0;
          $tecnico->save();
          return response($result)->header('Content-Type', 'application/json');
        }

        return response($result)->header('Content-Type', 'application/json');
        //return response(count($input["listTareas"]));
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }

     }

     /**
      * ingresar catastros
      */
      public function insertarCatastros(Request $request){
        try {
          if(!is_null($request)){
            $result= $this->lecturasAppServices->insertarCatastrosService($request->all());
            return response($result)->header('Content-Type', 'application/json');
          }
        } catch (\Exception $e) {
           return response()->json("Error :".$e);
        }

      }


  /**
   * obtener observaciones
   */
   public function getObservaciones($ID_EMP){
     try {
       $result=Observacion::where('id_emp',$ID_EMP)->get();
       if(count($result)>0){
         $data["observaciones"]=$result;
         $data["status"]=true;
         return response()->json($result);
       }
       $data["mensaje"]="No hay observaciones creadas para empresa con ID: ".$ID_EMP;
       $data["status"]=false;
       return response()->json($result);
     } catch (\Exception $e) {
        return response()->json("Error :".$e);
     }

   }

   public
}
