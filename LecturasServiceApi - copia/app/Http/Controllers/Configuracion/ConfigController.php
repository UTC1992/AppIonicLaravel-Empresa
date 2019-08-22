<?php
namespace App\Http\Controllers\Configuracion;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Configuracion;
use App\Traits\ApiResponser;

class ConfigController extends Controller
{
  use ApiResponser;

    public function __construct()
    {
        //
    }

    public function index($id){

      $config= Configuracion::where('idEmpresa',$id)->get();
      if(count($config)<=0){
        return response()->json("Error: No existe configuración para la empresa con ID : ".$id);
      }
      return $this->successResponse($config);
    }

    // crea una instancia de configuracion
    public function store(Request $request){
      if($this->existOrderCulumn($request->order,$request->idEmpresa)){
        return response()->json("Error: El orden de filtro ya se encuentra asignado");
      }
      if($request->key=="table"){
        if($this->existTableConfig($request->idEmpresa)){
          return response()->json("Error: El campo key con nombre tabla solo se puede ingresar una vez");
        }
      }

      $config= Configuracion::create($request->all());
      return $this->successResponse($config,Response::HTTP_CREATED);

    }

    private function existOrderCulumn($order,$idEmpresa){
      $exist=Configuracion::where('order',$order)->where('order','!=',0)->where('idEmpresa',$idEmpresa)->get();
      if(count($exist)>0){
        return true;
      }else{
        return false;
      }
    }

    private function existTableConfig($idEmpresa){
      $exist=Configuracion::where('key','table')->where('idEmpresa',$idEmpresa)->get();
      if(count($exist)>0){
        return true;
      }else{
        return false;
      }
    }
    public function show($configuracion){

    }

    /**
     * RETORNA CONFIGURACION
     */
    public function update(Request $request){
      $config= Configuracion::find($request->id);
      $config->key= $request->key;
      $config->value=$request->value;
      $config->estado=$request->estado;
      $config->filter=$request->filter;
      $config->order=$request->order;
      $configuracion= $config->save();
      return $configuracion;
    }


    public function destroy($idConfig){
      $config=new Configuracion();
      $res=$config->where('id', $idConfig);
      $r=$res->delete();
      return response()->json($r);
    }

    /**
     * create table lecturas
     */
     public function createTableLecturasEmpresa(Request $request){

       $tabla="";
       $config= new Configuracion();
       $result= $config->where("idEmpresa",$request->id)->get();
       if(count($result)<=0){
         return $this->errorResponse("No existe instancias de configuración para empresa con ID: ".$request->id,Response::HTTP_NOT_FOUND);
       }
       foreach ($result as $key => $value) {
         if($value->key=="table"){
           $tabla=$value->value;
           break;
         }
       }

       if($this->validateTableExist($tabla)){
         return $this->errorResponse("Configuracion ya ha sido creada",Response::HTTP_NOT_FOUND);
       }
       $create= new Configuracion();
       $res= $create->createTableLecturas($result);

       return $this->successResponse("Configuración generada correctamente",Response::HTTP_CREATED);
    }

    private function validateTableExist($tabla){
      try {
        $config=new Configuracion();
        $result=$config->existTable($tabla);
        return $result;
      } catch (\Exception $e) {
        return false;
      }
    }

    public function validateConfig($id){
      $tabla="";
      $config= new Configuracion();
      $result= $config->where("idEmpresa",$id)->get();
      if(count($result)>0){
        foreach ($result as $key => $value) {
          if($value->key=="table"){
            $tabla=$value->value;
            break;
          }
        }
        $config=new Configuracion();
        return response()->json($config->existTable($tabla));
      }else{
        return response()->json(false);
      }

    }

    /**
     * eliminar tabla de configuracion
     */

     public function dropTable($id){
       $tabla="";
       $config= new Configuracion();
       $result= $config->where("idEmpresa",$id)->get();
       if(count($result)>0){
         foreach ($result as $key => $value) {
           if($value->key=="table"){
             $tabla=$value->value;
             break;
           }
         }
         $config=new Configuracion();
         $config->dropTable($tabla);
         return response()->json(true);
       }else{
         return response()->json(false);
       }
     }

}
