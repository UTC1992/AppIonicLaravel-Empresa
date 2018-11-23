<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrdenTrabajo extends Model
{
  protected $table="tbl_ordentrabajo";
  protected $primaryKey = 'id_ord';
  protected $fillable = [
      'id_ord','id_tecn','estado','fecha','observacion','created_at','updated_at'
  ];
  protected $hidden = [
      'remember_token'
  ];

  public function getDataDistribucion(){
    return $distribucion = DB::select("select `t0`.`id_tecn` AS `id_tecn`,`t2`.`n9cose` AS `n9cose`,`t0`.`nombres` AS `nombres`,`t0`.`apellidos` AS `apellidos`,count(`t1`.`id_act`) AS `cantidad`,if((`t1`.`tipo_actividad` = 10),'Notificaciones',if((`t1`.`tipo_actividad` = 30),'Corte',if((`t1`.`tipo_actividad` = 40),'Reconexiones',NULL))) AS `tipo` from ((`empresa_db2`.`tbl_tecnico` `t0` join `empresa_db2`.`tbl_ordentrabajo` `t1` on((`t0`.`id_tecn` = `t1`.`id_tecn`))) join `empresa_db2`.`tbl_actividaddiaria` `t2` on((`t2`.`id_act` = `t1`.`id_act`))) where (`t1`.`estado` = 0) group by `t0`.`id_tecn`,`t1`.`tipo_actividad`,`t2`.`n9cose`"
										, []);
  }
}

/*select `t0`.`id_tecn` AS `id_tecn`,`t2`.`n9cose` AS `n9cose`,`t0`.`nombres` AS `nombres`,`t0`.`apellidos` AS `apellidos`,count(`t1`.`id_act`) AS `cantidad`,if((`t1`.`tipo_actividad` = 10),'Notificaciones',if((`t1`.`tipo_actividad` = 30),'Corte',if((`t1`.`tipo_actividad` = 40),'Reconexiones',NULL))) AS `tipo` from ((`empresa_db2`.`tbl_tecnico` `t0` join `empresa_db2`.`tbl_ordentrabajo` `t1` on((`t0`.`id_tecn` = `t1`.`id_tecn`))) join `empresa_db2`.`tbl_actividaddiaria` `t2` on((`t2`.`id_act` = `t1`.`id_act`))) where (`t1`.`estado` = 0) group by `t0`.`id_tecn`,`t1`.`tipo_actividad`,`t2`.`n9cose`
*/