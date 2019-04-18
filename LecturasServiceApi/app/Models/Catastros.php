<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use DB;

class Catastros extends Model
{

    protected $table="catastros";
    protected $primaryKey = 'idcatastro';
    protected $fillable = [
        'estado','fecha','idEmpresa','medidor','observacion','lectura','latitud','longitud','id_tecnico'
    ];

    protected $hidden = [
        'idcatastro'
    ];



}
