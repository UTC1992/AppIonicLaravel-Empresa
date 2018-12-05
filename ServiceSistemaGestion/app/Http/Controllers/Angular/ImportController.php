<?php
namespace App\Http\Controllers\Angular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadDiaria;
use Illuminate\Support\Facades\Storage;
use Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\IWriter;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{

    public function __construct(){
        //$this->middleware('auth:api');
    }
    public function index(){
      return view('import');
    }
    // exportar excel consolidado
    public function exportExcelConsolidado($date,$id_emp){
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
                'cutari')->where('created_at','like','%'.$date.'%')->where('id_emp',$id_emp)->get();

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->setTitle('CONSOLIDADO');
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A1",'N9SEPR')
                        ->setCellValue("B1",'N9CONO')
                        ->setCellValue("C1",'N9COCU')
                        ->setCellValue("D1",'N9SELO')
                        ->setCellValue("E1",'N9COZO')
                        ->setCellValue("F1",'N9COAG')
                        ->setCellValue("G1",'N9COSE')
                        ->setCellValue("H1",'N9CORU')
                        ->setCellValue("I1",'N9SERU')
                        ->setCellValue("J1",'N9VANO')
                        ->setCellValue("K1",'N9PLVE')
                        ->setCellValue("L1",'N9VACA')
                        ->setCellValue("M1",'N9ESTA')
                        ->setCellValue("N1",'N9COCN')
                        ->setCellValue("O1",'N9FECH')
                        ->setCellValue("P1",'N9MECO')
                        ->setCellValue("Q1",'N9SERI')
                        ->setCellValue("R1",'N9FECO')
                        ->setCellValue("S1",'N9LECO')
                        ->setCellValue("T1",'N9MANP')
                        ->setCellValue("U1",'N9COCL')
                        ->setCellValue("V1",'N9NOMB')
                        ->setCellValue("W1",'N9CEDU')
                        ->setCellValue("X1",'N9PRIN')
                        ->setCellValue("Y1",'N9NRPR')
                        ->setCellValue("Z1",'N9REFE')
                        ->setCellValue("AA1",'N9TELE')
                        ->setCellValue("AB1",'N9MEDI')
                        ->setCellValue("AC1",'N9FECL')
                        ->setCellValue("AD1",'N9LECT')
                        ->setCellValue("AE1",'N9COBS')
                        ->setCellValue("AF1",'N9COB2')
                        ->setCellValue("AG1",'N9CKD1')
                        ->setCellValue("AH1",'N9CKD2')
                        ->setCellValue("AI1",'CUSECU')
                        ->setCellValue("AJ1",'CUPOST')
                        ->setCellValue("AK1",'CUCOON')
                        ->setCellValue("AL1",'CUCOOE')
                        ->setCellValue("AM1",'CUCLAS')
                        ->setCellValue("AN1",'CUESTA')
                        ->setCellValue("AO1",'CUTARI');
            $x= 2;
            foreach($data as $item){

                $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$x",$item['n9sepr'])
                        ->setCellValue("B$x",$item['n9cono'])
                        ->setCellValue("C$x",$item['n9cocu'])
                        ->setCellValue("D$x",$item['n9selo'])
                        ->setCellValue("E$x",$item['n9cozo'])
                        ->setCellValue("F$x",$item['n9coag'])
                        ->setCellValue("G$x",$item['n9cose'])
                        ->setCellValue("H$x",$item['n9coru'])
                        ->setCellValue("I$x",$item['n9seru'])
                        ->setCellValue("J$x",$item['n9vano'])
                        ->setCellValue("K$x",$item['n9plve'])
                        ->setCellValue("L$x",$item['n9vaca'])
                        ->setCellValue("M$x",$item['n9esta'])
                        ->setCellValue("N$x",$item['n9cocn'])
                        ->setCellValue("O$x",$item['n9fech'])
                        ->setCellValue("P$x",$item['n9meco'])
                        ->setCellValue("Q$x",$item['n9seri'])
                        ->setCellValue("R$x",$item['n9feco'])
                        ->setCellValue("S$x",$item['n9leco'])
                        ->setCellValue("T$x",$item['n9manp'])
                        ->setCellValue("U$x",$item['n9cocl'])
                        ->setCellValue("V$x",$item['n9nomb'])
                        ->setCellValue("W$x",$item['n9cedu'])
                        ->setCellValue("X$x",$item['n9prin'])
                        ->setCellValue("Y$x",$item['n9nrpr'])
                        ->setCellValue("Z$x",$item['n9refe'])
                        ->setCellValue("AA$x",$item['n9tele'])
                        ->setCellValue("AB$x",$item['n9medi'])
                        ->setCellValue("AC$x",$item['n9fecl'])
                        ->setCellValue("AD$x",$item['n9lect'])
                        ->setCellValue("AE$x",$item['n9cobs'])
                        ->setCellValue("AF$x",$item['n9cob2'])
                        ->setCellValue("AG$x",$item['n9ckd1'])
                        ->setCellValue("AH$x",$item['n9ckd2'])
                        ->setCellValue("AI$x",$item['cusecu'])
                        ->setCellValue("AJ$x",$item['cupost'])
                        ->setCellValue("AK$x",$item['cucoon'])
                        ->setCellValue("AL$x",$item['cucooe'])
                        ->setCellValue("AM$x",$item['cuclas'])
                        ->setCellValue("AN$x",$item['cuesta'])
                        ->setCellValue("AO$x",$item['cutari']);

                $spreadsheet->getActiveSheet()->setCellValueExplicit(
                            'H'.$x,
                            $item['n9coru'],
                            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                        );
                $spreadsheet->getActiveSheet()->setCellValueExplicit(
                            'Y'.$x,
                            $item['n9nrpr'],
                            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                        );
                $spreadsheet->getActiveSheet()->setCellValueExplicit(
                            'Q'.$x,
                            $item['n9seri'],
                            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                        );
                $x++;

            }
            //nombre del EXCEL descargado
            $vector = explode("-",$date);
            $fileName = $vector[2]."-".$vector[1]."-".$vector[0].'_CONSOLIDADO';

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$fileName.".".$type);
            $writer->save("php://output");

            } catch (\Exception $e) {
            return response()->json($e);
          }

    }
}
