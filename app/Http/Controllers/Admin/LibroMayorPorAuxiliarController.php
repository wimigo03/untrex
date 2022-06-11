<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
//use App\TipoCambio;
//use App\Proyectos;
//use App\Comprobantes;
//use App\ComprobantesDetalle;
//use App\ComprobantesFiscales;
//use App\ComprobantesFiscalesDetalle;
use Carbon\Carbon;
use DB;
use PDF;

class LibroMayorPorAuxiliarController extends Controller
{
    public function index(){dd("ok");
        $cotizacion = TipoCambio::where('fecha',Carbon::now()->toDateString())->first();
        if($cotizacion == null){
            return redirect()->route('tipo_cambio.index')->with('message','Se debe actualizar el tipo de cambio para continuar...');    
        }
        $proyectos = Proyectos::pluck('nombre','id');
        $comprobantes = DB::table('comprobantes as a')
                    ->join('proyectos as b','b.id','a.proyecto_id')
                    ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.concepto','b.abreviatura','a.monto','a.status','a.copia')
                    ->orderBy('a.id','desc')->paginate(10);
        $back = true;
        return view('comprobantes.index',compact('comprobantes','proyectos','back'));
    }

    public function show($comprobante_id){
        $comprobante = DB::table('comprobantes as a')
                    ->join('users as b','b.id','a.user_id')
                    ->join('proyectos as c','c.id','a.proyecto_id')
                    ->leftjoin('users as d','d.id','a.user_autorizado_id')
                    ->where('a.id',$comprobante_id)
                    ->select('a.id as comprobante_id','a.nro_comprobante','a.moneda','b.name as creador',
                    DB::raw("if(a.tipo = 1,'INGRESO',if(a.tipo = 2,'EGRESO','TRASPASO')) as tipo_comprobante"),
                    'a.status','a.concepto','c.nombre','d.name as autorizado','a.fecha','a.copia','a.monto')
                    ->first();
        $comprobante_detalle = DB::table('comprobantes_detalles as a')
                            ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                            ->join('centros as d','d.id','a.centro_id')
                            ->leftjoin('plan_cuentas_auxiliares as e','e.id','a.plancuentaauxiliar_id')
                            ->select('b.codigo','b.nombre as plancuenta','d.nombre as centro','e.nombre as auxiliar','a.glosa','a.debe','a.haber')
                            ->where('a.comprobante_id',$comprobante_id)
                            ->where('a.deleted_at',null)
                            ->orderBy('a.id','desc')->get();
        $total_debe = $comprobante_detalle->sum('debe');
        $total_haber = $comprobante_detalle->sum('haber');
        return view('comprobantes.show',compact('comprobante','comprobante_detalle','total_debe','total_haber'));
    }
}
