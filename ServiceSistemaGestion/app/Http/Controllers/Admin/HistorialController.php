<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Historial;
use App\Models\Empresa;
use Auth;

class HistorialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $obj2 = new Empresa();
        $empresas = $obj2->getEmpresasByAdmin(Auth::user()->id_admin);
        $historiales = [];
        return view('historial.inicio',compact('historiales', 'empresas'));
    }

    public function consultar(Request $request)
    {
        $obj2 = new Empresa();
        $empresas = $obj2->getEmpresasByAdmin(Auth::user()->id_admin);
        $historial = new Historial();
        $historiales = $historial->getByEmpresa($request);
        return view('historial.inicio',compact('historiales', 'empresas'));
    }

}
