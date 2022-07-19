<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Proyectos;
use App\TipoCambio;
use App\PlanCuentas;
use App\Comprobantes;
use App\BalanceAperturas;
use App\ComprobantesDetalle;
use Carbon\Carbon;
use DB;
use PDF;

class EstadoResultadoController extends Controller
{
    public function proyectos(){
        $proyectos = DB::table('proyectos')->where('user_id',Auth()->user()->id)->pluck('nombre','id');
        return view('estado-resultado.proyectos',compact('proyectos'));
    }
    public function index($proyecto_id){
        $proyectos = DB::table('proyectos')->where('user_id',Auth()->user()->id)->pluck('nombre','id');
        $anho_actual = date('Y');
        for($i=($anho_actual-2);$i<=($anho_actual+2);$i++){
            $gestion[$i] = $i;
        }
        return view('estado-resultado.index',compact('proyectos','proyecto_id','gestion'));
    }

    public function search(Request $request){
        if($request->gestion == null && $request->finicial == null && $request->ffinal == null){
            return back()->withInput()->with('danger','Se debe seleccionar una gestion...');
        }
        if($request->finicial != null && $request->ffinal == null){
            return back()->withInput()->with('danger','Falta la fecha final...');
        }
        if($request->finicial == null && $request->ffinal != null){
            return back()->withInput()->with('danger','Falta la fecha inicial...');
        }
        if($request->gestion != null){
            $anho = $request->gestion;
            $start_date = $anho .'-04-01';
            $end_date = ($anho + 1) .'-03-31';
        }
        if($request->finicial != null && $request->ffinal != null){
            $start_date = substr($request->finicial,6,4) . '-' . substr($request->finicial,3,2) . '-' . substr($request->finicial,0,2);
            $end_date = substr($request->ffinal,6,4) . '-' . substr($request->ffinal,3,2) . '-' . substr($request->ffinal,0,2);
            if($start_date > $end_date){
                return back()->withInput()->with('danger','Falta la fecha inicial no puede ser mayor a la fecha final...');
            }
            $start_date_carbon = Carbon::parse($start_date);
            $end_date_carbon = Carbon::parse($end_date);
            if($start_date_carbon->month >= 4){
                if(($end_date_carbon->month < 4 && $end_date_carbon->year != $start_date_carbon->year + 1) || ($end_date_carbon->month >= 4 && $end_date_carbon->year != $start_date_carbon->year)){
                    return back()->withInput()->with('danger','Debe Seleccionar fechas dentro de una sola gestion...');
                }
            }
            if($start_date_carbon->month < 4){
                if($start_date_carbon->month < 4){
                    if(($end_date_carbon->month >= 4 && $end_date_carbon->year != $start_date_carbon->year - 1) || ($end_date_carbon->month < 4 && $end_date_carbon->year != $start_date_carbon->year)){
                        return back()->withInput()->with('danger','Debe Seleccionar fechas dentro de una sola gestion...');
                    }
                }
            }
            $start_date = $start_date_carbon->toDateString();
            $end_date = $end_date_carbon->toDateString();
        }
        $status = array();
        if($request->estado != null){
            $status_text = $request->estado;
            array_push($status,$request->estado);
        }else{
            $status_text = "TODOS";
            array_push($status,1);
            array_push($status,0);
        }
        $proyectos = DB::table('proyectos')->where('user_id',Auth()->user()->id)->pluck('nombre','id');
        $proyecto_id = $request->proyecto_id;
        $ingresos = PlanCuentas::where('codigo','like','4%')
                                ->where('proyecto_id',$proyecto_id)
                                ->where('estado','1')
                                ->orderBy('codigo')
                                ->get();
        $costos = PlanCuentas::where('codigo','like','5%')
                                ->where('proyecto_id',$proyecto_id)
                                ->where('estado','1')
                                ->orderBy('codigo')
                                ->get();
        $gastos = PlanCuentas::where('codigo','like','6%')
                                ->where('proyecto_id',$proyecto_id)
                                ->where('estado','1')
                                ->orderBy('codigo')
                                ->get();
        $totales = [];
        $cuentas = array();
        $tipoOperacion = "-+";
        $planCuentaId = $ingresos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion,$totales,$cuentas);
        $tipoOperacion = "+-";
        $planCuentaId = $costos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion,$totales,$cuentas);
        $planCuentaId = $gastos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion,$totales,$cuentas);
        $nroMaxColumna = 6;
        $total = $totales[$ingresos[0]->id] - $totales[$costos[0]->id] - $totales[$gastos[0]->id];
        return view('estado-resultado.show',compact('ingresos','costos','gastos','totales','cuentas','proyectos','proyecto_id','start_date','end_date','status_text','nroMaxColumna','total'));
    }

    public function pdf(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $proyecto_id = $request->proyecto_id;
        if($request->status == "TODOS"){
            $request_status = null;
        }else{
            $request_status = $request->status;
        }
        $status = array();
        if($request_status != null){
            $status_text = $request_status;
            array_push($status,$request_status);
        }else{
            $status_text = "TODOS";
            array_push($status,1);
            array_push($status,0);
        }
        $proyecto = DB::table('proyectos')->where('id',$proyecto_id)->first();
        set_time_limit(0);ini_set('memory_limit', '1G'); 
        $ingresos = PlanCuentas::where('codigo','like','4%')
                                ->where('proyecto_id',$proyecto_id)
                                ->where('estado','1')
                                ->orderBy('codigo')
                                ->get();
        $costos = PlanCuentas::where('codigo','like','5%')
                                ->where('proyecto_id',$proyecto_id)
                                ->where('estado','1')
                                ->orderBy('codigo')
                                ->get();
        $gastos = PlanCuentas::where('codigo','like','6%')
                                ->where('proyecto_id',$proyecto_id)
                                ->where('estado','1')
                                ->orderBy('codigo')
                                ->get();
        $totales = [];
        $cuentas = array();
        $tipoOperacion = "-+";
        $planCuentaId = $ingresos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion,$totales,$cuentas);
        $tipoOperacion = "+-";
        $planCuentaId = $costos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion,$totales,$cuentas);
        $planCuentaId = $gastos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion,$totales,$cuentas);
        $nroMaxColumna = 6;
        $total = $totales[$ingresos[0]->id] - $totales[$costos[0]->id] - $totales[$gastos[0]->id];
        $pdf = PDF::loadView('estado-resultado.pdf',compact('ingresos','costos','gastos','totales','cuentas','proyecto','start_date','end_date','status_text','nroMaxColumna','total'));
        $pdf->setPaper('LETTER', 'portrait');//landscape
        return $pdf->stream('Estado_de_Resultado.pdf');
    }
    
    public function sum_total_account_gestion($plan_cuenta_id,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion, &$totales, &$cuentas){
        $totalFinal = 0;
        $planCuenta = PlanCuentas::find($plan_cuenta_id);
        if($planCuenta->cuenta_detalle == '1'){
            $comprobantes = ComprobantesDetalle::join('comprobantes as c','c.id','comprobantes_detalles.comprobante_id')
                                                ->where('comprobantes_detalles.plancuenta_id',$plan_cuenta_id)
                                                ->whereBetween('c.fecha',[$start_date,$end_date])
                                                ->where('c.proyecto_id',$proyecto_id)
                                                ->whereIn('c.status',$status)
                                                ->orderBy('comprobantes_detalles.plancuentaauxiliar_id')
                                                ->orderBy('c.fecha')
                                                ->select('c.id','c.fecha','comprobantes_detalles.plancuenta_id as idplancuenta','c.nro_comprobante','comprobantes_detalles.glosa','debe','haber','comprobantes_detalles.cheque_nro','comprobantes_detalles.cheque_orden','c.status','comprobantes_detalles.plancuentaauxiliar_id as cuentaAux')
                                                ->get();
            $total = 0;
            foreach ($comprobantes as $comp) {
                if($tipoOperacion == "-+"){
                    $total += $comp->haber;
                    $total -= $comp->debe;
                }else{
                    $total += $comp->debe;
                    $total -= $comp->haber;
                }
            }
            $totalFinal += $total;
            if(!in_array($planCuenta->id,$cuentas)){
                $totales[$planCuenta->id] = $totalFinal;
                array_push($cuentas,$planCuenta->id);
            }
        }else{
            $childs = PlanCuentas::where('parent_id',$planCuenta->id)->get();
            foreach ($childs as $child ) {
                $totalFinalActual = $this->sum_total_account_gestion($child->id,$start_date,$end_date,$status,$proyecto_id,$tipoOperacion,$totales,$cuentas);
                $totalFinal += $totalFinalActual;
                if(!in_array($child->id,$cuentas)){
                    $totales[$child->id] = $totalFinalActual;
                    array_push($cuentas,$child->id);
                }
            }
        }
        return $totalFinal;
    }
}
