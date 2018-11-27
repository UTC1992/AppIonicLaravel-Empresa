<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
  protected $table="tbl_empresa";
  protected $primaryKey = 'id_emp';
  protected $fillable = [
      'nombre','estado'
  ];
  protected $hidden = [
      'remember_token'
  ];
}
