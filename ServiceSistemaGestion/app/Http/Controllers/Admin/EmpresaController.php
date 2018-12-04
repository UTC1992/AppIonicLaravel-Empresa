<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Empresa;
use Auth;

class EmpresaController extends Controller
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
        $objeto = new Empresa();
        $empresas = $objeto->getEmpresasByAdmin(Auth::user()->id_admin);
        return view('empresa.inicio',compact('empresas'));
        //return response()->json($empresas);
    }

    public function guardar(Request $request)
    {   
        if(!is_null($request)){
            $empresa = new Empresa();
            $save = $empresa->guardar($request);
            if ($save) {
                $mensaje = "true";
                return redirect()->action('Admin\EmpresaController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\EmpresaController@index', compact('mensaje'));
            }
        } else {
            return redirect()->action('Admin\EmpresaController@index');
        }
        
    }    

    public function edit($id = 0)
    {
        $empresa = new Empresa();
        $res = $empresa->getById($id);
        return view('empresa.edit',compact('res'));
    }

    public function updateEmp(Request $request)
    {
        if(!is_null($request)){
            $empresa = new Empresa();
            $res = $empresa->updateEmpresa($request);
            if ($res) {
                $mensaje = "true";
                return redirect()->action('Admin\EmpresaController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\EmpresaController@index', compact('mensaje'));
            }
            
        } else {
            return redirect()->action('Admin\EmpresaController@index');
        }
        
    }

    public function deleteEmp($id = 0)
    {
        $empresa = new Empresa();
        $res = $empresa->deleteById($id);
        if ($res) {
            $mensaje = "true";
            return redirect()->action('Admin\EmpresaController@index', compact('mensaje'));
        }
        else {
            $mensaje = "false";
            return redirect()->action('Admin\EmpresaController@index', compact('mensaje'));
        }
    }

}
