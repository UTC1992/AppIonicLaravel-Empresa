<?php

namespace App\Http\Controllers\Gateway\Lecturas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LecturasService;
use Auth;

class ConfigController extends Controller
{

    /**
     * Inject lecturas services
     */
    public $lecturasServices;


    public function  __construct(LecturasService $lecturasServices){
      $this->middleware('auth:admin');
      $this->lecturasService=$lecturasServices;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
      try {
        $configCompany=$this->lecturasService->getConfigCompany($id);
        return   response()->json(json_decode($configCompany));
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }


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
          $data=$request->all();
          $result=$this->lecturasService->createNewConfigCompany($data);
          return response()->json($result);
        } catch (\Exception $e) {
          return response()->json("Error :".$e);
        }

    }

  public function crear(Request $request){
    try {
      $data=$request->all();
      $result=$this->lecturasService->createNewConfigCompany($data);
      return response()->json($result);
    } catch (\Exception $e) {
      return response()->json("Error :".$e);
    }
  }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      try {
        $data=$request->all();
        $result=$this->lecturasService->updateConfigCompany($data);
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try {
        $result=$this->lecturasService->deleteConfigCompany($id);
        return response()->json($result);
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }
    }

    /**
     * crear confguración
     */
     public function crearConfiguracion(Request $request){
       try {
         $data=$request->all();
         $result=$this->lecturasService->configGenerateCompany($data);
         return response()->json($result);
       } catch (\Exception $e) {
         return response()->json("La configuracion ya ha sido generada");
       }
     }

     public function validateTableConfig($id){
       try {
         $result=$this->lecturasService->validarConfiguracion($id);
         return response()->json($result);
       } catch (\Exception $e) {
         return response()->json("Error :".$e);
       }
     }

     public function dropTable($id){
       try {
         $result=$this->lecturasService->dropTable($id);
         return response()->json($result);
       } catch (\Exception $e) {
         return response()->json("Error :".$e);
       }
     }
}
