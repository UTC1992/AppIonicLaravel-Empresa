<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Subscripcion;
use App\Models\Empresa;
use App\Models\Modulo;
use App\Models\Plan;
use Auth;

class SubscripcionController extends Controller
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
        $obj1 = new Subscripcion();
        $subs = $obj1->getSubscripciones();

        $obj2 = new Empresa();
        $empresas = $obj2->getEmpresasByAdmin(Auth::user()->id_admin);

        $obj3 = new Modulo();
        $modulos = $obj3->getModulos();

        $obj4 = new Plan();
        $planes = $obj4->getPlanes();

        return view('subscripcion.inicio',compact('subs','empresas', 'modulos', 'planes'));
        //return response()->json($empresas);
    }

    public function guardar(Request $request)
    {   
        if(!is_null($request)){
            $subs = new Subscripcion();
            $save = $subs->guardar($request);
            if ($save) {
                $mensaje = "true";
                return redirect()->action('Admin\SubscripcionController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\SubscripcionController@index', compact('mensaje'));
            }
        } else {
            return redirect()->action('Admin\SubscripcionController@index');
        }
        
    }    

    public function edit($id = 0)
    {
        $obj1 = new Subscripcion();
        $subs = $obj1->getById($id);

        $obj2 = new Empresa();
        $empresas = $obj2->getEmpresasByAdmin(Auth::user()->id_admin);

        $obj3 = new Modulo();
        $modulos = $obj3->getModulos();

        $obj4 = new Plan();
        $planes = $obj4->getPlanes();

        return view('subscripcion.edit',compact('subs','empresas', 'modulos', 'planes'));
    }

    public function updateSub(Request $request)
    {
        if(!is_null($request)){
            $subs = new Subscripcion();
            $res = $subs->updateSubscripcion($request);
            if ($res) {
                $mensaje = "true";
                return redirect()->action('Admin\SubscripcionController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\SubscripcionController@index', compact('mensaje'));
            }
            
        } else {
            return redirect()->action('Admin\SubscripcionController@index');
        }
        
    }

    public function deleteSub($id = 0)
    {
        $subs = new Subscripcion();
        $res = $subs->deleteById($id);
        if ($res) {
            $mensaje = "true";
            return redirect()->action('Admin\SubscripcionController@index', compact('mensaje'));
        }
        else {
            $mensaje = "false";
            return redirect()->action('Admin\SubscripcionController@index', compact('mensaje'));
        }
    }

}
