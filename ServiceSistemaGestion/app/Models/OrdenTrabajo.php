<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
