<?php
namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\OrdenTrabajo;
use App\Models\OrdenTemp;
use App\Models\Tecnico;
use App\Models\ActividadDiaria;
use Illuminate\Support\Facades\Storage;
use Excel;

class OrdenTempController extends Controller
{
  public function __construct(){
    $this->middleware('auth:api');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $ID_EMP=$this->getIdEmpUserAuth();
    $orden=new ActividadDiaria();
    $result=$orden->where('estado',0)->where('id_emp',$ID_EMP)->get();
    return response()->json($result);
  }

  // obtener id empresa de usuario autenticado
  private function getIdEmpUserAuth(){
    try {
      $user_auth = auth()->user();
      $ID_EMP=$user_auth->id_emp;
      return $ID_EMP;
    } catch (\Exception $e) {
      return response()->json();
    }

  }
  /**
   * Eliminar una asignacion
   */
  public function deleteDistribucion($id_tecn, $sector, $cantidad)
  {
    try
    {
      $orden=new OrdenTrabajo();
      $result=$orden->where('estado',0)
                    ->where('id_tecn','=',$id_tecn)
                    ->get();
      $contador = 0;
      foreach ($result as $key => $value) {
        $actividad=new ActividadDiaria();
        $resultAct=$actividad->where('estado',1)
                          ->where('id_act','=',$value->id_act)
                          ->where('n9cose','=',$sector)
                          ->first();
        if($resultAct['id_act'] == $value->id_act && $resultAct['n9cose'] == $sector){

          $resultAct['estado'] = 0;
          $resultAct['referencia'] = 'sin asignar';
          $resultAct->save();

          $ordenDelete=new OrdenTrabajo();
          $ordenDelete=$ordenDelete->where('estado',0)
                    ->where('id_tecn','=',$value->id_tecn)
                    ->where('id_act','=',$resultAct['id_act'])
                    ->first();
          $ordenDelete->delete();
          $contador++;
        }

      }

      $ordenStateTecnico=new OrdenTrabajo();
      $resultStateTecnico=$ordenStateTecnico->where('estado',0)
        ->where('id_tecn','=',$id_tecn)
        ->get();
        if(count($resultStateTecnico) == 0){
        $tecnico = Tecnico::find($id_tecn);
        $tecnico->asignado = 0;
        $tecnico->save();
      }

      if($contador == $cantidad){
        return response()->json(true);
      } else {
        return response()->json(false);
      }
    } catch (\Exception $e) {
      return response()->json("Error: ".$e);
    }
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    if($request->hasFile('archivo')){

      $path = $request->file('archivo')->getRealPath();
      $data = \Excel::load($path)->get();
      if($data->count()){
        $con=0;
        try {
          foreach ($data as $key => $value) {
            $con++;
            $actividad=new ActividadDiaria();
              $actividad->n9sepr=$value->n9sepr;
              $actividad->n9cono= $value->n9cono;
              $actividad->n9cocu= $value->n9cocu;
              $actividad->n9selo= $value->n9selo;
              $actividad->n9cozo= $value->n9cozo;
              $actividad->n9coag= $value->n9coag;
              $actividad->n9cose= $value->n9cose;
              $actividad->n9coru= $value->n9coru;
              $actividad->n9seru= $value->n9seru;
              $actividad->n9vano= $value->n9vano;
              $actividad->n9plve= $value->n9plve;
              $actividad->n9vaca= $value->n9vaca;
              $actividad->n9esta= $value->n9esta;
              $actividad->n9cocn= $value->n9cocn;
              $actividad->n9fech= $value->n9fech;
              $actividad->n9meco= $value->n9meco;
              $actividad->n9seri= $value->n9seri;
              $actividad->n9feco= $value->n9feco;
              /*if($value->n9leco!="0"){
                $actividad->n9leco= $value->n9leco;
              }*/
              $actividad->n9leco= $value->n9leco;
              $actividad->n9manp= $value->n9manp;
              $actividad->n9cocl= $value->n9cocl;
              $actividad->n9nomb= $value->n9nomb;
              $actividad->n9cedu= $value->n9cedu;
              $actividad->n9prin= $value->n9prin;
              $actividad->n9nrpr= $value->n9nrpr;
              $actividad->n9refe= $value->n9refe;
              $actividad->n9tele= $value->n9tele;
              $actividad->n9medi= $value->n9medi;
              $actividad->n9fecl= $value->n9fecl;
              $actividad->n9lect= $value->n9lect;
              $actividad->n9cobs= $value->n9cobs;
              $actividad->n9cob2= $value->n9cob2;
              $actividad->n9ckd1= $value->n9ckd1;
              $actividad->n9ckd2= $value->n9ckd2;
              $actividad->cusecu= $value->cusecu;
              $actividad->cupost= $value->cupost;
              $actividad->cucoon= $value->cucoon;
              $actividad->cucooe= $value->cucooe;
              $actividad->cuclas= $value->cuclas;
              $actividad->cuesta= $value->cuesta;
              $actividad->cutari= $value->cutari;
              $actividad->estado= false;
              $actividad->created_at=date('Y-m-d H:i:s');
              $actividad->referencia="sin asignar";
              if(($value->cucooe!="0" && $value->cucoon!="0") && (!is_null($value->cucooe) && !is_null($value->cucoon))){
                $coordenadas=$this->changeUtmCoordinates(str_replace(',','.',$value->cucooe),str_replace(',','.',$value->cucoon),17,false);
                if($coordenadas!=false){
                  $latitud=round($coordenadas["lat"],7);
                  $longitud=round($coordenadas["lon"],6);
                  if($this->validarCoordenada($latitud) && $this->validarCoordenada($longitud)){
                    $actividad->latitud=$latitud;
                    $actividad->longitud=$longitud;
                  }else{
                    $actividad->latitud=0;
                    $actividad->longitud=0;
                  }
                }
              }else{
                $actividad->latitud=0;
                $actividad->longitud=0;
              }
              $actividad->id_emp=$this->getIdEmpUserAuth();;
              $actividad->save();

          }
          if($con>0){
              $mesagge=true;
              return response()->json($mesagge);
          }
        } catch (\Exception $e) {
          return response()->json($e);
        }


      }else{
        $mesagge=false;
        return response()->json($mesagge);

      }
    }else{
      $mesagge=false;
      return response()->json($mesagge);

    }

  }

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
   * Display the specified resource.
   *
   * @param  \App\Models\OrdenTemp  $ordenTemp
   * @return \Illuminate\Http\Response
   */
  public function show(OrdenTemp $ordenTemp)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\OrdenTemp  $ordenTemp
   * @return \Illuminate\Http\Response
   */
  public function edit(OrdenTemp $ordenTemp)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\OrdenTemp  $ordenTemp
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, OrdenTemp $ordenTemp)
  {
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\OrdenTemp  $ordenTemp
   * @return \Illuminate\Http\Response
   */
  public function destroy(OrdenTemp $ordenTemp)
  {
      //
  }
}
