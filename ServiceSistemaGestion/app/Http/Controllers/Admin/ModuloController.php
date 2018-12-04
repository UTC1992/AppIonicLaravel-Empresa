<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Modulo;
use Auth;

class ModuloController extends Controller
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
        $objeto = new Modulo();
        $modulos = $objeto->getModulos();
        return view('modulo.inicio',compact('modulos'));
        //return response()->json($empresas);
    }

    public function guardar(Request $request)
    {   
        if(!is_null($request)){
            $modulo = new Modulo();
            $save = $modulo->guardar($request);
            if ($save) {
                $mensaje = "true";
                return redirect()->action('Admin\ModuloController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\ModuloController@index', compact('mensaje'));
            }
        } else {
            return redirect()->action('Admin\ModuloController@index');
        }
        
    }    

    public function edit($id = 0)
    {
        $modulo = new Modulo();
        $res = $modulo->getById($id);
        return view('modulo.edit',compact('res'));
    }

    public function updateMod(Request $request)
    {
        if(!is_null($request)){
            $modulo = new Modulo();
            $res = $modulo->updateModulo($request);
            if ($res) {
                $mensaje = "true";
                return redirect()->action('Admin\ModuloController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\ModuloController@index', compact('mensaje'));
            }
            
        } else {
            return redirect()->action('Admin\ModuloController@index');
        }
        
    }

    public function deleteMod($id = 0)
    {
        $modulo = new Modulo();
        $res = $modulo->deleteById($id);
        if ($res) {
            $mensaje = "true";
            return redirect()->action('Admin\ModuloController@index', compact('mensaje'));
        }
        else {
            $mensaje = "false";
            return redirect()->action('Admin\ModuloController@index', compact('mensaje'));
        }
    }

}
