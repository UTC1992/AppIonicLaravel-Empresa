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
    'id_emp',
  ];
  protected $hidden = [
    'remember_token'
  ];

  public function getDataActividadesTecnico($id){
    return $colmena = DB::table('tbl_actividaddiaria as T0')
          ->join('tbl_ordentrabajo as T1','T1.id_act','=','T0.id_act')
          ->select('T0.id_act','T0.n9cono','T0.n9cocu','T0.n9cose','T0.n9coru','T0.n9plve','T0.n9vaca','T0.n9meco','T0.n9leco','T0.n9cocl','T0.n9nomb','T0.n9refe','T0.cusecu','T0.cucoon','T0.cucooe','T1.estado','T1.tipo_actividad','T1.observacion')
          ->where('T1.id_tecn',$id)
          ->where('T0.estado',1)
          ->where('T1.estado',0)
          ->orderByRaw('T0.id_act desc')
          ->get();
  }

  public function getViewActivities(){
    return $colmena=DB::table('Actividades_tecnico')
    ->select('*')
    ->get();
  }
}
