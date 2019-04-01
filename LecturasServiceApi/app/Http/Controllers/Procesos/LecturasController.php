<?php
namespace App\Http\Controllers\Procesos;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Configuracion;
use App\Traits\ApiResponser;

class LecturasController extends Controller
{
  use ApiResponser;

    public function __construct()
    {

    }

    public function index(Request $request){
      try {
        

      } catch (\Exception $e) {

      }


    }

    // crea una instancia de configuracion
    public function store(Request $request){

    }

    public function show($configuracion){

    }
    public function update(Request $request, $empresa){

    }
    public function destroy($empresa){

    }



}
