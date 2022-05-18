<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Cotizaciones;
//use App\Comprobantes;
//use App\ComprobantesDetalle;
//use App\PlanCuentas;
//use App\PlanCuentasAuxiliares;
use Carbon\Carbon;
use DB;

class FacturasController extends Controller
{
    public function index(){
        //index
    }

    public function comprobanteCreate($comprobante_id){
        /*dd($comprobante_id);
        /*$user = DB::table('users')->where('id',$comprobante->user_id)->first();
        $socio = DB::table('socios')->where('id',$comprobante->socio_id)->first();
        $proyectos = DB::table('proyectos')->pluck('nombre','id');
        $centros = DB::table('centros')->pluck('nombre','id');*/
        $proveedores = DB::table('plan_cuentas_auxiliares')->where('tipo',1)->where('estado',1)->pluck('nombre','id');
    return view('comprobantes-facturas.create',compact('proveedores'));
    }
}
