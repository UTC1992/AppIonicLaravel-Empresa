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
      try {

        $tecnico=new Tecnico();
        $tecnico->nombres=$request->nombres;
        $tecnico->apellidos=$request->nombres;
        $tecnico->cedula=$request->cedula;
        $tecnico->telefono=$request->telefono;
        $tecnico->email=$request->email;
        $tecnico->estado=$request->estado;
        $tecnico->asignado=0;
        $tecnico->borrado=0;
        $tecnico->save();
        if($tecnico){
          return response()->json(true);
        }else{
          return response()->json(false);
        }
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }



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

    public function buildTaskTecnicos(Request $request){
      try {
        if($request->cantidad_actividades<=0 || $request->cantidad_actividades==null){
          return response()->json(1);
        }
        $cont=0;
        $contador=0;
        $array_actividades=$request->array_actividades;
        $array_tecnicos=$request->array_tecnicos;
        $cantidad_distribuir=ceil($request->cantidad_actividades/count($array_tecnicos));
        for ($i=0; $i <$request->cantidad_actividades; $i++) {
          //ingresa  orden trabajo
          $ordenTrabajo=new OrdenTrabajo();
          $ordenTrabajo->id_tecn=$array_tecnicos[$cont];
          $ordenTrabajo->id_act=$array_actividades[$i];
          $ordenTrabajo->estado=0;
          $ordenTrabajo->fecha=date('Y-m-d');
          $ordenTrabajo->observacion="Orden de trabajo asignado";
          $ordenTrabajo->tipo_actividad=$request->actividad;
          $ordenTrabajo->save();
          // actualiza actividad diaria asignado
          $ordenProc=ActividadDiaria::find($array_actividades[$i]);
          $ordenProc->estado=1;
          $ordenProc->referencia="Asignado";
          $ordenProc->save();
          $contador++;
          if($contador==$cantidad_distribuir){
            $cont++;
            $contador=0;
          }
        }
        // asigna técnico
        for ($j=0; $j < count($array_tecnicos); $j++) {
          $tecnico=Tecnico::find($array_tecnicos[$j]);
          $tecnico->asignado=1;
          $tecnico->save();
        }

        return response()->json(true);

      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
      }

    }
    // asignar nueva tarea Tecnico
    public function changeStateTecnico($id){
      try {
        $tecnico=Tecnico::find($id);
        $tecnico->asignado=0;
        $tecnico->save();
        if($tecnico){
          return response()->json(true);
        }
      } catch (\Exception $e) {
        return response()->json("Error: ".$e);
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
