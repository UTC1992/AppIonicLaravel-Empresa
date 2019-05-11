<?php
namespace App\Http\Controllers\Angular;

use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Historial;
use App\Models\OrdenTemp;
use App\Models\OrdenTrabajo;
use App\Models\ActividadDiaria;
use App\Models\ReconexionManual;
use Illuminate\Support\Facades\Hash;

class TecnicoController extends Controller
{
  public function __construct(){
    $this->middleware('auth:api');
  }
    /**
     * obtiene técnicos de cortes
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      try {
        $tecnico=new Tecnico();
        $result= $tecnico->where('estado',1)->where('borrado',0)->where('id_emp',$this->getIdEmpUserAuth())->get();
        return response()->json($result);
      } catch (\Exception $e) {
          return response()->json($e);
      }
    }

    /**
     * obtener tecnicos de cortes
     */
     public function getTecnicosCortes(){
       try {
         $tecnico=new Tecnico();
         $result= $tecnico->where('estado',1)->where('borrado',0)->where('id_emp',$this->getIdEmpUserAuth())->where('actividad','cortes')->get();
         return response()->json($result);
       } catch (\Exception $e) {
           return response()->json($e);
       }
     }

     /**
      * obtener tecnicos de lecturas
      */
      public function  getTecnicosLecturas(){
        try {
          $tecnico=new Tecnico();
          $result= $tecnico->where('estado',1)->where('borrado',0)->where('id_emp',$this->getIdEmpUserAuth())->where('actividad','lecturas')->get();
          return response()->json($result);
        } catch (\Exception $e) {
            return response()->json($e);
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {
        $exist_cedula= Tecnico::where('id_emp', $this->getIdEmpUserAuth())->where('cedula',$request->cedula)->exists();
        if($exist_cedula){
          return response()->json(0);
        }
        $tecnico=new Tecnico();
        $tecnico->nombres=$request->nombres;
        $tecnico->apellidos=$request->apellidos;
        $tecnico->cedula=$request->cedula;
        $tecnico->telefono=$request->telefono;
        $tecnico->email=$request->email;
        $tecnico->estado=$request->estado;
        $tecnico->actividad=$request->actividad;
        $tecnico->asignado=0;
        $tecnico->borrado=0;
        $tecnico->password='123456';
        $tecnico->id_emp=$this->getIdEmpUserAuth();
        $tecnico->save();
        if($tecnico){
          $this->createHistoryUser("Crear","Creacion de etecnico correcto","Tecnicos");
          return response()->json(true);
        }else{
          $this->createHistoryUser("Error","Creacion de etecnico fallido","Tecnicos");
          return response()->json(false);
        }
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }



    }


    //tecnicos sin asignar actividades
    public function getTecnicosSinActividades(){
      $tecnico=new Tecnico();
      $result=$tecnico->where('asignado',0)->where('estado',1)->where('borrado',0)->where('id_emp',$this->getIdEmpUserAuth())->where('actividad','cortes')->get();
      return response()->json($result);
    }



    //tecnicos sin asignar actividades de lecturas
    public function getTecnicosSinActividadesLecturas(){
      $tecnico=new Tecnico();
      $result=$tecnico->where('asignado',0)->where('estado',1)->where('borrado',0)->where('id_emp',$this->getIdEmpUserAuth())->where('actividad','lecturas')->get();
      return response()->json($result);
    }

    /**
     * borrar técnico
     */
    public function delete($id_tecnico){
      $tecnico=new Tecnico();
      $result=$tecnico->where('id_tecn',$id_tecnico)->where('id_emp',$this->getIdEmpUserAuth())->first();
      $result->borrado = 1;
      $result->estado = 0;
      $result->save();

      if($result){
        $this->createHistoryUser("Eliminar","Elimina Tecnico","Tecnicos");
        return response()->json(true);
      }else{
        $this->createHistoryUser("Error","Elimina Tecnico fallido","Tecnicos");
          return response()->json(false);
      }
    }
    /**
     *
     */
    public function editTecnicoAngular(Request $request){
      $tecnico=Tecnico::find($request->id_tecn);
      $tecnico->nombres=$request->nombres;
      $tecnico->apellidos=$request->apellidos;
      $tecnico->cedula=$request->cedula;
      $tecnico->telefono=$request->telefono;
      $tecnico->email=$request->email;
      $tecnico->estado=$request->estado;
      $tecnico->actividad=$request->actividad;
      $tecnico->asignado=0;
      $tecnico->borrado=0;
      $pass="123456";
      $tecnico->password=$pass;
      $tecnico->id_emp=$this->getIdEmpUserAuth();
      $result = $tecnico->save();
      $this->createHistoryUser("Editar","Edita Tecnico","Tecnicos");
      return response()->json($result);
    }

    public function getTecnicoById($id_tecnico){
      $tecnico=Tecnico::find($id_tecnico);
      return response()->json($tecnico);
    }

/**
 *
 */
    public function buildTaskTecnicos(Request $request){
      try {
        if($request->cantidad_actividades<=0 || $request->cantidad_actividades==null){
          return response()->json(1);
        }
        $cont=0;
        $contador=0;
        $array_actividades=$request->array_actividades;
        $array_tecnicos=$request->array_tecnicos;
        $cantidad_distribuir=ceil($request->cantidad_actividades/count($array_tecnicos));
        for ($i=0; $i <$request->cantidad_actividades; $i++) {
          //ingresa  orden trabajo
          $ordenTrabajo=new OrdenTrabajo();
          $ordenTrabajo->id_tecn=$array_tecnicos[$cont];
          $ordenTrabajo->id_act=$array_actividades[$i];
          $ordenTrabajo->estado=0;
          $ordenTrabajo->fecha=date('Y-m-d');
          $ordenTrabajo->observacion="";
          $ordenTrabajo->tipo_actividad=$request->actividad;
          $ordenTrabajo->save();
          // actualiza actividad diaria asignado
          $ordenProc=ActividadDiaria::find($array_actividades[$i]);
          $ordenProc->estado=1;
          $ordenProc->referencia="Asignado";
          $ordenProc->save();
          $contador++;
          if($contador==$cantidad_distribuir){
            $cont++;
            $contador=0;
          }
        }
        // asigna técnico
        for ($j=0; $j < count($array_tecnicos); $j++) {
          $tecnico=Tecnico::find($array_tecnicos[$j]);
          $tecnico->asignado=1;
          $tecnico->save();
        }

        $this->createHistoryUser("Distribucion","Distribucion de actividades a Tecnico","Tecnicos");
        return response()->json(true);

      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }

    }


    // asignar nueva tarea Tecnico
    public function changeStateTecnico($id){
      try {
        $tecnico=Tecnico::find($id);
        $tecnico->asignado=0;
        $tecnico->save();
        if($tecnico){
          $this->createHistoryUser("Cambiar Estado","Cambia estado de Tecnico","Tecnicos");
          return response()->json(true);
        }
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }

    }

  // obtener reconexiones manuales

    public function getReconexionesManualesTecnico(Request $request){
      try {
          $id_tecn=$request->id_tecn;
          $fecha=$request->fecha;
          $recManuales=new ReconexionManual();
          $result=$recManuales->where(function($query) use($id_tecn){
            if($id_tecn!="empty")
              $query->where('id_tecn',$id_tecn);
          })->where(function($query) use($fecha){
            if($fecha!="empty")
              $query->where('created_at','like','%'.$fecha.'%');
          })->where('id_emp',$this->getIdEmpUserAuth())->get();
          if(count($result)>0){
            return response()->json($result);
          }else{
            return response()->json(false);
          }
      } catch (\Exception $e) {
        return response()->json("Error:_ ".$e);
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
