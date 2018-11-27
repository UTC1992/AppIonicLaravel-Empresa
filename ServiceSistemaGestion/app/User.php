<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;
    //HasApiTokens
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','id_emp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','id'
    ];

    public function getUserInfo($idUsuario){
      return $actividad = DB::table('users as T0')
            ->join('tbl_empresa as T1','T1.id_emp','=','T0.id_emp')
            ->select('T0.name','T0.email as username','T1.nombre as empresa')
            ->where('T0.id',$idUsuario)
            ->first();
    }
    public function validaEmpresaActiva($id_emp){
      return $actividad = DB::table('tbl_empresa as T0')
            ->select('T0.estado')
            ->where('T0.id_emp',$id_emp)
            ->where('T0.estado',1)
            ->exists();
    }
}
