<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Empresa extends Model
{
    protected $table="tbl_empresa";
    protected $primaryKey = 'id_emp';
    protected $fillable = [
        'id_emp',
        'nombre',
        'estado',
        'id_admin'
    ];

    protected $hidden = [
        'remember_token'
    ];

    public function guardar($data = [])
    {
        try {
            if(!is_null($data)){
                $empresa = new Empresa();
                $empresa->nombre = $data->nombre;
                $empresa->estado = $data->estado;
                $empresa->id_admin = $data->id_admin;
                $empresa->save();
                return true;
            } else
            {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getEmpresasByAdmin($id_admin=0)
    {
    	return $empresas = DB::table('tbl_empresa')
          ->select('*')
          ->where('id_admin',$id_admin)
          ->where('borrado',0)
          ->get();
    }

    public function getById($id='')
    {
      return Empresa::find($id);
    }

    public function updateEmpresa($data=[])
    {
    	try {
	        $empresa = Empresa::find($data->id_emp);
	        $empresa->nombre = $data->nombre;
	        $empresa->estado = $data->estado;
	        $empresa->save();
	        return true;
      	} catch (\Exception $e) {
	        return false;
      	}
    }

    public function deleteById($id=0)
    {
    	try {
          $empresa = Empresa::find($id);
          $empresa->borrado = 1;
          return $empresa->save();
      	} catch (\Exception $e) {
	        return $e;
      	}
    }

    /**
     * obtiene plan empresa
     */
    public static function getPlanesEmpresa($idEmpresa){
        try {
          return $planes = DB::table('tbl_empresa as T0')
              ->join('tbl_modulo_empresa as T1','T1.id_emp','=','T0.id_emp')
              ->join('tbl_modulo as T2','T2.id_mod','=','T1.id_mod')
              ->join('tbl_planes as T3','T3.id_plan','=','T1.id_plan')
              ->select('T2.nombre as modulo','T2.codigo as id_modulo','T3.nombre as plan','T3.descripcion', 'T3.num_tecnicos','T0.nombre as empresa','T1.fecha_inicio','T1.fecha_fin')
              ->where('T0.id_emp',$idEmpresa)
              ->get();
        } catch (\Exception $e) {
  
        }
    }


}
