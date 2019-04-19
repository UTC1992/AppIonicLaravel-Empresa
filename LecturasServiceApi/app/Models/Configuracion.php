<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use DB;

class Configuracion extends Model
{

    protected $table="configuraciones";
    protected $primaryKey = 'id';
    protected $fillable = [
        'id','key','value','estado','idEmpresa','filter','order'
    ];

    protected $hidden = [

    ];

    public function createTableLecturas($data){
      $tabla="";
      foreach ($data as $key => $value) {
        if($value->key=="table"){
          $tabla=$value->value;
        }
      }
      Schema::create($tabla, function (Blueprint $table) use($data){
        $table->increments('id');
        foreach ($data as $key => $value) {
          if($value->key=="column"){
            $table->string($value->value)->nullable();;
          }
        }
        $table->string("longitud")->nullable();;
        $table->string("latitud")->nullable();
        $table->string("lectura_actual")->nullable();
        $table->integer("idEmpresa")->nullable();;
        $table->boolean("estado")->nullable();;
        $table->timestamps();
    });
    }

    public function existTable($tabla){
      $res = Schema::hasTable($tabla);
      return $res;
    }

    public function dropTable($tabla){
      $res=Schema::dropIfExists($tabla);
      return $res;
    }
}
