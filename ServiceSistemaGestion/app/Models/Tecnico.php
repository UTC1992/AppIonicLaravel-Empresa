<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
  protected $table="tbl_tecnico";
  protected $primaryKey = 'id_tecn';
  protected $fillable = [
    'id_tecn',
    'nombres',
    'apellidos',
    'codigo',
    'cedula',
    'telefono',
    'email',
    'estado',
    'password'
  ];
  protected $hidden = [
    'remember_token'
  ];
}
