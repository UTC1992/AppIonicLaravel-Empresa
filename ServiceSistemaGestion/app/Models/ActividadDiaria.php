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
}
