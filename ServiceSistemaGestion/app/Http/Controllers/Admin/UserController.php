<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\Models\Empresa;
use Auth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected function validator(array $data)
    {
        
    }

    public function index()
    {
        $objeto = new User();
        $users = $objeto->getUsers();

        $obj2 = new Empresa();
        $empresas = $obj2->getEmpresasByAdmin(Auth::user()->id_admin);

        return view('user.inicio',compact('users', 'empresas'));
        //return response()->json($empresas);
    } 



    public function edit($id = 0)
    {
        $user = new User();
        $res = $user->getById($id);
        $userEdit = $res[0];
        //return response()->json($userEdit);
        $obj2 = new Empresa();
        $empresas = $obj2->getEmpresasByAdmin(Auth::user()->id_admin);

        return view('user.edit',compact('userEdit', 'empresas'));
    }

    public function updateUser(Request $request)
    {
        if(!is_null($request)){
            $user = new User();
            $res = $user->updateUser($request);
            if ($res) {
                $mensaje = "true";
                return redirect()->action('Admin\UserController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\UserController@index', compact('mensaje'));
            }
            
        } else {
            return redirect()->action('Admin\UserController@index');
        }
        
    }

    public function updatePassword(Request $request)
    {
        if(!is_null($request)){
            $user = new User();
            $res = $user->updatePass($request);
            if ($res) {
                $mensaje = "true";
                return redirect()->action('Admin\UserController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\UserController@index', compact('mensaje'));
            }
            
        } else {
            return redirect()->action('Admin\UserController@index');
        }
    }

    public function deleteUser($id = 0)
    {
        $user = new User();
        $res = $user->deleteById($id);
        if ($res) {
            $mensaje = "true";
            return redirect()->action('Admin\UserController@index', compact('mensaje'));
        }
        else {
            $mensaje = "false";
            return redirect()->action('Admin\UserController@index', compact('mensaje'));
        }
    }

}
