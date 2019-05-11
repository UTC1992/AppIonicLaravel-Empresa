<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
  protected $table="tbl_observaciones";
  protected $primaryKey = 'id_obs';
  protected $fillable = [
    'id_obs',
    'id_emp',
    'descripcion',
    'tipo',
    'codigo',
    'created_at'
  ];
}
