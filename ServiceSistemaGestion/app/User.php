<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

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
            ->select('T0.name','T0.email as username', 'T0.id as idUser','T1.nombre as empresa','T1.id_emp')
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

    public function getUsers()
    {
        //return $user = User::all();
        return $sub = DB::select('select T0.id_emp as idEmp, T0.nombre as nombreEmp, T1.name as name, T1.email as email, T1.id as id from tbl_empresa as T0, users as T1 where T0.id_emp=T1.id_emp and T1.borrado=0', []);
    }

    public function getUsersByEmpresa($id_emp = 0)
    {
        //return $user = User::all();
        return $users = DB::select('SELECT * FROM users WHERE id_emp=:idEmpresa', ['idEmpresa' => $id_emp]);
    }

    public function getById($id='')
    {
        //return User::find($id);
        return $sub = DB::select('select T0.id_emp as idEmp, T0.nombre as nombreEmp, T1.name as name, T1.email as email, T1.id as id from tbl_empresa as T0, users as T1 where T0.id_emp=T1.id_emp and T1.id=:id', ['id' => $id]);
    }

    public function updateUser($data=[])
    {
        try {
            $user = User::find($data->id);
            $user->name = $data->name;
            $user->email = $data->email;
            $user->id_emp = $data->id_emp;
            $user->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function updatePass($data=[])
    {
        try {
            $user = User::find($data->id);
            $user->password = Hash::make($data->password);
            $user->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteById($id=0)
    {
        try {
            $user = User::find($id);
            $user->borrado = 1;
            return $user->save();
        } catch (\Exception $e) {
            return $e;
        }
    }
}
