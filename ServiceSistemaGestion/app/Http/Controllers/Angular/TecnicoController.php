<?php

namespace App\Http\Controllers\Angular;

use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\OrdenTemp;
use App\Models\ActividadDiaria;

class TecnicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $result=Tecnico::all();
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
        $input=$request->all();
        $tecnico=new Tecnico();
        $res=$tecnico->insert($input);
        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function show(Tecnico $tecnico)
    {

    }

    /**
    *
    */

    public function delete($id_tecnico){
      $tecnico=new Tecnico();
      $result=$tecnico->where('id_tecn',$id_tecnico)->delete();
      if($result>0){
        return response()->json("Borrado");
      }else{
          return response()->json("No se borro");
      }

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

    }
    public function editTecnicoAngular(Request $request){
      $tecnico=Tecnico::find($request->id_tecn);
      $result=$tecnico->update($request->all());
      return response()->json($result);
    }

    public function getTecnicoById($id_tecnico){
      $tecnico=Tecnico::find($id_tecnico);
      return response()->json($tecnico);
    }

    public function buildTaskTecnicos($tipo,$cadena){
      $array_id_tecnicos=explode('&&',$cadena);
      $likeValue="";
      if($tipo=="10"){
        $likeValue="%10%";
      }
      if($tipo=="30"){
        $likeValue="%30%";
      }
      if($tipo=="40"){
        $likeValue="%40%";
      }
      $contador=0;
      $num=new ActividadDiaria();
      $num_total_actividades=$num->where('estado',0)->where('n9cono','like',''.$likeValue.'')->count();
      $num_act_by_tec=$num_total_actividades/count($array_id_tecnicos);
          $i=0;
          $orden=new ActividadDiaria();
          $result=$orden->where('estado',0)->where('n9cono','like',''.$likeValue.'')->get();
          foreach ($result as $key => $value) {
            $temp=new OrdenTemp();
            $temp->n9cono=$value->n9cono;
            $temp->n9cocu=$value->n9cocu;
            $temp->n9cose=$value->n9cose;
            $temp->n9coru=$value->n9coru;
            $temp->n9meco=$value->n9meco;
            $temp->n9leco=$value->n9leco;
            $temp->n9cocl=$value->n9cocl;
            $temp->n9nomb=$value->n9nomb;
            $temp->n9refe=$value->n9refe;
            $temp->cusecu=$value->cusecu;
            $temp->cucoon=$value->cucoon;
            $temp->observacion="tarea temporal";
            $temp->estado=0;
            $temp->fecha=date('Y-m-d');
            $temp->hora=date('H:i:s');
            $temp->id_tecn=$array_id_tecnicos[$i];
            $temp->save();
            $ordenProc=ActividadDiaria::find($value->id_act);
            $ordenProc->estado=1;
            $ordenProc->referencia="Asignado";
            $ordenProc->save();
            if($contador>ceil($num_act_by_tec)){
              $i++;
              $contador=0;
            }
            $contador++;
          }
      return response()->json(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tecnico $tecnico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tecnico $tecnico)
    {

    }
}
