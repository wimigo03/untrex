<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use App\TipoCambio;
use App\Comprobantes;
use App\ComprobantesDetalle;
use App\ComprobantesFiscales;
use App\ComprobantesFiscalesDetalle;
use App\Socios;
use App\Proyectos;
use Carbon\Carbon;
use DB;
use PDF;

class ComprobantesFiscalesController extends Controller
{
    public function index(){
        $fechaActual = Carbon::now();
        $cotizacion = TipoCambio::where('fecha',Carbon::now()->toDateString())->first();
        if($cotizacion == null){
            return redirect()->route('tipo_cambio.index')->with('message','Se debe actualizar el tipo de cambio para continuar...');    
        }
        $proyectos = Proyectos::pluck('nombre','id');
        $back = true;
        return view('comprobantes-fiscales.index',compact('proyectos','back'));
    }

    public function indexAjax(){
        return datatables()
            ->query(DB::table('comprobantes_fiscales as a')
            ->join('proyectos as b','b.id','a.proyecto_id')
            ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.concepto','b.abreviatura','a.monto','a.status',
                    DB::raw("if(a.status = '0','BORRADOR',if(a.status = '1', 'APROBADO','ANULADO')) as status_search"),
                    DB::raw("DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha_comprobante")))
            ->filterColumn('status_search', function($query, $keyword) {
                $sql = "if(a.status = '0','BORRADOR',if(a.status = '1', 'APROBADO', 'ANULADO'))  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->filterColumn('fecha_comprobante', function($query, $keyword) {
                $sql = "DATE_FORMAT(a.fecha,'%d/%m/%Y')  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->addColumn('btnActions','comprobantes-fiscales.partials.actions')
            ->rawColumns(['btnActions'])
            ->toJson();
    }

    public function search(Request $request){
        if($request->fecha != null){
            $fecha = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        }else{
            $fecha = null;
        }
        $proyectos = Proyectos::pluck('nombre','id');
        $comprobantes_fiscales = DB::table('comprobantes_fiscales as a')
                                    ->join('proyectos as b','b.id','a.proyecto_id')
                                    ->where('a.fecha',"LIKE",$fecha)
                                    ->where('a.nro_comprobante',"LIKE",'%' . $request->nro_comprobante . '%')
                                    ->where('b.id',"LIKE",$request->proyecto)
                                    ->where('a.tipo',"LIKE",$request->tipo)
                                    ->where('a.status',"LIKE",$request->estado)
                                    //->where('a.concepto',"LIKE","%".$request->concepto."%")
                                    ->select('a.id as comprobante_fiscal_id','a.fecha','a.nro_comprobante','a.concepto','b.abreviatura','a.monto','a.status')
                                    ->orderBy('a.id','desc')->get();
        $back = false;
        return view('comprobantes-fiscales.indexSearch',compact('comprobantes_fiscales','proyectos','back'));
    }

    public function show($comprobante_fiscal_id){
        $comprobante_fiscal = DB::table('comprobantes_fiscales as a')
                                ->join('users as b','b.id','a.user_id')
                                ->join('proyectos as c','c.id','a.proyecto_id')
                                ->leftjoin('users as d','d.id','a.user_autorizado_id')
                                ->where('a.id',$comprobante_fiscal_id)
                                ->select('a.id as comprobante_id','a.nro_comprobante','a.moneda','b.name as creador',
                                DB::raw("if(a.tipo = 1,'INGRESO',if(a.tipo = 2,'EGRESO','TRASPASO')) as tipo_comprobante"),
                                'a.status','a.concepto','c.nombre','d.name as autorizado','a.fecha','a.monto')
                                ->first();
        $comprobante_fiscal_detalle = DB::table('comprobantes_fiscales_detalles as a')
                                        ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                                        ->join('centros as d','d.id','a.centro_id')
                                        ->join('plan_cuentas_auxiliares as e','e.id','a.plancuentaauxiliar_id')
                                        ->select('b.codigo','b.nombre as plancuenta','d.nombre as centro','e.nombre as auxiliar','a.glosa','a.debe','a.haber')
                                        ->where('a.comprobante_fiscal_id',$comprobante_fiscal_id)
                                        ->where('a.deleted_at',null)
                                        ->orderBy('a.id','desc')->get();
        $total_debe = $comprobante_fiscal_detalle->sum('debe');
        $total_haber = $comprobante_fiscal_detalle->sum('haber');
        return view('comprobantes-fiscales.show',compact('comprobante_fiscal','comprobante_fiscal_detalle','total_debe','total_haber'));
    }

    public function aprobar($comprobante_id){
        /*try{
            DB::beginTransaction();*/
            $comprobante_detalle = ComprobantesFiscalesDetalle::where('comprobante_fiscal_id',$comprobante_id)->get();
            $total_debe = $comprobante_detalle->sum('debe');
            $total_haber = $comprobante_detalle->sum('haber');
            if($total_debe != $total_haber){
                return back()->withInput()->with('danger','Imposible Aprobar el comprobante. El total debe y haber no son iguales...');
            }
            $comprobante = ComprobantesFiscales::find($comprobante_id);
            $comprobante->user_autorizado_id = auth()->user()->id;;
            $comprobante->status = 1;
            $comprobante->update();
            /*if($comprobante->copia == '1'){
                $this->copia_comprobante($comprobante);
            }*/
        /*}catch (\Exception $th){
            DB::rollback(); 
            return back()->with('danger','La accion no se pudo completar... por favor revise la informacion procesada...');
        }*/
        return redirect()->route('comprobantes.fiscales.index')->with('message','El comprobante '. $comprobante->nro_comprobante . ' fue aprobado...');
    }

    public function rechazar($comprobante_id){
        $comprobante = ComprobantesFiscales::find($comprobante_id);
        $comprobante->user_autorizado_id = auth()->user()->id;;
        $comprobante->status = 2;
        $comprobante->update();
        return redirect()->route('comprobantes.fiscales.index')->with('danger','El comprobante '. $comprobante->nro_comprobante . ' fue rechazado...');
    }

    public function pdf($comprobante_id){
        set_time_limit(0);ini_set('memory_limit', '1G');
        $comprobante = DB::table('comprobantes_fiscales as a')
                    ->join('users as b','b.id','a.user_id')
                    ->join('proyectos as c','c.id','a.proyecto_id')
                    ->leftjoin('users as d','d.id','a.user_autorizado_id')
                    ->where('a.id',$comprobante_id)
                    ->select('a.id as comprobante_id','a.nro_comprobante','a.moneda','b.name as creador',
                    DB::raw("if(a.tipo = 1,'INGRESO',if(a.tipo = 2,'EGRESO','TRASPASO')) as tipo_comprobante"),
                    'a.status','a.concepto','c.nombre','d.name as autorizado','a.fecha','a.monto','a.tipo_cambio','a.ufv','a.entregado_recibido')
                    ->first();
        if($comprobante->status == 0){
            $estado = 'PENDIENTE';
        }else{
            if($comprobante->status == 1){
                $estado = 'APROBADO';
            }else{
                $estado = 'ANULADO';
            }   
        }
        $comprobante_detalle = DB::table('comprobantes_fiscales_detalles as a')
                            ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                            ->join('centros as d','d.id','a.centro_id')
                            ->leftjoin('plan_cuentas_auxiliares as e','e.id','a.plancuentaauxiliar_id')
                            ->select('b.codigo','b.nombre as plancuenta','d.nombre as centro','e.nombre as auxiliar','a.glosa','a.debe',
                                    'a.haber','e.id as plancuentaauxiliar_id','a.cheque_nro','d.abreviatura as ab_centro')
                            ->where('a.comprobante_fiscal_id',$comprobante_id)
                            ->where('a.deleted_at',null)
                            ->orderBy('a.id','desc')->get();
        $total_debe = $comprobante_detalle->sum('debe');
        $total_haber = $comprobante_detalle->sum('haber');
        $numberLetras = new NumeroALetras();
        $monto_total = number_format($total_debe,2,',','.');
        $monto_total_letras = $numberLetras->toInvoice($total_debe, 2, 'Bolivianos');
        $i_f = 2;
        $detalles_comprobantes_cheques = ComprobantesFiscalesDetalle::where('comprobante_fiscal_id',$comprobante_id)->where('cheque_nro','<>','')->orderBy('debe','desc')->get();
        $pdf = PDF::loadView('comprobantes.pdf',compact(['comprobante','comprobante_detalle','total_debe','total_haber','estado','i_f','detalles_comprobantes_cheques','monto_total','monto_total_letras']));
        $pdf->setPaper('LETTER', 'portrait');//landscape
        return $pdf->stream();
    }
}
