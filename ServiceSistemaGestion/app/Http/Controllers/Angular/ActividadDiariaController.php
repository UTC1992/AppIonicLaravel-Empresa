<?php
namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadDiaria;
use Illuminate\Http\JsonResponse;
use App\Models\Tecnico;
use App\Models\Historial;
use App\Models\ReconexionManual;
use App\Models\OrdenTrabajo;

class ActividadDiariaController extends Controller
{



  public function __construct(){
    $this->middleware('auth:api');
  }
    public function getActivitadesTecnico(){
      try {
        $actividades=new ActividadDiaria();
        $result=$actividades->getViewActivities();
        return response()->json($result);
      } catch (\Exception $e) {

      }
    }


    public function getActivitiesTecnico($id_tecnico, $tipo,$sector){
      $orden=new ActividadDiaria();
      $result=$orden->getDataActividadesTecnicoDetalle($id_tecnico, $tipo,$sector,$this->getIdEmpUserAuth());
      return response()->json($result);
    }
    //validar y completar las actividades del tecnico
    public function validateActivitiesByTecnico($id_tecnico){
      try {
        if(!is_null($id_tecnico)){
          $tecnico=Tecnico::find($id_tecnico);
          $tecnico->asignado=0;
          $tecnico->save();
          if($tecnico){
            return response()->json(true);
          }else{
            return response()->json(false);
          }
        }
      } catch (\Exception $e) {
        return response()->json("Erro: ".$e);
      }
    }

    public function getActivitiesToDay($fecha,$id_tecnico,$actividad,$estado){
      try {
        $actividades=new ActividadDiaria();
        $result=$actividades->getAllActivitiesFilter($fecha,$id_tecnico,$actividad,$estado,$this->getIdEmpUserAuth());
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }
    //obtener cantones de distribucion
    function getCantonesActividades($type){
      try {
        $actividades=new ActividadDiaria();
        $result=$actividades->getCantonstByActivityType($type,$this->getIdEmpUserAuth());
        if($result){
          return response()->json($result);
        }else{
          return response()->json(false);
        }
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }
    // obtener sectores de canton
    public function getSectores($tipo_actividad,$canton){
      try {
        $actividad=new ActividadDiaria();
        $result=$actividad->getSectorsByActivities($tipo_actividad,$canton,$this->getIdEmpUserAuth());
        if($result){
          return response()->json($result);
        }else{
          return response()->json(false);
        }

      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }

    }
    // obtener cantidad de Actividades_tecnico
    public function getActivitiesBySectors($tipo_actividad,$canton,$sector){
      try{
          $actividad=new ActividadDiaria();
          $result=$actividad->getActivitiesBySectors($tipo_actividad,$canton,$sector,$this->getIdEmpUserAuth());
          if($result){
            return response()->json($result);
          }else{
            return response()->json(false);
          }
      }catch (\Exception $e){
        return response()->json("Error: ".$e);
      }
    }

     // obtener cantidad de Actividades_tecnico post
    public function getActivitiesBySectorsPost(Request $request){
      try{
          $actividad=new ActividadDiaria();
          $result=$actividad->getActivitiesBySectorsPost($request->actividad,$request->canton,$request->sector,$this->getIdEmpUserAuth());
          if($result){
            return response()->json($result);
          }else{
            return response()->json(false);
          }
      }catch (\Exception $e){
        return response()->json("Error: ".$e);
      }
    }

    //actualizar actividades manuales
    public function validarActividadesManuales(){
      try {

        $actividad=new ActividadDiaria();
        $ID_EMP=$this->getIdEmpUserAuth();
        $result=$actividad->where('estado',0)->where('id_emp',$ID_EMP)->where('n9cono','=','040')->get();
        $cont=0;
        foreach ($result as $key => $value) {
            
          $result_rec=new ReconexionManual();
          $reconexiones= $result_rec->where('id_emp',$ID_EMP)->where('estado',0)->where('observacion','Sin novedad')->where('medidor',''.$value["n9meco"].'')->first();
            
          if(!is_null($reconexiones)){

              $act=ActividadDiaria::find($value->id_act);
              $act->n9leco=$reconexiones->lectura;
              $act->n9lect=$reconexiones->lectura;
              $act->n9feco=date('Y')."".date('m')."".date('d');
              $act->n9fecl=date('Y')."".date('m')."".date('d');
              $act->estado=2;
              $act->referencia='Finalizado';
              $act->save();
            // insertar registro orden trabajo tecnico
              $ordenTrabajo=new OrdenTrabajo();
              $ordenTrabajo->id_tecn=$reconexiones->id_tecn;
              $ordenTrabajo->id_act=$value["id_act"];
              $ordenTrabajo->estado=1;
              $ordenTrabajo->fecha=date('Y-m-d');
              $ordenTrabajo->observacion=$reconexiones->observacion;
              $ordenTrabajo->tipo_actividad="40";
              $ordenTrabajo->save();
              ReconexionManual::where('id_rec',$reconexiones->id_rec)->update(['estado'=>1]);
            }
          }
         // actualiza estado rec manuales
        ReconexionManual::where('estado',0)->update(['estado'=>1]);
       $this->createHistoryUser("Actividades Manuales","Valida Actividades manuales","Cortes");
        return response()->json(true);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }
//obtiene reconexiones manuales sin procesar
    public function getRecManualesSinProcesar(){
      try {
          $res=new ReconexionManual();
          $result=$res->where('estado',0)->where('id_emp',$this->getIdEmpUserAuth())->get();
          $con=count($result);
          return response()->json($con);
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }
    }

  // consolidar actividades diarias
  public function consolidarActividadesDiarias($date){
    try {
      $actividad_asignada=new ActividadDiaria();
      $res=$actividad_asignada->where('estado',1)->where('created_at','like','%'.$date.'%')->where('id_emp',$this->getIdEmpUserAuth())->exists();
      if($res){
        return response()->json(false);
      }else{
        $actividad= new ActividadDiaria();
        $result=$actividad->where('created_at','like','%'.$date.'%')->where('id_emp',$this->getIdEmpUserAuth())->get();
        foreach ($result as $key => $value) {
		        $act1=ActividadDiaria::find($value->id_act);
          	$act1->consolidado=1;
          	$act1->save();
          if($value->estado==0){
            $act=ActividadDiaria::find($value->id_act);
            $act->estado=3;
            $act->referencia="Consolidado sin realizar";
            $act->save();
          }
        }
        $this->createHistoryUser("Consolidar","Consolidar actividades diarias","Cortes");
        return response()->json(true);
      }

    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }

  }

  // obtdener actividades consolidadas
  public function getActividadesByDate($date){
    try {
      $actividad=new ActividadDiaria();
      $result=$actividad->where('created_at','like','%'.$date.'%')->where('estado','!=',0)->where('id_emp',$this->getIdEmpUserAuth())->get();
      return response()->json($result);
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }

  public function getDistribucion(){
    try {
      $ID_EMP=$this->getIdEmpUserAuth();
      $orden=new OrdenTrabajo();
      $result=$orden->getDataDistribucion($ID_EMP);
      return response()->json($result);
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
      return response()->json();
    }

  }

  // eliminar actividades
  public function eliminarActividades(Request $request){
    try {
      $fecha=$request->fecha;
        $ID_EMP=$this->getIdEmpUserAuth();
        $actividad=new ActividadDiaria();
        $cont=$actividad->contarTecnicosAsignados($ID_EMP,$fecha);
        if($cont>0){
          $result=$actividad->obtenerTecnicosAsignadosActividad($ID_EMP,$fecha);
          foreach ($result as $key => $value) {
              $tecnico=Tecnico::find($value->id_tecn);
              $tecnico->asignado=0;
              $tecnico->save();
          }

        }

        $actividad_diaria=new ActividadDiaria();
        $res=$actividad_diaria->where('created_at','like','%'.$fecha.'%')->where('id_emp',$ID_EMP)->where('consolidado','!=1')->delete();
        if($res>0){
            $this->createHistoryUser("Eliminar Ruta","Se elimina actividades de la fecha: ".$fecha." e ID_EMP: ".$ID_EMP."","Cortes");
          return response()->json(true);
        }
        $this->createHistoryUser("Eliminar Ruta","Eliminación fallida NO data","Cortes");
        return response()->json(false);
    } catch (\Exception $e) {
        return response()->json("Error: ".$e);
    }
  }

  /**
   * guardar historial de actividades de usuario autenticado
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
