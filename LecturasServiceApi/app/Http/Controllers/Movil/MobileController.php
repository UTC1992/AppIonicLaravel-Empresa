<?php
namespace App\Http\Controllers\Movil;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class MobileController extends Controller
{
  use ApiResponser;

    public function __construct()
    {

    }

    /**
     * Obtiene lecturas a realziar por técnico y empresa
     */
    public function index($idEmpresa,$idTecnico){
      try {
        $tablaLecturasCompany= $this->getTableCompany($idEmpresa);
        $rutas = DB::table($tablaLecturasCompany)
                        ->join('orden_trabajo', 'orden_trabajo.id_lectura', '=', $tablaLecturasCompany.'.id')
                        ->where($tablaLecturasCompany.'.estado', 1)
                        ->where('orden_trabajo.id_tecnico', $idTecnico)
                        ->get();

        return response()->json($rutas);
      } catch (\Exception $e) {
          return response()->json("error: ".$e);
      }
    }


    public function prcesarLecturas(Request $request){
      try {

      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }

    /**
     * calcular consumo mensual por de cliente
     */
    private function calcularConsumo($lecturaAnterior, $nueva){
      return $consumo = $lecturaAnterior-$nueva;
    }

    /**
     * calcula consumo por historial a traves de un prmedio
     */
    private function calcularConsumoPorHistorial($numeroHistorial, $medidor){
      try {
        $tablaLecturasCompany= $this->getTableCompany($idEmpresa,$idTecnico);
        $actividades = DB::table($tablaLecturasCompany)
                        ->where('medidor', $medidor)
                        ->limit($numeroHistorial)
                        ->get();
        return $consumo;
      } catch (\Exception $e) {

      }

    }

    /**
     * obtiene configuración de la empresa
     */
    private function getConfigCompany($idCompany){
      try {
        $config = DB::table('configuraciones')->where('idEmpresa', $idCompany)->get();
        return $config;
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }
    }

    /**
     * obtiene nombre de tabla de actividades de la configuración de la empresa
     */
        private function getTableCompany($idEmpresa){
          try {
            $tabla="";
            $config = DB::table('configuraciones')->where('idEmpresa', $idEmpresa)->get();
            foreach ($config as $key => $value) {
              if($value->key=="table"){
                $tabla=$value->value;
                break;
              }
            }
            return $tabla;
          } catch (\Exception $e) {
            return response()->json("error: ".$e);
          }

        }


}
