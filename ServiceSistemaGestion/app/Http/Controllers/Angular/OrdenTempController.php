<?php

namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\OrdenTemp;
use App\Models\ActividadDiaria;
use Illuminate\Support\Facades\Storage;
use Excel;

class OrdenTempController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $orden=new ActividadDiaria();
    $result=$orden->where('estado',0)->get();
    return response()->json($result);
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
              $actividad->id_emp=1;
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
