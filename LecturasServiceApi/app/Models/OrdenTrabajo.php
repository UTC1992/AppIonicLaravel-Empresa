<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use DB;

class OrdenTrabajo extends Model
{

    protected $table="orden_trabajo";
    protected $primaryKey = 'id';
    protected $fillable = [
        'estado','created_at','updated_at','id_lectura','id_tecnico','id_empresa'
    ];

    protected $hidden = [
        'idorden_trabajo',
    ];


}
