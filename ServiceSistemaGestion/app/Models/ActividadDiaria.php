<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class ActividadDiaria extends Model
{
  protected $table="tbl_actividaddiaria";
  protected $primaryKey = 'id_act';
  protected $fillable = [
    'id_act',
    'n9sepr',
    'n9cono',
    'n9cocu',
    'n9selo',
    'n9cozo',
    'n9coag',
    'n9cose',
    'n9coru',
    'n9seru',
    'n9vano',
    'n9plve',
    'n9vaca',
    'n9esta',
    'n9cocn',
    'n9fech',
    'n9meco',
    'n9seri',
    'n9feco',
    'n9leco',
    'n9manp',
    'n9cocl',
    'n9nomb',
    'n9cedu',
    'n9prin',
    'n9nrpr',
    'n9refe',
    'n9tele',
    'n9medi',
    'n9fecl',
    'n9lect',
    'n9cobs',
    'n9cob2',
    'n9ckd1',
    'n9ckd2',
    'cusecu',
    'cupost',
    'cucoon',
    'cucooe',
    'cuclas',
    'cuesta',
    'cutari',
    'created_at',
    'updated_at',
    'referencia',
    'estado',
    'latitud',
    'longitud',
    'id_emp',
  ];
  protected $hidden = [
    'remember_token'
  ];

  public function getDataActividadesTecnico($id,$ID_EMP){
    return $actividad = DB::table('tbl_actividaddiaria as T0')
          ->join('tbl_ordentrabajo as T1','T1.id_act','=','T0.id_act')
          ->select('T0.latitud','T0.longitud','T1.fecha','T1.id_tecn','T0.id_act','T0.n9cono','T0.n9cocu','T0.n9cose','T0.n9coru','T0.n9plve','T0.n9vaca','T0.n9meco','T0.n9leco','T0.n9cocl','T0.n9nomb','T0.n9refe','T0.cusecu','T0.cucoon','T0.cucooe','T1.estado','T1.tipo_actividad','T1.observacion')
          ->where('T1.id_tecn',$id)
          ->where('T0.estado',1)
          ->where('T1.estado',0)
          ->where('T0.id_emp',$ID_EMP)
          ->orderByRaw('T0.id_act desc')
          ->get();
  }

  public function getDataActividadesTecnicoDetalle($id, $tipo,$ID_EMP){
    return $actividad = DB::table('tbl_actividaddiaria as T0')
          ->join('tbl_ordentrabajo as T1','T1.id_act','=','T0.id_act')
          ->select('T0.n9cono','T0.latitud','T0.longitud','T1.fecha','T1.id_tecn','T0.id_act','T0.n9cono','T0.n9cocu','T0.n9cose','T0.n9coru','T0.n9plve','T0.n9vaca','T0.n9meco','T0.n9leco','T0.n9cocl','T0.n9nomb','T0.n9refe','T0.cusecu','T0.cucoon','T0.cucooe','T1.estado','T1.tipo_actividad','T1.observacion')
          ->where('T1.id_tecn',$id)
          ->where('T0.estado',1)
          ->where('T1.estado',0)
          ->where('T0.n9cono',$tipo)
          ->where('T0.id_emp',$ID_EMP)
          ->orderByRaw('T0.id_act desc')
          ->get();
  }

  public function getViewActivities(){
    return $actividad=DB::table('Actividades_tecnico')
    ->select('*')
    ->get();
  }

  public function getAllActivitiesFilter($fecha,$id_tecnico,$actividad,$estado,$ID_EMP){
    return $actividad2 = DB::table('tbl_actividaddiaria as T0')
          ->leftJoin('tbl_ordentrabajo as T1','T1.id_act','=','T0.id_act')
          ->select('T0.*','T1.observacion as observacionFin')
          ->where('T0.created_at','like','%'.$fecha.'%')
          ->where(function($query) use($id_tecnico){
            if($id_tecnico!="empty")
            $query->where('T1.id_tecn',$id_tecnico);
          })
          ->where(function($query) use($actividad){
            if($actividad!="empty")
            $query->where('T0.n9cono','like','%'.$actividad.'%');
          })
          ->where(function($query) use($estado){
            if($estado!="empty")
            $query->where('T0.estado',$estado);
          })
          ->where('T0.id_emp',$ID_EMP)
          ->orderByRaw('T0.id_act desc')
          ->get();
  }
  //obtener cantores actividades por tipo
  public function getCantonstByActivityType($tipo_actividad,$ID_EMP){
    return $actividad = DB::table('tbl_actividaddiaria as T0')
          ->leftJoin('tbl_ordentrabajo as T1','T1.id_act','=','T0.id_act')
          ->select('T0.n9coag as canton')
          ->where('T0.estado',0)
          ->where('T0.id_emp',$ID_EMP)
          ->where('T0.n9cono','like','%'.$tipo_actividad.'%')
          ->groupBy('T0.n9coag')
          ->get();
  }
  // obtener actividades
  public function getSectorsByActivities($tipo_actividad,$canton,$ID_EMP){
    return $actividad = DB::table('tbl_actividaddiaria as T0')
          ->select('T0.n9cose as sector')
          ->where('T0.estado',0)
          ->where('T0.id_emp',$ID_EMP)
          ->where('T0.n9cono','like','%'.$tipo_actividad.'%')
          ->where('T0.n9coag',$canton)
          ->groupBy('T0.n9cose')
          ->get();
  }

  // function actividades by sectores
  public function getActivitiesBySectors($tipo_actividad,$canton,$sector,$ID_EMP){
    return $actividad = DB::table('tbl_actividaddiaria as T0')
          ->select('T0.*')
          ->where('T0.estado',0)
          ->where('T0.id_emp',$ID_EMP)
          ->where('T0.n9cono','like','%'.$tipo_actividad.'%')
          ->where('T0.n9coag',$canton)
          ->where('T0.n9cose',$sector)
          ->get();
  }

  // function actividades by sectores
  public function getActivitiesBySectorsPost($tipo_actividad,$canton,$sector,$ID_EMP){
    return $actividad = DB::table('tbl_actividaddiaria as T0')
          ->select('T0.*')
          ->where('T0.estado',0)
          ->where('T0.id_emp',$ID_EMP)
          ->where('T0.n9cono','like','%'.$tipo_actividad.'%')
          ->where('T0.n9coag',$canton)
          ->whereIn('T0.n9cose',$sector)
          ->get();
  }


}
