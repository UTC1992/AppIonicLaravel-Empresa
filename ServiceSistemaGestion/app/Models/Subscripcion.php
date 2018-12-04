<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Subscripcion extends Model
{
  	protected $table="tbl_modulo_empresa";
 	  protected $primaryKey = 'id_mod_emp';
  	protected $fillable = [
	    'id_mod_emp',
	    'id_mod',
	    'id_emp'
  	];
  
  	public function guardar($data = [])
    {
        try {
            if(!is_null($data)){
                $sub = new Subscripcion();
                $sub->id_mod = $data->id_mod;
                $sub->id_emp = $data->id_emp;
                $sub->save();
                return true;
            } else 
            {
                return false;
            }   
        } catch (Exception $e) {
            return false;   
        }
    }

    public function getSubscripciones()
    {
    	//return $sub = Subscripcion::all();
      return $sub = DB::select('select T1.nombre as nombreEmp, T2.nombre as nombreMod, T0.id_mod_emp as idSubs from tbl_modulo_empresa as T0, tbl_empresa as T1, tbl_modulo as T2 where T0.id_emp=T1.id_emp and T0.id_mod=T2.id_mod', []);
    }

    public function getById($id='')
    {
      //return Subscripcion::find($id);
      return $sub = DB::select('select T1.nombre as nombreEmp, T1.id_emp as idEmp, T2.nombre as nombreMod, T2.id_mod as idMod, T0.id_mod_emp as idSubs from tbl_modulo_empresa as T0, tbl_empresa as T1, tbl_modulo as T2 where T0.id_emp=T1.id_emp and T0.id_mod=T2.id_mod and T0.id_mod_emp=:id', ['id' => $id]);
    }

    public function updateSubscripcion($data=[])
    {	
    	try {
	        $sub = Subscripcion::find($data->id_mod_emp);
	        $sub->id_emp = $data->id_emp;
	        $sub->id_mod = $data->id_mod;
	        $sub->save();
	        return true;
      	} catch (\Exception $e) {
	        return false;
      	}
    }

    public function deleteById($id=0)
    {
    	try {
	        return Subscripcion::find($id)->delete();
      	} catch (\Exception $e) {
	        return $e;
      	}
    }
}