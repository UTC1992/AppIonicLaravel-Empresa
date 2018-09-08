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
        foreach ($data as $key => $value) {
          $actividades_list[] = [
            'n9sepr'=> $value->n9sepr,
            'n9cono'=> $value->n9cono,
            'n9cocu'=> $value->n9cocu,
            'n9selo'=> $value->n9selo,
            'n9cozo'=> $value->n9cozo,
            'n9coag'=> $value->n9coag,
            'n9cose'=> $value->n9cose,
            'n9coru'=> $value->n9coru,
            'n9seru'=> $value->n9seru,
            'n9vano'=> $value->n9vano,
            'n9plve'=> $value->n9plve,
            'n9vaca'=> $value->n9vaca,
            'n9esta'=> $value->n9esta,
            'n9cocn'=> $value->n9cocn,
            'n9fech'=> $value->n9fech,
            'n9meco'=> $value->n9meco,
            'n9seri'=> $value->n9seri,
            'n9feco'=> $value->n9feco,
            'n9leco'=> $value->n9leco,
            'n9manp'=> $value->n9manp,
            'n9cocl'=> $value->n9cocl,
            'n9nomb'=> $value->n9nomb,
            'n9cedu'=> $value->n9cedu,
            'n9prin'=> $value->n9prin,
            'n9nrpr'=> $value->n9nrpr,
            'n9refe'=> $value->n9refe,
            'n9tele'=> $value->n9tele,
            'n9medi'=> $value->n9medi,
            'n9fecl'=> $value->n9fecl,
            'n9lect'=> $value->n9lect,
            'n9cobs'=> $value->n9cobs,
            'n9cob2'=> $value->n9cob2,
            'n9ckd1'=> $value->n9ckd1,
            'n9ckd2'=> $value->n9ckd2,
            'cusecu'=> $value->cusecu,
            'cupost'=> $value->cupost,
            'cucoon'=> $value->cucoon,
            'cucooe'=> $value->cucooe,
            'cuclas'=> $value->cuclas,
            'cuesta'=> $value->cuesta,
            'cutari'=> $value->cutari,
            'estado'=> false,
            'referencia'=>"archivo csv",
            'id_emp'=>1
          ];
        }
        if(!empty($actividades_list)){
            ActividadDiaria::insert($actividades_list);
            $mesagge="Ok";
            $response=response()->json($mesagge);
            return $response;
        }
      }else{
        $mesagge="Fail";
        $response=response()->json($mesagge);
        return $response;
      }
    }else{
      $mesagge="Don't search files";
      $response=response()->json($mesagge);
      return $response;
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
