<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = "tbl_admins";

    protected  $guard = "admin";

    protected $primaryKey = "id_admin";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombres', 'apellidos', 'cedula', 'telefono', 'direccion', 'estado', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
      $this->notify(new AdminResetPasswordNotification($token));
    }

    /**
    * obtener y retornar un admin mediante el id
    */
    public function getById($id='')
    {
      return Admin::find($id);
    }

    /**
    * actualizar los datos informativos del admin
    */
    public function actualizar($data = [])
    {
      try {
        $admin = Admin::find($data->id_admin);
        $admin->nombres = $data->nombres;
        $admin->apellidos = $data->apellidos;
        $admin->cedula = $data->cedula;
        $admin->telefono = $data->telefono;
        $admin->direccion = $data->direccion;
        $admin->estado = $data->estado;
        $admin->email = $data->email;
        $admin->save();

        return true;

      } catch (\Exception $e) {

        return false;

      }

    }

    public function verificarPass($passwordAntigua = "", $passwordIngresadaAVerificar = "")
    {
      try {
        if(Hash::check($passwordIngresadaAVerificar, $passwordAntigua))
        {
          return true;
        }
        else {
          return false;
        }
      } catch (\Exception $e) {

        return false;

      }

    }

    public function changePassword($id_admin, $password)
    {
      try {
        $admin = Admin::find($id_admin);
        $admin->password = Hash::make($password);
        $admin->save();
        return true;
      } catch (\Exception $e) {

        return false;

      }
      
    }

}
