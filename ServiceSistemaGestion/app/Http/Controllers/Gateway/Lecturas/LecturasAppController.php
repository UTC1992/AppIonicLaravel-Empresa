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
         $input=json_decode($request[0],true);
         if(count($input["listTareas"])<=0){
           $data=array();
           $data["mensaje"]="Debe enviar por lo menos una lectura para procesar";
           $data["status"]=false;
           return response($data,404)->header('Content-Type', 'application/json');
         }
         $id_tecnico= $input["id_tecn"];
         $arrayLectura=array();
         $arrayLectura["id_emp"]=$input["id_emp"];
         $arrayLectura["listTareas"]=$input["listTareas"];
         $result= json_decode($this->lecturasAppServices->updateLecturasService($arrayLectura),true);
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
            $result= $this->lecturasAppServices->insertarCatastrosAService($request);
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
         return response()->json($data);
       }
       $data["mensaje"]="No hay observaciones creadas para empresa con ID: ".$ID_EMP;
       $data["status"]=false;
       return response()->json($data);
     } catch (\Exception $e) {
        return response()->json("Error :".$e);
     }

   }
}
