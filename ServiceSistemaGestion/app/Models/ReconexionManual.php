<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ReconexionManual extends Model
{
  protected $table="tbl_recmanual";
  protected $primaryKey = 'id_rec';
  protected $fillable = [
      'id_rec','lectura','medidor','id_tecn','observacion','estado','created_at','updated_at','id_emp'
  ];
  protected $hidden = [
      'remember_token'
  ];

  public function getRecManual($fecha, $id_tecn, $ID_EMP){
    return $actividad = DB::table('tbl_recmanual as T0')
          ->join('tbl_tecnico as T1','T1.id_tecn','=','T0.id_tecn')
          ->select('T0.*','T1.nombres','T1.apellidos','T1.cedula')
          ->where(function($query) use($id_tecn){
            if($id_tecn!="empty")
              $query->where('T0.id_tecn',$id_tecn);
          })
          ->where(function($query) use($fecha){
            if($fecha!="empty")
              $query->where('T0.created_at','like','%'.$fecha.'%');
          })
          ->where('T0.id_emp',$ID_EMP)
          ->orderByRaw('T0.id_rec desc')
          ->get();
  }

}
