<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
