<?php

namespace App\Http\Controllers\Gateway\Lecturas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LecturasAppService;

class LecturasAppController extends Controller
{
  //inject lecturas service
    public $lecturasAppServices;

    public function __construct(LecturasAppService $lecturasAppServices){
    //  $this->middleware('auth:api');
      $this->lecturasAppServices=$lecturasAppServices;
    }

    /**
     * obtiene rutas de los técnicos
     */
    public function index($idEmpresa,$idTecnico){
      try {
        $result=$this->lecturasAppServices->getDataDistributionByTecnicoService($idEmpresa,$idTecnico);
        return response($result);
      } catch (\Exception $e) {
        return response()->json("Error :".$e);
      }

    }





}
