<?php
namespace App\Http\Controllers\Procesos;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Configuracion;
use App\Traits\ApiResponser;

class ProcesosController extends Controller
{
  use ApiResponser;

    public function __construct()
    {

    }

/**
 * metodo carga archivo txt
 */
    public function carga(Request $request){
      try {

        $filename = $request->file;
        $tabla="";
          if (file_exists($filename) && is_readable ($filename)) {
          $fileResource  = fopen($filename, "r");
          if ($fileResource) {
              $contInsert=0;
              $dataInsert=array();
              $configRow=$this->getConfigCompany($request->id);
              while (($line = fgets($fileResource)) !== false) {

                $lineArray=array();
                $lineArrayFilter= array();
                $lineArray = explode("|", $line);

                $cn=0;
                for ($i=0; $i < count($lineArray); $i++) {
                  if($lineArray[$i]!=""){
                    $lineArrayFilter[$cn]=utf8_encode($lineArray[$i]);
                    $cn++;
                  }
                }

                $cont=0;
                $data=array();
                $config=$configRow;
                foreach ($config as $key => $value) {
                  if($cont>=count($lineArrayFilter)){
                    break;
                  }
                  if($value->key!="table"){
                    $data[$value->value]= trim($lineArrayFilter[$cont]);
                    $cont++;;
                  }
                  if($value->key=="table"){
                    $tabla= $value->value;
                  }
                }

                $data["idEmpresa"]=$request->id;
                $data["estado"]=false;
                $dataInsert[$contInsert]=$data;
                if($contInsert>=2500){
                  $this->createRegister($tabla,$dataInsert);
                  $dataInsert=array();
                  $contInsert=0;
                }
                $contInsert++;
              }
              fclose($fileResource);

              /**
               * comprueba si hay residuos de datos en el array
               */
              if(count($dataInsert)>0){
                $this->createRegister($tabla,$dataInsert);
                print_r($dataInsert);
              }
          }

          }
          return response()->json(true);

      } catch (\Exception $e) {

        return response()->json("error: ".$e);
      }
    }

    private function getConfigCompany($idCompany){
      $config = DB::table('configuraciones')->where('idEmpresa', $idCompany)->get();
      return $config;
    }


    private function createRegister($table,$data){

        DB::table($table)->insert($data);
    }

    /**
     * Obtener datos filtro distribución
     */
    public function getColumnFilter(Request $request){
      try {

        $order=$request->order;
        $idEmpresa=$request->idEmpresa;
        $tableCompany=$this->getTableCompany($idEmpresa);
        if($tableCompany==""){
            return response()->json("error: No existe configuración para la empresa con ID :".$idEmpresa);
        }
        $columnFilter=$this->getColumnFiltertCompanyByOrder($order,$idEmpresa);
        if(!$columnFilter){
          return response()->json("error: No se ha asignado orden de filtro en la configuración de la empresa");
        }
        $columnGroupByFilter = DB::table($tableCompany)->select($columnFilter)->where('estado', 0)->groupBy($columnFilter)->get();

        return response()->json($columnGroupByFilter);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }
    /**
     * Obtiene campo filtro segun el orden de llamada
     */
    private function getColumnFiltertCompanyByOrder($order,$idEmpresa){
    try {
      $column = DB::table('configuraciones')->where('idEmpresa', $idEmpresa)->where('order',$order)->first();
      return $column->value;
    } catch (\Exception $e) {
      return false;
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
     * Descarga de consolidado
     */
    public function downloadFileConsolidado(Request $request){

    }


    public function update(Request $request, $empresa){

    }
    public function destroy($empresa){

    }



}
