<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class OrdenTemp extends Model
{
  protected $table="tbl_ordentemp";
  protected $primaryKey = 'id_ode';
  protected $fillable = [
      'id_ode','n9cono','n9cocu','n9cose','n9coru','n9plve','n9vaca','n9meco','n9leco','n9cocl','n9nomb','n9refe','cusecu','cucoon','foto','observacion','fecha','hora','id_tecn'
  ];
  protected $hidden = [
      'remember_token'
  ];
}
