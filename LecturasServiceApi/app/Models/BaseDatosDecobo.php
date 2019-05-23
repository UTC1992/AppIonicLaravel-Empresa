<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use DB;

class BaseDatosDecobo extends Model
{

    protected $table="decobo";
    protected $primaryKey = 'id';
    protected $fillable = [
        'id','zona','agencia','sector','ruta','cuenta','medidor','campo_a','campo_n','campo_0',
        'secuencia','columna2','fechaultimalec','lectura','equipo','lector','esferas','tarifa','nombre',
        'direccion','campo10','columna4','este','norte','estado','longitud','latitud','idEmpresa','created_at','updated_at',
        'nueva_lectura'
    ];

    protected $hidden = [

    ];


}
