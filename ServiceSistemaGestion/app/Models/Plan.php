<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Plan extends Model
{
  	protected $table="tbl_planes";
    protected $primaryKey = 'id_plan';
  	protected $fillable = [
	    'id_plan',
	    'nombre',
	    'descripcion',
      'num_tecnicos'
  	];

  	public function guardar($data = [])
    {
        try {
            if(!is_null($data)){
                $plan = new Plan();
                $plan->nombre = $data->nombre;
                $plan->descripcion = $data->descripcion;
                $plan->num_tecnicos = $data->num_tecnicos;
                $plan->save();
                return true;
            } else
            {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPlanes()
    {
    	return $plan = Plan::all();
    }

    public function getById($id='')
    {
      return Plan::find($id);
    }

    public function updatePlan($data=[])
    {
    	try {
	        $plan = Plan::find($data->id_plan);
	        $plan->nombre = $data->nombre;
            $plan->descripcion = $data->descripcion;
            $plan->num_tecnicos = $data->num_tecnicos;
	        $plan->save();
	        return true;
      	} catch (\Exception $e) {
	        return false;
      	}
    }

    public function deleteById($id=0)
    {
    	try {
	        return Plan::find($id)->delete();
      	} catch (\Exception $e) {
	        return $e;
      	}
    }

    /**
     * obtener modulos por empresa
     */
    /*public function getModulosEmpresa($id){
      try {
        return $actividad = DB::table('tbl_modulo as T0')
              ->join('tbl_modulo_empresa as T1','T1.id_mod','=','T0.id_mod')
              ->select('T0.nombre','T0.estado','T0.ruta', 'T0.icono_menu')
              ->where('T1.id_emp',$id)
              ->get();
      } catch (\Exception $e) {
        return $e;
      }
    }*/
}
