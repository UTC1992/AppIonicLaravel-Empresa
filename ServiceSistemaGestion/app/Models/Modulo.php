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
	    'estado',
      'ruta',
      'icono_menu'
  	];

  	public function guardar($data = [])
    {
        try {
            if(!is_null($data)){
                $modulo = new Modulo();
                $modulo->nombre = $data->nombre;
                $modulo->codigo = $data->codigo;
                $modulo->estado = $data->estado;
                $modulo->ruta = $data->ruta;
                $modulo->icono_menu = $data->icono_menu;
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
          $modulo->codigo = $data->codigo;
          $modulo->estado = $data->estado;
          $modulo->ruta = $data->ruta;
          $modulo->icono_menu = $data->icono_menu;
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

    /**
     * obtener modulos por empresa
     */
    public function getModulosEmpresa($id){
      try {
        return $actividad = DB::table('tbl_modulo as T0')
              ->join('tbl_modulo_empresa as T1','T1.id_mod','=','T0.id_mod')
              ->select('T0.nombre','T0.estado','T0.ruta', 'T0.icono_menu')
              ->where('T1.id_emp',$id)
              ->get();
      } catch (\Exception $e) {
        return $e;
      }
    }
}
