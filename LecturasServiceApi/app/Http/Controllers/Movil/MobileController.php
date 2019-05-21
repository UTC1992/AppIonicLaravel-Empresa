<?php
namespace App\Http\Controllers\Movil;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Catastros;

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
                        ->limit(50)
                        ->get();

        return response()->json($rutas);
      } catch (\Exception $e) {
          return response()->json("error: ".$e);
      }
    }


    /**
     * recibe data desde móvil para proceso
     */
    public function recibirLecturas(Request $request){
      try {
        //$input=$request->json()->all();
        
        //$input=$request->json()->all();
        $data=json_decode($request->listTareas, true);
        $idEmpresa=$request->id_emp;
        $tablaLecturasCompany=$this->getTableCompany($idEmpresa);

        $cont=0;
        foreach ($data as $key => $value) {
          // code...
          // data lecturas
          $dataProcArray=array();
          $dataProcArray["nueva_lectura"]=$value["lectura_actual"];
          $dataProcArray["estado"]=$value["estado"];
          DB::table($tablaLecturasCompany)
                 ->where('id',$value["id"])
                 ->update($dataProcArray);
          //orden trabajo
          $dataOrdenTrabajo=array();
          $dataOrdenTrabajo["fecha_lectura"]=$value["fechatarea"];
          $dataOrdenTrabajo["hora"]=$value["hora"];
          $dataOrdenTrabajo["lat"]=$value["lat_lectura"];
          $dataOrdenTrabajo["lon"]=$value["lon_lectura"];
          $dataOrdenTrabajo["observacion"]=$value["observacion"];;
          $dataOrdenTrabajo["foto"]=$value["foto"];

          if($value["estado"]!=0){
            $dataOrdenTrabajo["estado"]=$value["estado"];
          }
          
          DB::table('orden_trabajo')
                 ->where('id_lectura',$value["id"])
                 ->update($dataOrdenTrabajo);
          $cont++;
        }

        if($cont>0){
          $data["mensaje"]="Lecturas recibidas correctamente";
          $data["cantidad"]=$cont;
          $data["status"]=true;
        }else{
          $data["mensaje"]="Ocurrio un error al actualizar registros";
          $data["status"]=false;
        }
        
        return response()->json($data);
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

  /**
   * ingresar catastros
   */
  public function insertCatastros(Request $request){
    try {
      $input= $request->all();
      $res= Catastros::create($input);
      return $this->ApiResponser($res);
    } catch (\Exception $e) {
        return response()->json("error: ".$e);
    }

  }


}
