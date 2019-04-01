<?php

namespace App\Http\Controllers\Gateway\Lecturas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LecturasService;

class LecturasController extends Controller
{


    private $lecturasServices;

    public function __construct(LecturasService $lecturasServices){
      $this->middleware('auth:api');
      $this->lecturasService=$lecturasServices;
    }


    /**
     * subir archivo al servidor, se conecta al servicio de lecturas api
     */
     public function uploadFile(Request $request){
         try {
           $file=$request->file;
           $ID_EMP=$this->getIdEmpUserAuth();
           $result=$this->lecturasService->uploadFile($file,$ID_EMP);
           return response($result);
         } catch (\Exception $e) {
           return response()->json("Error :".$e);
         }
     }


     /**
      * obtener campos filtro de distribución de rutas
      */
      public function getFilterFiels(){
        try {
          $data["idEmpresa"]=$this->getIdEmpUserAuth();
          $result=$this->lecturasService->getFilterFields($data);
          return response($result);
        } catch (\Exception $e) {
          return response()->json("Error :".$e);
        }
      }

      /**
       * obtiene datos de filtro por campos desde servicio de lecturas
       */
      public function getDataFilter(Request $request){
        try {

          $data=array();
          $data["id"]=$this->getIdEmpUserAuth();
          $data["whereData"]=$request->whereData;
          $data["select"]=$request->select;
          $result= $this->lecturasService->getDataFilter($data);
          return response($result);
        } catch (\Exception $e) {
          return response()->json("Error :".$e);
        }
      }

      /**
       * obtiene id de lecturas a distribuir del servicio de lecturas
       */
      public function getDataDistribution(Request $request){
        try {
          $data=array();
          $data["data"]=$request->whereData;
          $data["id"]=$this->getIdEmpUserAuth();
          $result= $this->lecturasService->getDataDistributionService($data);
          return response($result);
        } catch (\Exception $e) {
            return response()->json("Error :".$e);
        }

      }

      /**
       * obtener datos del primer filtro de distribución
       */
    public function getFirstFilterFields(){
      try {
        $data["id"]=$this->getIdEmpUserAuth();
        $result=$this->lecturasService->getFirstFilterFields($data);
        return response($result);
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }
    }



    /**
     * distribuir ruta a técnico
     */

     public function distribuirRuta(Request $request){
       try {
         $data= array();
         $data["lecturas"]= $request->lecturas;
         $data["idTecnico"]= $request->idTecnico;
         $data["idEmpresa"]= $this->getIdEmpUserAuth();
         $result=$this->lecturasService->distribuirRutaService($data);
         return response($result);
       } catch (\Exception $e) {
          return response()->json("Error :".$e);
       }
     }
     // obtener id empresa de usuario autenticado
     private function getIdEmpUserAuth(){
       try {
         $user_auth = auth()->user();
         $ID_EMP=$user_auth->id_emp;
         return $ID_EMP;
       } catch (\Exception $e) {
         return response()->json("Error :".$e);
       }
     }

}
