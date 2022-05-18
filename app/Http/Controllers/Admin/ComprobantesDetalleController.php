<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cotizaciones;
use App\Comprobantes;
use App\ComprobantesDetalle;
use App\PlanCuentas;
use App\PlanCuentasAuxiliares;
use Carbon\Carbon;
use DB;

class ComprobantesDetalleController extends Controller
{
    public function index(){
        //index
    }

    public function create(Comprobantes $comprobante){
        $user = DB::table('users')->where('id',$comprobante->user_id)->first();
        $socio = DB::table('socios')->where('id',$comprobante->socio_id)->first();
        $proyectos = DB::table('proyectos')->pluck('nombre','id');
        $centros = DB::table('centros')->pluck('nombre','id');
        $plan_cuentas = PlanCuentas::select(DB::raw("CONCAT(codigo,'&nbsp;|&nbsp;',nombre) as nombre"),'id')
                                    ->where('socio_id',$comprobante->socio_id)
                                    ->where('estado',1)                                    
                                    ->where('cuenta_detalle','1')
                                    ->pluck('nombre','id');
        $plan_cuentas_auxiliares = PlanCuentasAuxiliares::where('estado',1)->pluck('nombre','id');
        $comprobante_detalle = DB::table('comprobantes_detalles as a')
                                ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                                ->join('proyectos as c','c.id','a.proyecto_id')
                                ->join('centros as d','d.id','a.centro_id')
                                ->join('plan_cuentas_auxiliares as e','e.id','a.plancuentaauxiliar_id')
                                ->select('b.codigo','b.nombre as plancuenta','c.nombre as proyecto','d.nombre as centro','e.nombre as auxiliar','a.glosa','a.debe','a.haber')
                                ->where('a.comprobante_id',$comprobante->id)
                                ->where('a.deleted_at',null)->get();
        $total_debe = $comprobante_detalle->sum('debe');
        $total_haber = $comprobante_detalle->sum('haber');
        return view('comprobantes-detalles.create',compact('comprobante','user','socio','proyectos','centros','plan_cuentas','plan_cuentas_auxiliares','comprobante_detalle','total_debe','total_haber'));
    }

    public function getPlanCuenta($id){
        $plan_cuenta = PlanCuentas::where('id',$id)->first();
        if($plan_cuenta){            
            return response()->json($plan_cuenta);
        } else return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function insertar(Request $request){
        $request->validate([
            'proyecto'=> 'required',
            'centro'=> 'required',
            'plan_cuenta'=> 'required',
            'plan_cuenta_auxiliar'=> 'required',
            'glosa'=> 'required',
            'debe_bs'=> 'required_without:haber_bs',
            'haber_bs'=> 'required_without:debe_bs'
        ]);

        $comprobanteDetalle = new ComprobantesDetalle();
        $comprobanteDetalle->comprobante_id = $request->comprobante_id;
        $comprobanteDetalle->plancuenta_id = $request->plan_cuenta;
        $comprobanteDetalle->plancuentaauxiliar_id = $request->plan_cuenta_auxiliar;
        $comprobanteDetalle->proyecto_id = $request->proyecto;
        $comprobanteDetalle->centro_id = $request->centro;
        $comprobanteDetalle->glosa = $request->glosa;
        $comprobanteDetalle->debe = $request->debe_bs;
        $comprobanteDetalle->haber = $request->haber_bs;
        $comprobanteDetalle->tipo_transaccion = $request->tipo_transaccion;
        $comprobanteDetalle->cheque_nro = $request->cheque_nro;
        $comprobanteDetalle->cheque_orden = $request->cheque_orden;
        $comprobanteDetalle->save();
        
        return redirect()->route('comprobantesdetalles.create',$request->comprobante_id)->with('message','Los datos ingresados se guardaron correctamente...');
    }

    public function finalizar(Request $request){
        //dd($request->all());
        if($request->total_debe != $request->total_haber){
            return back()->withInput()->with('danger','El total debe y haber no son iguales...');
        }
        $total = $request->total_debe;
        $comprobante = Comprobantes::find($request->comprobante_id);
        $comprobante->monto = $total;
        $comprobante->status_validate = 1;
        $comprobante->update();
        return redirect()->route('comprobantes.index')->with('message','El comprobante '. $comprobante->nro_comprobante . ' fue registrado con exito...');
    }
}
