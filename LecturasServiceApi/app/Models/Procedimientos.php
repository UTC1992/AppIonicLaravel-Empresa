<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use DB;

class Procedimientos extends Model
{

    protected $table="orden_trabajo";
    protected $primaryKey = 'idorden_trabajo';
    protected $fillable = [
        'estado','created_at','updated_at','id_lectura','id_tecnico','id_empresa','observacion','fecha_lectura','hora',
        'lon','lat','foto'
    ];

    protected $hidden = [
        'idorden_trabajo'
    ];

/**
 * llamada procedimiento almacenado empresa
 */
public static function procesarOrdenTemp(){
  return $result=  DB::select('call sp_test_update()');
}

/**
 * guardar historial de rutas
 */
public static function guardarHistorialDecobo($tabla){
  return $result=  DB::select('call ps_guardar_historial_decobo("'.$tabla.'")');
}

/**
 * generar orden temporal
 */
public static function generarOrdenTempDecobo($mes){
  return $result=  DB::select('call ps_generar_orden_temp("'.$mes.'")');
}

}
