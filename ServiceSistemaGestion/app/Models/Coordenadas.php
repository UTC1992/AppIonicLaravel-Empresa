<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordenadas extends Model
{
  protected $table="Coordenadas";
  protected $primaryKey = 'idCoordenadas';
  protected $fillable = [
      'id_emp',
      'cuenta',
      'medidor',
      'latitud',
      'longitud'
  ];

  protected $hidden = [
      'remember_token'
  ];


}
