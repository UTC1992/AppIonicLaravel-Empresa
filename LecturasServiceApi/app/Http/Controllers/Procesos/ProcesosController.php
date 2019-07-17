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
        $contador_registros=0;
        $mes=$request->mes;
        $dataResponse=array();
        /*
        if($this->validarMesCargaRutas($mes)){
          $dataResponse["error"]="No se puede subir el archivo, el mes seleccionado ya existe en la base de datos";
          $dataResponse["status"]=false;
          return response()->json($dataResponse);
        }*/

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

                  if(($data["este"]!="0" && $data["norte"]!="0") && (!is_null($data["este"]) && !is_null($data["norte"]))){
                    $coordenadas=$this->changeUtmCoordinates($data["este"],$data["norte"],17,false);
                    $longitud=round($coordenadas["lon"],6);
                    $latitud=round($coordenadas["lat"],7);
                     if($this->validarCoordenada($latitud) && $this->validarCoordenada($longitud)){
                       if(strlen($latitud)<=11 && strlen($longitud)<=10){
                         $data["longitud"]=$longitud;
                         $data["latitud"]=$latitud;
                       }else{
                         $data["longitud"]=0;
                         $data["latitud"]=0;
                       }
                    }else {
                      $data["longitud"]=0;
                      $data["latitud"]=0;
                    }
                  }else{
                    $data["longitud"]=0;
                    $data["latitud"]=0;
                  }
                //echo(" data : ".$data["este"]);

                $data["idEmpresa"]=$request->id;
                $data["estado"]=false;
                $data["created_at"]=date('Y-m-d H:i:s');
                $data["mes"]=$mes;
                $dataInsert[$contInsert]=$data;

                if($contInsert>=2100){
                  $this->createRegister($tabla,$dataInsert);
                  $contador_registros=$contador_registros+$contInsert;
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
                $contador_registros=$contador_registros+count($dataInsert);
              }
              $res=true;
          }

          }
          $dataResponse["mensaje"]="El archivo ha sido subido correctamente";
          $dataResponse["cantidad_subida"]=$contador_registros;
          $dataResponse["status"]=$res;
          return response()->json($dataResponse);

      } catch (\Exception $e) {

        return response()->json("error: ".$e);
      }
    }

    /**
     * valida existencia de mes en hitrial
     */
    private function validarMesCargaRutas($mes){
      try {
        $result = DB::table("decobo_historial")->where("mes",$mes)->exists();
        return $result;
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

    //  DB::table($table)->insert($data);
      DB::table("decobo_orden_temp")->insert($data);
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
 *
 */
public function generarGuardarHistorialDecobo(){
  try {
    $data=array();
    $tabla='decobo_orden_temp';
    $decobo_temp=DB::table('decobo_orden_temp')->where('nueva_lectura','is null')->exists();
    if($decobo_temp){
      $data['mensaje']="No se puede guardar el historial por que existen lecturas sin procesar";
      $data['status']=false;
      return response()->json($data);
    }

    Procedimientos::guardarHistorialDecobo($tabla);
    $data['mensaje']="El historial ha sido generado con exito";
    $data['status']=true;
    return response()->json($data);
  } catch (\Exception $e) {
    return response()->json("error: ".$e);
  }

}


/**
 *
 */
public function generarOrdenTemp(){
  try {
    $data=array();
    $fecha= date('Y-m-d');
    $res = DB::table("decobo_historial")
                        ->select("secuencial")
                        ->groupBy("secuencial")
                        ->orderBy('secuencial', 'desc')
                        ->first();
    $ultimo_secuencial=$res->secuencial;
    $decobo_historial=DB::table('decobo_historial')->where('secuencial',$ultimo_secuencial)->get();
    if(count($decobo_historial)>0){

      DB::table('decobo_orden_temp')->truncate();
      $dataInsert=array();
      $contador=0;
      foreach ($decobo_historial as $key => $value) {
          $dataValue=array();
          //$dataValue["id"]=$value->id;
          $dataValue["zona"]=$value->zona;
          $dataValue["agencia"]=$value->agencia;
          $dataValue["sector"]=$value->sector;
          $dataValue["ruta"]=$value->ruta;
          $dataValue["cuenta"]=$value->cuenta;
          $dataValue["medidor"]=$value->medidor;
          $dataValue["campo_a"]=$value->campo_a;
          $dataValue["campo_n"]=$value->campo_n;
          $dataValue["campo_0"]=$value->campo_0;
          $dataValue["secuencia"]=$value->secuencia;
          $dataValue["columna2"]=$value->columna2;
          $dataValue["fechaultimalec"]=$value->fechaultimalec;
          $dataValue["lectura"]=$value->nueva_lectura;
          $dataValue["equipo"]=$value->equipo;
          $dataValue["lector"]=$value->lector;
          $dataValue["esferas"]=$value->esferas;
          $dataValue["tarifa"]=$value->tarifa;
          $dataValue["nombre"]=$value->nombre;
          $dataValue["campo10"]=$value->campo10;
          $dataValue["columna4"]=$value->columna4;
          $dataValue["este"]=$value->este;
          $dataValue["norte"]=$value->norte;
          $dataValue["estado"]=1;
          $dataValue["longitud"]=$value->longitud;
          $dataValue["latitud"]=$value->latitud;
          $dataValue["idEmpresa"]=$value->idEmpresa;
          $dataValue["created_at"]=$fecha;
          $dataValue["updated_at"]=$fecha;
          $dataValue["nueva_lectura"]="0";
          $ms=0;
          if($value->mes==12){
            $mes=1;
          }else{
            $mes=$value->mes+1;
          }
          $dataValue["mes"]=$mes;
          $dataValue["alerta"]=0;
          $dataValue["referencia_alerta"]=null;
          $dataValue["recibido"]=0;
          $dataValue["obtenido"]=0;
          $dataValue["hora"]=$value->hora;
          $dataValue["observacion"]=$value->observacion;
          $dataValue["foto"]=$value->foto;
          $dataValue["lectura_actual"]=null;
          $dataValue["lat"]=$value->lat;
          $dataValue["lon"]=$value->lon;
          $dataValue["fecha_lectura"]=$value->fecha_lectura;
          $dataValue["tecnico_id"]=$this->getTecnicoAsignacion($value->agencia,$value->sector,$value->ruta);
          $dataValue["cedula_tecnico"]=$value->cedula_tecnico;
          $dataValue["consumo_anterior"]=$value->nuevo_consumo;
          $dataValue["nuevo_consumo"]="0";
          $dataValue["procesado"]=0;
          $dataValue["secuencial"]=$ultimo_secuencial+1;
          $dataInsert[$contador]=$dataValue;
          if($contador===1100){
            DB::table("decobo_orden_temp")->insert($dataInsert);
            $dataInsert=array();
            $contador=0;
          }
          $contador++;

      }
      if(count($dataInsert)>0){
        DB::table("decobo_orden_temp")->insert($dataInsert);
      }
      $data['mensaje']="Oden de trabajo temporal  ha sido generado con exito";
      $data['status']=true;
      return response()->json($data);
    }else{
      $data['error']="No se pudo generar el orden de trabajo temporal";
      $data['status']=false;
      return response()->json($data);
    }

  } catch (\Exception $e) {
    return response()->json("error: ".$e);
  }

}

/**
 * encintrar asignacion
 */
 private function getTecnicoAsignacion($agencia,$sector,$ruta)
 {

     $result = DB::table("rutas_tecnicos_decobo")
                ->where("agencia",$agencia)
                ->where("sector",$sector)
                ->where("ruta",$ruta)
                ->first();
      if($result){
        return $result->tecnico_id;
      }
      return null;


 }
/**
 *actualiza la orden de trabajo
 */
public function actualizarOrdenTrabajo()
{
  try {
    $dataRe=array();
    $decobo_temp1=DB::table('decobo')->get();
    if(count($decobo_temp1)<=0){
      $dataRe["error"]="No se proceso ningun registro";
      $dataRe["status"]=false;
      $dataRe["total_procesados"]=0;
    }
    $cont=0;
    foreach ($decobo_temp1 as $key => $value) {
        $data=array();
        $data['zona']=$value->zona;
        $data['agencia']=$value->agencia;
        $data['sector']=$value->sector;
        $data['ruta']=$value->ruta;
        $data['cuenta']=$value->cuenta;
        $data['medidor']=$value->medidor;
        $data['campo_a']=$value->campo_a;
        $data['campo_n']=$value->campo_n;
        $data['lectura']=$value->lectura;
        $data['campo_0']=$value->campo_0;
        $data['secuencia']=$value->columna2;
        $data['columna2']=$value->campo_n;
        $data['fechaultimalec']=$value->fechaultimalec;
        $data['equipo']=$value->equipo;
        $data['lector']=$value->lector;
        $data['esferas']=$value->esferas;
        $data['tarifa']=$value->tarifa;
        $data['nombre']=$value->nombre;
        $data['direccion']=$value->direccion;
        $data['campo10']=$value->campo10;
        $data['columna4']=$value->columna4;
        $data['este']=$value->este;
        $data['norte']=$value->norte;
        $data['estado']=$value->estado;
        $data['longitud']=$value->longitud;
        $data['latitud']=$value->latitud;
        $data['idEmpresa']=$value->idEmpresa;
        $data['mes']=$value->mes;
        $data['nuevo_consumo']=0;
        $data['procesado']=0;
        $res=DB::table('decobo_orden_temp')
                 ->where('medidor',$value->medidor)->first();
        if($res){
            if(is_null($res->nueva_lectura) || $res->nueva_lectura==''){
              DB::table('decobo_orden_temp')
                  ->where('medidor',$value->medidor)
                  ->update($data);
            }else{
              $dataUpdate=array();
              $dataUpdate["mes"]=$value->mes;
              $dataUpdate['nuevo_consumo']=null;
              DB::table('decobo_orden_temp')
                  ->where('medidor',$value->medidor)
                  ->update($dataUpdate);
            }
        }else{
          DB::table('decobo_orden_temp')->insert($data);
        }
        $cont++;
    }


    $dataRe["mensaje"]="Proceso finalizado con exito";
    $dataRe["status"]=true;
    $dataRe["total_procesados"]=$cont;
    return response()->json($dataRe);
  } catch (\Exception $e) {
    return response()->json("error: ".$e);
  }

}

/**
 *
 */
private function validarConsumos($medidor,$lecturaNueva,$mes){
  try {
    $res=DB::table('decobo_historial')
             ->where('medidor',$medidor)
             ->where('mes',$mes)
             ->first();
    if($res){
      if($res->lectura>$lecturaNueva){
        return true;
      }
      return false;
    }
  } catch (\Exception $e) {
    return response()->json("error: ".$e);
  }
}


/**
 *
 */
public function  procesarCatastros(){
  try {
    $result=DB::table('catastros')->get();
    foreach ($result as $key => $value) {
      $res= DB::table('decobo_orden_temp')->where('medidor',$value->medidor)->exists();
      if($res){
        DB::table('decobo_orden_temp')->where('medidor',$value->medidor)->update(['lectura'=>$value->lectura]);
        DB::table('catastros')->where('idcatastro',$value->idcatastro)->update(['estado'=>1]);
      }
    }
    return response()->json(true);
  } catch (\Exception $e) {
    return response()->json("error: ".$e);
  }

}


/**
 * procesar lecturas y validar consumos
 */
 public function procesarConsumosFinal(){
   try {
     $dataResult=array();
     $cont=0;
     //$this->calcularConsumos();
     $this->validarObservacion();
     $this->promediaConsuloSinLecturaSinObs();
     $result = DB::table("decobo_orden_temp")
               ->where("procesado",0)
               ->get();

     foreach ($result as $key => $value) {
       $rs = $this->calcularPorcentajeMasMenos15($value->nuevo_consumo,$value->consumo_anterior);
       if(!$rs){
         DB::table("decobo_orden_temp")
          ->where("medidor",$value->medidor)
          ->update(["alerta"=>2,"referencia_alerta"=>"CONSUMO FUERA DE RANGO","procesado"=>1]);
       }
       DB::table("decobo_orden_temp")
        ->where("medidor",$value->medidor)
        ->update(["procesado"=>1]);
        $cont++;
     }
     $dataResult["mensaje"]="Consumos Validados con exito";
     $dataResult["cantidad"]=$cont;
     $dataResult["status"]=true;
   } catch (\Exception $e) {
     return response()->json("error: ".$e);
   }

 }

private function promediaConsuloSinLecturaSinObs(){
  try {
  $result =  DB::table("decobo_orden_temp")
        ->where("nueva_lectura","0")
        ->where("observacion","is null")
        ->where("lectura","!=","0")
        ->get();

        if(count($result)>0){
          foreach ($result as $key => $value) {
            $rs1 = DB::table("decobo_historial")
                  ->whereBetween("secuencial",[$value->secuencial-3,$value->secuencial-1])
                  ->where("medidor",$value->medidor)
                  ->get();
                  if(count($rs1)>0){
                    $contador=0;
                    $consumo=0;
                    $nl=0;
                    foreach ($variable as $key => $value2) {
                      $consumo = $consumo + $value2->nuevo_consumo;
                    }
                    $consumo = $consumo / count($rs1);
                    $nl = $consumo + $value->lectura;

                    DB::table("decobo_orden_temp")
                      ->where("medidor",$value->medidor)
                      ->update(["nueva_lectura"=>$nl,"nuevo_consumo"=>$consumo,"procesado"=>1]);
                  }
          }
        }
  } catch (\Exception $e) {
     return response()->json("error: ".$e);
  }

}

 public function calcularConsumos(){
   try {
     $dataResult=array();
     $cont=0;
          $result = DB::table("decobo_orden_temp")->get();
          foreach ($result as $key => $value) {
            if($value->nueva_lectura!="0"){
              $consumo = (int)$value->nueva_lectura - (int)$value->lectura;
              $result = DB::table("decobo_orden_temp")->where("medidor",$value->medidor)->update(["nuevo_consumo"=>$consumo]);
              $cont++;
            }
          }

          $dataResult["mensaje"]="Consumos calculados correctamente";
          $dataResult["cantidad"]=$cont;
          $dataResult["status"]=true;
          return response()->json($dataResult);;
   } catch (\Exception $e) {
     return response()->json("error: ".$e);
   }

 }

 private function calcularNuevoConsumo2(){
   $cont=0;
        $result = DB::table("decobo_orden_temp")->get();
        foreach ($result as $key => $value) {
          $consumo = (int)$value->nueva_lectura - (int)$value->lectura;
          $result = DB::table("decobo_orden_temp")->where("medidor",$value->medidor)->update(["nuevo_consumo"=>$consumo]);
          $cont++;
        }
        return $cont;
 }

  private function calcularPorcentajeMasMenos15($nuevo_consumo, $consumo_anterior){

      $resultMas15 = $valor + ($valor*0.15);
      $resultMenos15 = $valor - ($valor*0.15);

      if($nuevo_consumo <= $resultMas15 || $nuevo_consumo >= $resultMenos15){
        return true;
      }
      return false;
  }

  private function validarObservacion(){
    $result =DB::table("decobo_orden_temp")
            ->orWhere("observacion","borroso")
            ->orWhere("observacion","alto")
            ->orWhere("observacion","obstruido")
            ->where("procesado",0)
            ->get();
    if(count($result)>0){
      foreach ($result as $key => $value) {
        $rs1 = DB::table("decobo_historial")
              ->whereBetween("secuencial",[$value->secuencial-3,$value->secuencial-1])
              ->where("medidor",$value->medidor)
              ->get();
              if(count($rs1)>0){
                $contador=0;
                $consumo=0;
                $nl=0;
                foreach ($variable as $key => $value2) {
                  $consumo = $consumo + $value2->nuevo_consumo;
                }
                $consumo = $consumo / count($rs1);
                $nl = $consumo + $value->lectura;

                DB::table("decobo_orden_temp")
                  ->where("medidor",$value->medidor)
                  ->update(["nueva_lectura"=>$nl,"nuevo_consumo"=>$consumo,"procesado"=>1]);
              }
      }
    }
    return true;
  }

  /**
   * validar lecturas
   */
  public function validarLecturas(){
    try {

      $result =DB::table("decobo_orden_temp")
                ->where("nueva_lectura","0")
                ->where("procesado","=",0)
                ->get();
        $cont=0;
        $cont_lecturas=0;
        $cont_coordenadas=0;
        $dataResult=array();
        foreach ($result as $key => $value) {
            $lectura_anterior_cero=DB::table("decobo_historial")
                                  ->where('secuencial', ($value->secuencial)-1)
                                  ->where('nueva_lectura', '0')
                                  ->where('medidor', $value->medidor)
                                  ->where('observacion', 'like','%MEDIDOR RETIRADO%')
                                  ->exists();
            if($lectura_anterior_cero){
              DB::table("decobo_orden_temp")->where("medidor",$value->medidor)->update(["lectura"=>"0","observacion"=>"MEDIDOR RETIRADO","procesado"=>1]);
            }

            $lecturas_anteriores = DB::table("decobo_historial")
                                      ->whereBetween("secuencial",[$value->secuencial-3,$value->secuencial-1])
                                      ->where("nueva_lectura","0")
                                      ->where("medidor",$value->medidor)
                                      ->get();

            if(count($lecturas_anteriores)>0){
              foreach ($lecturas_anteriores as $key => $value) {
                DB::table("decobo_orden_temp")
                ->where("medidor",$value->medidor)
                ->update(["observacion"=>$value->observacion,"lectura"=>$value->lectura,"procesado"=>1]);
              }
            }
            $cont_lecturas++;

          if((is_null($value->longitud) || is_null($value->latitud) || ($value->longitud=="" || $value->latitud=="")) && ($value->lectura=="0" || is_null($value->lectura))){

              DB::table("decobo_orden_temp")
              ->where("medidor",$value->medidor)
              ->update(["lectura"=>"0","observacion"=>"SIN COORDENADAS","procesado"=>1]);
              $cont_coordenadas++;
          }
          $cont++;
        }

      // valida lectura menor que la anterior
      $r = $this->validaLecturaMayor();


      $dataResult["mensaje"]="Proceso terminado con exito";
      $dataResult["cantidad_lecturas_cero"]=$cont;
      $dataResult["status"]=true;

      return response()->json($dataResult);

    } catch (\Exception $e) {
       return response()->json("error: ".$e);
    }
  }


private function validaLecturaMayor(){
  try {
    $result= DB::table("decobo_orden_temp")
            ->where("nueva_lectura","<","lectura")
            ->where("procesado",0)
            ->get();
    $contador=0;
    $dataIds=array();
    foreach ($result as $key => $value) {
      $dataIds[$contador]=$value->id;
      $contador++;
    }
    if(count($dataIds)>0){
      DB::table("decobo_orden_temp")
          ->whereIn("id",$dataIds)
          ->update(["alerta"=>1,"referencia_alerta"=>"LECTURA MENOR QUE LA ANTERIOR","procesado"=>1]);
    }
    return true;
  } catch (\Exception $e) {
     return response()->json("error: ".$e);
  }


}

  public function validarConsumos2(){
    try {


    } catch (\Exception $e) {
      return response()->json("error: ".$e);
    }

  }


}
