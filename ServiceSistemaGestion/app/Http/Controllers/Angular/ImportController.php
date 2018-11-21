<?php

namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadDiaria;
use Illuminate\Support\Facades\Storage;
use Excel;
class ImportController extends Controller
{

  public function __construct(){
    //$this->middleware('auth:api');
  }
    public function index(){
      return view('import');
    }
    // exportar excel consolidado
    public function exportExcelConsolidado($date){
    $type="xlsx";
      try {
        $res = new ActividadDiaria();
        $data=$res->select('n9sepr',
        'n9cono',
        'n9cocu',
        'n9selo',
        'n9cozo',
        'n9coag',
        'n9cose',
        'n9coru',
        'n9seru',
        'n9vano',
        'n9plve',
        'n9vaca',
        'n9esta',
        'n9cocn',
        'n9fech',
        'n9meco',
        'n9seri',
        'n9feco',
        'n9leco',
        'n9manp',
        'n9cocl',
        'n9nomb',
        'n9cedu',
        'n9prin',
        'n9nrpr',
        'n9refe',
        'n9tele',
        'n9medi',
        'n9fecl',
        'n9lect',
        'n9cobs',
        'n9cob2',
        'n9ckd1',
        'n9ckd2',
        'cusecu',
        'cupost',
        'cucoon',
        'cucooe',
        'cuclas',
        'cuesta',
        'cutari')->where('estado','=',3)->where('created_at','like','%'.$date.'%')->get();
  	    return Excel::create('Consolidado-'.$date, function($excel) use ($data) {
  			$excel->sheet('mySheet', function($sheet) use ($data)
  	        {
  				$sheet->fromArray($data);
  	        });
  		})->download($type);
      } catch (\Exception $e) {
        return response()->json($e);
      }

    }
}
