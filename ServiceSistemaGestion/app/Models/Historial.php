<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
  protected $table="event_history";
  protected $primaryKey = 'idevent_history';
  protected $fillable = [
      'idevent_history',
      'accion',
      'observacion',
      'usuario',
      'created_at',
      'modulo',
      'empresa'
  ];
}
