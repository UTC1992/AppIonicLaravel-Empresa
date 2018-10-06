<?php

namespace App\Http\Controllers\Angular;

use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\OrdenTemp;
use App\Models\OrdenTrabajo;
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
        $res=$tecnico->create($input);
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

    //tecnicos sin asignar actividades
    public function getTecnicosSinActividades(){
      $tecnico=new Tecnico();
      $result=$tecnico->where('asignado',0)->get();
      return response()->json($result);
    }

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
      if(count($array_id_tecnicos)>0){
        $likeValue="";
        $actividad="";
        if($tipo=="10"){
          $likeValue="10%";
          $actividad="Notificación";
        }
        if($tipo=="30"){
          $likeValue="30%";
          $actividad="Corte";
        }
        if($tipo=="40"){
          $likeValue="40%";
          $actividad="Reconexión";
        }
        $contador=0;
        $num=new ActividadDiaria();
        $num_total_actividades=$num->where('estado',0)->where('n9cono','like',''.$likeValue.'')->count();
        $num_act_by_tec=$num_total_actividades/count($array_id_tecnicos);
            $i=0;
            $orden=new ActividadDiaria();
            $result=$orden->where('estado',0)->where('n9cono','like',''.$likeValue.'')->get();
            if(count($result)>0){
              foreach ($result as $key => $value) {

                $ordenTrabajo=new OrdenTrabajo();
                $ordenTrabajo->id_tecn=$array_id_tecnicos[$i];
                $ordenTrabajo->id_act=$value->id_act;
                $ordenTrabajo->estado=0;
                $ordenTrabajo->fecha=date('Y-m-d');
                $ordenTrabajo->observacion="Orden de trabajo pendiente ".$actividad;
                $ordenTrabajo->tipo_actividad=$tipo;
                $ordenTrabajo->save();

                $ordenProc=ActividadDiaria::find($value->id_act);
                $ordenProc->estado=1;
                $ordenProc->referencia="Asignado";
                $ordenProc->save();
                if($contador==ceil($num_act_by_tec)){
                  $i++;
                  $contador=0;
                }
                $contador++;
              }

              for ($x=0; $x < count($array_id_tecnicos); $x++) {
                  $tecnico=Tecnico::find($array_id_tecnicos[$x]);
                  $tecnico->asignado=1;
                  $tecnico->save();
              }
              return response()->json(true);
            }else{
              return response()->json(false);
            }


      }else{
        return response()->json("1");
      }

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
