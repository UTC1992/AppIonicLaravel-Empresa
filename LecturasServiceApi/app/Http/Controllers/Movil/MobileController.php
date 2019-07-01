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
      //  $tablaLecturasCompany= $this->getTableCompany($idEmpresa);
        $rutas_tecnico= DB::table("rutas_tecnicos_decobo")->where("tecnico_id",$idTecnico)->get();
        $dataResult=array();
        $cont=0;
        if(count($rutas_tecnico)>0){
        foreach ($rutas_tecnico as $key => $value) {
          $dataSelect=array();
          $dataSelect=DB::table('decobo_orden_temp')
                          ->where('agencia',$value->agencia)
                          ->where('sector',$value->sector)
                          ->where('ruta',$value->ruta)
                          ->get();
              for ($i=0; $i < count($dataSelect) ; $i++) {
                array_push($dataResult, $dataSelect[$i]);
              }
        }
          return response()->json($dataResult);
        }else{
          return response()->json(false);
        }
      } catch (\Exception $e) {
          return response()->json("error: ".$e);
      }
    }


    /**
     * recibe data desde móvil para proceso
     */
    public function recibirLecturas(Request $request){
      try {
     $data=json_decode($request->listTareas, true);
     $idEmpresa=$request->id_emp;
     $tablaLecturasCompany="decobo_orden_temp";//$this->getTableCompany($idEmpresa);

     $cont=0;
     foreach ($data as $key => $value) {
       // code...
       // data lecturas
       if($value["estado"]==2){
         $dataProcArray=array();
         $dataProcArray["nueva_lectura"]=$value["lectura_actual"];
         $dataProcArray["estado"]=$value["estado"];
         $dataProcArray["fecha_lectura"]=$value["fechatarea"];
         $dataProcArray["hora"]=$value["hora"];
         $dataProcArray["lat"]=$value["lat_lectura"];
         $dataProcArray["lon"]=$value["lon_lectura"];
         $dataProcArray["observacion"]=$value["observacion"];;
         $dataProcArray["foto"]=$value["foto"];
         $dataProcArray["estado"]=$value["estado"];
         $dataProcArray["recibido"]=1;
         DB::table($tablaLecturasCompany)
              ->where('medidor',$value["medidor"])
              ->update($dataProcArray);
       }

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


        $data= $request->json()->all();

        $dataInsert= array();
        $contador=0;
        foreach ($data as $key => $value) {

          $result=DB::table("catastros")->where("mes",$value["mes"])->where("medidor",$value["medidor"])->exists();
          if(!$result){
            $dataInsert[$contador]["idEmpresa"]=$value["idEmpresa"];
            $dataInsert[$contador]["medidor"]=$value["medidor"];
            $dataInsert[$contador]["observacion"]=$value["observacion"];
            $dataInsert[$contador]["lectura"]=$value["lectura"];
            $dataInsert[$contador]["fecha"]=$value["fecha"];
            $dataInsert[$contador]["latitud"]=$value["latitud"];
            $dataInsert[$contador]["longitud"]=$value["longitud"];
            $dataInsert[$contador]["estado"]=0;
            $dataInsert[$contador]["id_tecnico"]=$value["id_tecnico"];
            $dataInsert[$contador]["hora"]=$value["hora"];
            $dataInsert[$contador]["foto"]=$value["foto"];
            $dataInsert[$contador]["mes"]=$value["mes"];
            $dataInsert[$contador]["created_at"]= date('Y-m-d');
            if($contador==1200){
              DB::table("catastros")->insert($dataInsert);
              $contador=0;
              $dataInsert=array();
            }
            $contador++;
          }
        }

        if($contador>0){
          DB::table("catastros")->insert($dataInsert);
        }

        $res=true;
        return response()->json($res);


    } catch (\Exception $e) {
        return response()->json("error: ".$e);
    }

  }


}
