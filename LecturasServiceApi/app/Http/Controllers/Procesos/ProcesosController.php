<?php
namespace App\Http\Controllers\Procesos;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Configuracion;
use App\Traits\ApiResponser;
Use App\Models\Procedimientos;

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
        $mes=0;
        $mes=$request->mes;
        $configRow=$this->getConfigCompany($request->id);
        if(count($configRow)<=0){
          return response()->json("Error: La empresa con ID: ".$request->id." no tiene generada su configuración");
        }
        $res=false;
        $filename = $request->file;
        $tabla="";
          if (file_exists($filename) && is_readable ($filename)) {
          $fileResource  = fopen($filename, "r");
          if ($fileResource) {
            //$res="in proces";
              $contInsert=0;
              $dataInsert=array();

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
                //echo(" data : ".$data["este"]);
                $coordenadas=$this->changeUtmCoordinates($data["este"],$data["norte"],17,false);
                $data["longitud"]=round($coordenadas["lon"],6);
                $data["latitud"]=round($coordenadas["lat"],7);
                $data["idEmpresa"]=$request->id;
                $data["estado"]=false;
                $data["created_at"]=date('Y-m-d H:i:s');
                $data["mes"]=12;
                $dataInsert[$contInsert]=$data;

                if($contInsert>=2100){
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
                //print_r($dataInsert);
              }
              $res=true;
          }

          }

          return response()->json($res);

      } catch (\Exception $e) {

        return response()->json("error: ".$e);
      }
    }

    private function getConfigCompany($idCompany){
      $config = DB::table('configuraciones')->where('idEmpresa', $idCompany)->get();
      return $config;
    }

    private function validarExistenciaMedidor($medidor){
    return   $result= DB::table($table)
                    ->where('medidor',$medidor)
                    ->whereNotNull('lectura')
                    ->exists();
    }

    private function createRegister($table,$data){

      DB::table($table)->insert($data);
      //BaseDatosDecobo::insert($data);
    }

    /**
     * Obtener datos filtro distribución
     */
    public function getColumnsFilter(Request $request){
      try {

        //$order=$request->order;
        $idEmpresa=$request->idEmpresa;
        $tableCompany=$this->getTableCompany($idEmpresa);
        if($tableCompany==""){
            return response()->json("error: No existe configuración para la empresa con ID :".$idEmpresa);
        }
        $columnFilter=$this->getColumnFiltertCompanyByOrder($idEmpresa);
        if(!$columnFilter){
          return response()->json("error: No se ha asignado  filtros en la configuración de la empresa con ID: ".$idEmpresa);
        }
        //$columnGroupByFilter = DB::table($tableCompany)->select($columnFilter)->where('estado', 0)->groupBy($columnFilter)->get();

        return response()->json($columnFilter);
      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }
    /**
     * Obtiene campo filtro segun el orden de llamada
     */
    private function getColumnFiltertCompanyByOrder($idEmpresa){
    try {
      $column = DB::table('configuraciones')->where('idEmpresa', $idEmpresa)->where('filter','1')->get();
      return $column;
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
 * obtiene datos del prlmer filtro de distribución
 */

 public function getDataFirstFilter(Request $request){
   try {
     $primerCampo="";
     $idEmpresa=$request->id;
     $tabla = $this->getTableCompany($idEmpresa);
     $config= $this->getConfigCompany($idEmpresa);
     foreach ($config as $key => $value) {
       if($value->key=="column" && $value->filter==1 && $value->order==1){
         $primerCampo=$value->value;
         break;
       }
     }

     if($primerCampo!=""){
       $result = DB::table($tabla)->select($primerCampo)->groupBy($primerCampo)->get();
       return response()->json($result);
     }else{
       return response()->json("error: No se ha asignado campo filtro de primer orden");
     }
   } catch (\Exception $e) {
     return response()->json("error: ".$e);
   }

 }
// obtiene datos de consulta
    public function getDataGroupBy(Request $request){
      try {
        $whereArray= $request->whereData;
        $idEmpresa= $request->id;
        $campoSelect= $request->select;

        $tabla = $this->getTableCompany($idEmpresa);
        $result = DB::table($tabla)->select($campoSelect)->where($whereArray)->groupBy($campoSelect)->get();
        return response()->json($result);

      } catch (\Exception $e) {
        return response()->json("error: ".$e);
      }

    }

    /**
     * obtiene los datos a distribuir
     *@Parm :  array de campos filtro(where)
     */
    public function getDataToDistribution(Request $request){
      try {
        $array=$request->data;
        $idEmpresa= $request->id;
        $tabla = $this->getTableCompany($idEmpresa);
        $result = DB::table($tabla)->select('id')->where($array)->where('estado',0)->get();
        return response()->json($result);

      } catch (\Exception $e) {
          return response()->json("error: ".$e);
      }

    }


    /**
     * Descarga de consolidado
     */
    public function downloadFileConsolidado(Request $request){

    }

    /**
     * generar location latitud y longitud
     */
     //funcion validar longitud y  latitud
     public function validarCoordenada($coordenada){
       $res=preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?);[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $coordenada);
       return $res;
     }

       //funciones para convertir cordenas utm en geograficas

       private function changeUtmCoordinates($x,$y,$zone,$aboveEquator){
           if(!is_numeric($x) or !is_numeric($y) or !is_numeric($zone)){
             return false;
           }
           $southhemi = false;
           if($aboveEquator!=true){
             $southhemi = true;
           }
           $latlon = $this->UTMXYToLatLon($x, $y, $zone, $southhemi);
           return array('lat'=>$this->radian2degree($latlon[0]),'lon'=>$this->radian2degree($latlon[1]));
         }

         private function radian2degree($rad){
           $pi = 3.14159265358979;
                 return ($rad / $pi * 180.0);
         }
         private function degree2radian($deg){
           $pi = 3.14159265358979;
           return ($deg/180.0*$pi);
         }
         private function UTMCentralMeridian($zone){
           $cmeridian = $this->degree2radian(-183.0 + ($zone * 6.0));
           return $cmeridian;
         }
         private function LatLonToUTMXY($lat, $lon, $zone){
                 $xy = $this->MapLatLonToXY ($lat, $lon, $this->UTMCentralMeridian($zone));
           /* Adjust easting and northing for UTM system. */
           $UTMScaleFactor = 0.9996;
                 $xy[0] = $xy[0] * $UTMScaleFactor + 500000.0;
                 $xy[1] = $xy[1] * $UTMScaleFactor;
                 if ($xy[1] < 0.0)
                     $xy[1] = $xy[1] + 10000000.0;
                 return $xy;
         }
         private function UTMXYToLatLon($x, $y, $zone, $southhemi){
           $latlon = array();
           $UTMScaleFactor = 0.9996;
                 $x -= 500000.0;
                 $x /= $UTMScaleFactor;
                 /* If in southern hemisphere, adjust y accordingly. */
                 if ($southhemi)
                   $y -= 10000000.0;
                 $y /= $UTMScaleFactor;
                 $cmeridian = $this->UTMCentralMeridian ($zone);
                 $latlon = $this->MapXYToLatLon ($x, $y, $cmeridian);
                 return $latlon;
         }
         private function MapXYToLatLon($x, $y, $lambda0){
           $philambda = array();
           $sm_b = 6356752.314;
           $sm_a = 6378137.0;
           $UTMScaleFactor = 0.9996;
           $sm_EccSquared = .00669437999013;
                 $phif = $this->FootpointLatitude ($y);
                 $ep2 = (pow ($sm_a, 2.0) - pow ($sm_b, 2.0)) / pow ($sm_b, 2.0);
                 $cf = cos ($phif);
                 $nuf2 = $ep2 * pow ($cf, 2.0);
                 $Nf = pow ($sm_a, 2.0) / ($sm_b * sqrt (1 + $nuf2));
                 $Nfpow = $Nf;
                 $tf = tan ($phif);
                 $tf2 = $tf * $tf;
                 $tf4 = $tf2 * $tf2;
                 $x1frac = 1.0 / ($Nfpow * $cf);
                 $Nfpow *= $Nf;
                 $x2frac = $tf / (2.0 * $Nfpow);
                 $Nfpow *= $Nf;
                 $x3frac = 1.0 / (6.0 * $Nfpow * $cf);
                 $Nfpow *= $Nf;
                 $x4frac = $tf / (24.0 * $Nfpow);
                 $Nfpow *= $Nf;
                 $x5frac = 1.0 / (120.0 * $Nfpow * $cf);
                 $Nfpow *= $Nf;
                 $x6frac = $tf / (720.0 * $Nfpow);
                 $Nfpow *= $Nf;
                 $x7frac = 1.0 / (5040.0 * $Nfpow * $cf);
                 $Nfpow *= $Nf;
                 $x8frac = $tf / (40320.0 * $Nfpow);
                 $x2poly = -1.0 - $nuf2;
                 $x3poly = -1.0 - 2 * $tf2 - $nuf2;
                 $x4poly = 5.0 + 3.0 * $tf2 + 6.0 * $nuf2 - 6.0 * $tf2 * $nuf2- 3.0 * ($nuf2 *$nuf2) - 9.0 * $tf2 * ($nuf2 * $nuf2);
                 $x5poly = 5.0 + 28.0 * $tf2 + 24.0 * $tf4 + 6.0 * $nuf2 + 8.0 * $tf2 * $nuf2;
                 $x6poly = -61.0 - 90.0 * $tf2 - 45.0 * $tf4 - 107.0 * $nuf2 + 162.0 * $tf2 * $nuf2;
                 $x7poly = -61.0 - 662.0 * $tf2 - 1320.0 * $tf4 - 720.0 * ($tf4 * $tf2);
                 $x8poly = 1385.0 + 3633.0 * $tf2 + 4095.0 * $tf4 + 1575 * ($tf4 * $tf2);
                 $philambda[0] = $phif + $x2frac * $x2poly * ($x * $x)
                   + $x4frac * $x4poly * pow ($x, 4.0)
                   + $x6frac * $x6poly * pow ($x, 6.0)
                   + $x8frac * $x8poly * pow ($x, 8.0);

                 $philambda[1] = $lambda0 + $x1frac * $x
                   + $x3frac * $x3poly * pow ($x, 3.0)
                   + $x5frac * $x5poly * pow ($x, 5.0)
                   + $x7frac * $x7poly * pow ($x, 7.0);

                 return $philambda;
         }
         private function FootpointLatitude($y){
           $sm_b = 6356752.314;
           $sm_a = 6378137.0;
           $UTMScaleFactor = 0.9996;
           $sm_EccSquared = .00669437999013;
                 $n = ($sm_a - $sm_b) / ($sm_a + $sm_b);
                 $alpha_ = (($sm_a + $sm_b) / 2.0)* (1 + (pow ($n, 2.0) / 4) + (pow ($n, 4.0) / 64));
                 $y_ = $y / $alpha_;
                 $beta_ = (3.0 * $n / 2.0) + (-27.0 * pow ($n, 3.0) / 32.0)+ (269.0 * pow ($n, 5.0) / 512.0);
                 $gamma_ = (21.0 * pow ($n, 2.0) / 16.0)+ (-55.0 * pow ($n, 4.0) / 32.0);
                 $delta_ = (151.0 * pow ($n, 3.0) / 96.0)+ (-417.0 * pow ($n, 5.0) / 128.0);
                 $epsilon_ = (1097.0 * pow ($n, 4.0) / 512.0);
                 $result = $y_ + ($beta_ * sin (2.0 * $y_))
                     + ($gamma_ * sin (4.0 * $y_))
                     + ($delta_ * sin (6.0 * $y_))
                     + ($epsilon_ * sin (8.0 * $y_));
                 return $result;
         }
         private function MapLatLonToXY($phi, $lambda, $lambda0){
           $xy=array();
           $sm_b = 6356752.314;
           $sm_a = 6378137.0;
           $UTMScaleFactor = 0.9996;
           $sm_EccSquared = .00669437999013;
           $ep2 = (pow ($sm_a, 2.0) - pow ($sm_b, 2.0)) / pow ($sm_b, 2.0);
           $nu2 = $ep2 * pow (cos ($phi), 2.0);
           $N = pow ($sm_a, 2.0) / ($sm_b * sqrt (1 + $nu2));
           $t = tan ($phi);
           $t2 = $t * $t;
           $tmp = ($t2 * $t2 * $t2) - pow ($t, 6.0);
           $l = $lambda - $lambda0;
           $l3coef = 1.0 - $t2 + $nu2;
           $l4coef = 5.0 - $t2 + 9 * $nu2 + 4.0 * ($nu2 * $nu2);
           $l5coef = 5.0 - 18.0 * $t2 + ($t2 * $t2) + 14.0 * $nu2- 58.0 * $t2 * $nu2;
           $l6coef = 61.0 - 58.0 * $t2 + ($t2 * $t2) + 270.0 * $nu2- 330.0 * $t2 * $nu2;
           $l7coef = 61.0 - 479.0 * $t2 + 179.0 * ($t2 * $t2) - ($t2 * $t2 * $t2);
           $l8coef = 1385.0 - 3111.0 * $t2 + 543.0 * ($t2 * $t2) - ($t2 * $t2 * $t2);
           $xy[0] = $N * cos ($phi) * $l
                     + ($N / 6.0 * pow (cos ($phi), 3.0) * $l3coef * pow ($l, 3.0))
                     + ($N / 120.0 * pow (cos ($phi), 5.0) * $l5coef * pow ($l, 5.0))
                     + ($N / 5040.0 * pow (cos ($phi), 7.0) * $l7coef * pow ($l, 7.0));
           $xy[1] = $this->ArcLengthOfMeridian ($phi)
                     + ($t / 2.0 * $N * pow (cos ($phi), 2.0) * pow ($l, 2.0))
                     + ($t / 24.0 * $N * pow (cos ($phi), 4.0) * $l4coef * pow ($l, 4.0))
                     + ($t / 720.0 * $N * pow (cos ($phi), 6.0) * $l6coef * pow ($l, 6.0))
                     + ($t / 40320.0 * $N * pow (cos ($phi), 8.0) * $l8coef * pow ($l, 8.0));
           return $xy;
         }
         private function ArcLengthOfMeridian($phi){
           $sm_b = 6356752.314;
           $sm_a = 6378137.0;
           $UTMScaleFactor = 0.9996;
           $sm_EccSquared = .00669437999013;
           $n = ($sm_a - $sm_b) / ($sm_a + $sm_b);
           $alpha = (($sm_a + $sm_b) / 2.0)
             * (1.0 + (pow ($n, 2.0) / 4.0) + (pow ($n, 4.0) / 64.0));
           $beta = (-3.0 * $n / 2.0) + (9.0 * pow ($n, 3.0) / 16.0)
                    + (-3.0 * pow ($n, 5.0) / 32.0);
           $gamma = (15.0 * pow ($n, 2.0) / 16.0)
                     + (-15.0 * pow ($n, 4.0) / 32.0);
           $delta = (-35.0 * pow ($n, 3.0) / 48.0)
                     + (105.0 * pow ($n, 5.0) / 256.0);
           $epsilon = (315.0 * pow ($n, 4.0) / 512.0);
           $result = $alpha* ($phi + ($beta * sin (2.0 * $phi))
                     + ($gamma * sin (4.0 * $phi))
                     + ($delta * sin (6.0 * $phi))
               + ($epsilon * sin (8.0 * $phi)));
           return $result;
         }


       //fin funciones

/**
 *actualiza la orden de trabajo
 */
public function actualizarOrdenTrabajo()
{
  try {
    $decobo_temp1=DB::table('decobo')
                  ->limit(3000)
                  ->orderByRaw('id asc')
                  ->get();
    $cont=0;
    $cont_nuew=0;
    $cont_null=0;
    $array=array();

    foreach ($decobo_temp1 as $key => $value) {
        $data=array();
        $data['zona']=$value->zona;
        $data['agencia']=$value->agencia;
        $data['sector']=$value->sector;
        $data['ruta']=$value->ruta;
        $data['cuenta']=$value->cuenta;
        $data['medidor']=$value->medidor;
        $data['lectura']=$value->lectura;
        $res=DB::table('decobo_orden_temp')
                 ->where('medidor',$value->medidor)->first();
        if($res){
            if(is_null($res->lectura) || $res->lectura==''){
              DB::table('decobo_orden_temp')
                  ->updateOrInsert(
                      ['medidor' =>$value->medidor],
                      ['lectura'=>$value->lectura]
                  );
              $cont_null++;
            }
            $cont++;
        }else{
          DB::table('decobo_orden_temp')->insert($data);
          $cont_nuew++;
        }
        //$array[$cont]=$res;

        /*
        DB::table('decobo_orden_temp')
            ->updateOrInsert(
                ['medidor' => ''.$value->medidor.''],
                $data
            );
    /*
          //$result = DB::table('decobo_orden_temp')->where('medidor',''.$value->medidor.'')->whereNull('lectura')->exists();
        if(DB::table('decobo_orden_temp')->where('medidor',$value->medidor)->exists()){
        //$result = DB::table('decobo_orden_temp')->where('medidor',$value->medidor)->where('lectura',null)->get();
            //DB::table('decobo_orden_temp')->where('medidor',$value->medidor)->update(['lectura'=>$value->lectura]);
            DB::table('decobo_orden_temp')->where('id','!=',0)->where('medidor',$value->medidor)->update(['lectura'=>''.$value->lectura.'']);

              $cont++;


        }else{
          //$cont++;
          //DB::table('decobo_orden_temp')->create($value->all());
        }*/

    }
    $dataRe=array();
    $dataRe["encontrados"]=$cont;
    $dataRe["nuevo"]=$cont_nuew;
    $dataRe['actualizado']=$cont_null;
    //$dataRe["data"]=$array;
    return response()->json($dataRe);
  } catch (\Exception $e) {
    return response()->json("error: ".$e);
  }

}

}
