<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Modulo extends Model
{
  	protected $table="tbl_modulo";
 	  protected $primaryKey = 'id_mod';
  	protected $fillable = [
	    'id_mod',
	    'nombre',
	    'estado'
  	];
  
  	public function guardar($data = [])
    {
        try {
            if(!is_null($data)){
                $modulo = new Modulo();
                $modulo->nombre = $data->nombre;
                $modulo->estado = $data->estado;
                $modulo->save();
                return true;
            } else 
            {
                return false;
            }   
        } catch (Exception $e) {
            return false;   
        }
    }

    public function getModulos()
    {
    	return $modulo = Modulo::all();
    }

    public function getById($id='')
    {
      return Modulo::find($id);
    }

    public function updateModulo($data=[])
    {	
    	try {
	        $modulo = Modulo::find($data->id_mod);
	        $modulo->nombre = $data->nombre;
	        $modulo->estado = $data->estado;
	        $modulo->save();
	        return true;
      	} catch (\Exception $e) {
	        return false;
      	}
    }

    public function deleteById($id=0)
    {
    	try {
	        return Modulo::find($id)->delete();
      	} catch (\Exception $e) {
	        return $e;
      	}
    }
}