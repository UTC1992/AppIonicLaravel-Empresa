<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Plan;
use Auth;

class PlanController extends Controller
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
        $objeto = new Plan();
        $planes = $objeto->getPlanes();
        return view('plan.inicio',compact('planes'));
        //return response()->json($empresas);
    }

    public function guardar(Request $request)
    {   
        if(!is_null($request)){
            $plan = new Plan();
            $save = $plan->guardar($request);
            if ($save) {
                $mensaje = "true";
                return redirect()->action('Admin\PlanController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\PlanController@index', compact('mensaje'));
            }
        } else {
            return redirect()->action('Admin\PlanController@index');
        }
        
    }    

    public function edit($id = 0)
    {
        $plan = new Plan();
        $res = $plan->getById($id);
        return view('plan.edit',compact('res'));
    }

    public function updatePlan(Request $request)
    {
        if(!is_null($request)){
            $plan = new Plan();
            $res = $plan->updatePlan($request);
            if ($res) {
                $mensaje = "true";
                return redirect()->action('Admin\PlanController@index', compact('mensaje'));
            }
            else {
                $mensaje = "false";
                return redirect()->action('Admin\PlanController@index', compact('mensaje'));
            }
            
        } else {
            return redirect()->action('Admin\PlanController@index');
        }
        
    }

    public function deletePlan($id = 0)
    {
        $plan = new Plan();
        $res = $plan->deleteById($id);
        if ($res) {
            $mensaje = "true";
            return redirect()->action('Admin\PlanController@index', compact('mensaje'));
        }
        else {
            $mensaje = "false";
            return redirect()->action('Admin\PlanController@index', compact('mensaje'));
        }
    }

}
