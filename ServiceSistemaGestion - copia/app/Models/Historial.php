<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Historial extends Model
{
  protected $table="event_history";
  protected $primaryKey = 'idevent_history';
  protected $fillable = [
      'idevent_history',
      'accion',
      'observacion',
      'usuario',
      'created_at',
      'modulo',
      'empresa'
  ];

  public function getHistoriales()
  {
    try {
      return $actividad = DB::table('event_history as T0')
            ->join('users as T1','T1.id','=','T0.usuario')
            ->select('T0.*','T1.name as nombre')
            ->get();
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function getByEmpresa($data = [])
  {
    try {
      return $actividad = DB::table('event_history as T0')
            ->join('users as T1','T1.id','=','T0.usuario')
            ->select('T0.*','T1.name as nombre')
            ->where('T0.empresa',$data->id_emp)
            ->where('T0.created_at','like','%'.$data->fecha_actividad.'%')
            ->get();
    } catch (\Exception $e) {
      return $e;
    }
  }
}
