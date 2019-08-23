<?php

namespace App\Http\Controllers\Gateway\Lecturas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LecturasService;
use App\Models\Tecnico;

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
           $mes=$request->mes;
           $result=$this->lecturasService->uploadFile($file,$ID_EMP,$mes);
           return response($result)->header('Content-Type', 'applicationIjson');
         } catch (\Exception $e) {
           return response()->json("Error :".$e);
         }
     }


     /**
      * obtener campos filtro de distribuci¨®n de rutas
      */
      public function getFilterFiels(){
        try {
          $data["idEmpresa"]=$this->getIdEmpUserAuth();
          $result=$this->lecturasService->getFilterFields($data);
          return response($result)->header('Content-Type', 'applicationIjson');
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
       * obtener datos del primer filtro de distribuci¨®n
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
     * distribuir ruta a t¨¦cnico
     */

     public function distribuirRuta(Request $request){
       try {

         $result=$this->lecturasService->distribuirRutaService($request->all());
         $this->changeEstadoTecnicoLecturas($request->idTecnico);
         return response($result)->header('Content-Type', 'applicationIjson');
       } catch (\Exception $e) {
          return response()->json("Error :".$e);
       }
     }

     /**
      * asigna tecnico
      */
     private function changeEstadoTecnicoLecturas($id){
       try {
          $tecnico=Tecnico::find($id);
          $tecnico->asignado=1;
          $tecnico->save();
       } catch (\Exception $e) {

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

     /**
      * obtiene datis de asignacion desde el servicio lecturas
      */
      public function getOrdenTrabajoTecnicosLecturas(){
        try {
          $ID_EMP=$this->getIdEmpUserAuth();
          $result=$this->lecturasService->orderTrabajoTecnicosService($ID_EMP);
          return $result;
        } catch (\Exception $e) {
          return response()->json("Error :".$e);
        }
      }

    /**
     * servicio de proceso de catastros decobo
     */
    public function procesarCatastros(){
      try {
        $ID_EMP=$this->getIdEmpUserAuth();
        $result=$this->lecturasService->procesarCatastrosService();
        return response($result)->header('Content-Type', 'applicationIjson');
      } catch (\Exception $e) {
          return response()->json("Error :".$e);
      }

    }
/**
 *Proesar de orden temporal con nuevo archivo
 */
    public function procesarActualizarOrdenTemporal(){
      try {
        $ID_EMP=$this->getIdEmpUserAuth();
        $result=$this->lecturasService->procesarOrdenTemporalService();
        return response($result)->header('Content-Type', 'applicationIjson');
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }
    }

/**
 *gaudar historial de lecturas
 */
  public function guardarHistorial(){
    try {
      $ID_EMP=$this->getIdEmpUserAuth();
      $result=$this->lecturasService->guardarHistorialService();
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }
  }

/**
 *servicio generar orden de trabajo temporal
 */
  public function generarOrdenTrabajo(){
    try {
      $ID_EMP=$this->getIdEmpUserAuth();
      $result=$this->lecturasService->generarOrdenTempService();
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }
  }

/**
 * obtien rutas
 */
  public function obtenerRutasDecobo()
  {
    try {
      $result=$this->lecturasService->obtenerRutasDecobo();
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
        return response()->json("Error :".$e);
    }

  }

  public function obtenerRutasAsignadas(){
    try {
      $result=$this->lecturasService->obtenerRutasAsignadasTecnicos();
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }

  }

  public function asignarRutas(Request $request){
    try {
      $result=$this->lecturasService->obtenerRutasAsignadasTecnicos($request);
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }

  }

  public function deleteAsignacionLecturas(Request $request){
    try {
      $result=$this->lecturasService->deleteAsignacion($request->all());
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }
  }

  /**
   *  servicio que validar lecturas
   */
   public function validarLecturas($agencia){
     try {
       $result=$this->lecturasService->validarLecturasServices($agencia);
       return response($result)->header('Content-Type', 'applicationIjson');
     } catch (\Exception $e) {
       return response()->json("Error :".$e);
     }

   }

  /**
   * calcula consumos desde servcioo lectuyras
   */
   public function calculaConsumosService($agencia){
     try {
       $result=$this->lecturasService->calculaConsumosService($agencia);
       return response($result)->header('Content-Type', 'applicationIjson');
     } catch (\Exception $e) {
       return response()->json("Error :".$e);
     }

   }

  /**
   * valida consumos service
   */
   public function validaConsumos($agencia){
     try {
       $result= $this->lecturasService->validaConsumosService($agencia);
       return response($result)->header('Content-Type', 'applicationIjson');
     } catch (\Exception $e) {
       return response()->json("Error :".$e);
     }

   }


  public function getLecturasTrabajo(Request $request){
    try {
      $result= $this->lecturasService->getReporteLecturasService($request->all());
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }

  }

  public function reporteErroresConsumos(){
    try {
      $result= $this->lecturasService->getReporteErroresConsumoService();
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }

  }

  public function reporteErroresLecturas(){
    try {
      $result= $this->lecturasService->getReporteErroresLecturasService();
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }

  }

  public function reporteEnviosLecturas($mes){
    try {
      $result= $this->lecturasService->getReporteEnviosService($mes);
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }

  }

  public function validaLecturasMenores($agencia){
    try {
      $result= $this->lecturasService->validaLecturasMenoresServices($agencia);
      return response($result)->header('Content-Type', 'applicationIjson');
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }

  }

  /**
   * subir archivo txt de respaldo al servidor, se conecta al servicio de lecturas api
   */
   public function uploadBackupFile(Request $request){
       try {
         $file=$request->file;
         $ID_EMP=$this->getIdEmpUserAuth();
         $result=$this->lecturasService->uploadBackupFileService($file);
         return response($result)->header('Content-Type', 'applicationIjson');
       } catch (\Exception $e) {
         return response()->json("Error :".$e);
       }
   }

   /**
    * actualizar ritas distribucion
    */

    public function actualizarDistribuciones(){
      try {
        $result=$this->lecturasService->actualizarDistribucionService();
        return response($result)->header('Content-Type', 'applicationIjson');
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }

    }

    public function borrarTablaDecobo(){
      try {
        $result=$this->lecturasService->borrarTablaDecoboService();
        return response($result)->header('Content-Type', 'applicationIjson');
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }

    }
}
