<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Admin;
use Auth;

class AdminController extends Controller
{
    protected $admin;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->admin = new Admin;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => 'required|string|max:10',
            'telefono' => 'required|string|max:10',
            'direccion' => 'required|string|max:255',
            'estado' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.inicio');
    }

    /**
    * mostrar perfil del usuario para que pueda editarlo
    */
    public function showPerfil()
    {
        $usuario = $this->admin->getById(Auth::user()->id_admin);
        return View('admin.perfil', compact('usuario'));
    }

    /**
    * actualizar perfil del usuario administrador
    */
    public function updatePerfil(Request $request)
    {
      $actualizar = $this->admin->actualizar($request);
      if ($actualizar) {
        $mensaje = "true";
        return redirect()->action('AdminController@showPerfil', compact('mensaje'));
      }
      else {
        $mensaje = "false";
        return redirect()->action('AdminController@showPerfil', compact('mensaje'));
      }

    }

    /**
    *actualizar el password
    */
    public function updatePassword(Request $request)
    {
      //se busca al usuario con el id enviado
      $usuario = $this->admin->getById($request->id_admin);

      //se envia a verificar el password del usuario obtenido con el ID
      //y el password ingresado a verificar
      if ($this->admin->verificarPass($usuario->password, $request->passwordanterior)) {
        if($request->password == $request->password_confirmation){
          if( $this->admin->changePassword($request->id_admin, $request->password)){
            $mensaje = "Correcto se dito el password";
            return redirect()->action('AdminController@showPerfil', compact('mensaje'));  
          }
        } else {
          $mensaje = "Los password no son identicos";
          return redirect()->action('AdminController@showPerfil', compact('mensaje'));  
        }
        
        return redirect()->action('AdminController@showPerfil', null); 
        
      }
      else {
        $mensaje = "No se puedo editar el password";
        return redirect()->action('AdminController@showPerfil', compact('mensaje'));
      }
    }

}
