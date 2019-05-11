<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use DB;

class OrdenTrabajo extends Model
{

    protected $table="orden_trabajo";
    protected $primaryKey = 'idorden_trabajo';
    protected $fillable = [
        'estado','created_at','updated_at','id_lectura','id_tecnico','id_empresa'
    ];

    protected $hidden = [
        'idorden_trabajo'
    ];

/**
 * llamada procedimiento almacenado empresa
 */
public static function getOrdenTrabajoAsignado($tabla){
  return $result=  DB::select('call ps_tecnicos_asignacion_lecturas("'.$tabla.'")');
}

/**
 *
 */
public static function getLecturasConsolidadas($tabla,$mes){
  return $result=  DB::select('call ps_consolidado_lecturas("'.$tabla.'","'.$mes.'")');
}

}
