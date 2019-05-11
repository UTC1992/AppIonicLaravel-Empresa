<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Reportes extends Model
{


  /**
   * ejecuta procedimiento almacenado de estadisdtica
   */
    public static function getEstadisticaActividadesDiaria($empresa,$inicio,$fin)
    {
    $result=  DB::select('call sp_estadistica_diario("'.$empresa.'", "'.$inicio.'","'.$fin.'")');
    return $result;
    }

    /**
     * procedimiento estadistica por mes
     */
    public static function getEstadisticaActividadesPorMes($empresa,$mes)
    {
    $result=  DB::select('call sp_estadistica_mes("'.$empresa.'", "'.$mes.'")');
    return $result;
    }

    /**
     * procedimiento estadística de tecnicos por fecha
     */
    public static function getEstadisticaActividadesTecnicos($empresa,$inicio,$fin)
    {
    $result=  DB::select('call sp_estadistica_tecnicos("'.$empresa.'", "'.$inicio.'","'.$fin.'")');
    return $result;
    }

    /**
     * procedimiento estadistica por mes tecnicos
     */
    public static function getEstadisticaActividadesTecnicosMes($empresa,$mes)
    {
    $result=  DB::select('call sp_estadistica_tecnicos_mes("'.$empresa.'", "'.$mes.'")');
    return $result;
    }

    /**
     * procedimiento actividades tecncio
     */
    public static function getActividadByTecnico($tecnico)
    {
    $result=  DB::select('call sp_actividad_sector_tecnico("'.$tecnico.'")');
    return $result;
    }
}
