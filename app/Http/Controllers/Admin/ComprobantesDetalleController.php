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
        //
    }

    public function create(Comprobantes $comprobante){
        //dd($comprobante, "Dilson");
        $user = DB::table('users')->where('id',$comprobante->user_id)->first();
        $empresa = DB::table('empresas')->where('id',$comprobante->empresa_id)->first();
        $proyectos = DB::table('proyectos')->pluck('nombre','id');
        $centros = DB::table('centros')->pluck('nombre','id');
        $plan_cuentas = PlanCuentas::select(DB::raw("CONCAT(codigo,'&nbsp;|&nbsp;',nombre) as nombre"),'id')
                                    ->where('empresa_id',$comprobante->empresa_id)
                                    ->where('estado',1)                                    
                                    ->where('cuenta_detalle','=','1')
                                    ->pluck('nombre','id');
        $plan_cuentas_auxiliares = PlanCuentasAuxiliares::select(DB::raw("CONCAT(id,'&nbsp;|&nbsp;',IF(ISNULL(proveedor_id), IF(ISNULL(user_id),IF(ISNULL(maquinaria_id),'GEN: ','EQU: '),'PER: '), 'PROV: '),nombre) AS nombre"),'id')
        
                                    ->where('estado','1')->pluck('nombre','id');
        /*$comprobante = Comprobante::where('id',$comprobante->id)->first();
        $query = ComprobantesDetalle::where('idcomprobante',$comprobante->id);
        $plan_cuentas = PlanCuentas::select(DB::raw("CONCAT(codigo,'&nbsp;|&nbsp;',nombre) AS nombre"),'id')
                                    ->where('empresa_id',$comprobante->empresa_id)
                                    ->where('status',1)                                    
                                    ->where('cuenta_detalle','=','1')
                                    ->get()->pluck('nombre','id');
        $plan_cuentas_auxiliares = PlanCuentasAuxiliares::select(DB::raw("CONCAT(id,'&nbsp;|&nbsp;',IF(ISNULL(proveedor_id), IF(ISNULL(user_id),IF(ISNULL(maquinaria_id),'GEN: ','EQU: '),'PER: '), 'PROV: '),nombre) AS nombre"),'id')
                                                            ->where('status','1')
                                                            ->get()
                                                            ->pluck('nombre','id');
        $suma_total_debe = $query->sum('debe');
        $suma_total_haber = $query->sum('haber');
        $comprobantes_detalles = $query->orderBy('id', 'DESC')->paginate();
        $detalles = $query->get();
        $proyectos = Proyecto::where('empresa_id',$comprobante->empresa_id)->get()->pluck('name','id');
        $centros = Centro::get()->pluck('name','id');
        $relaciones = FacturaComprobanteDetalle::get();
        $array = array();
        foreach ($relaciones as $relacion) {
            foreach ($detalles as $det) {
                if($det->id == $relacion->comprobante_detalle_id){
                    array_push($array,$relacion->comprobante_detalle_id);
                }
            }
        }
        $fechaComprobante = Carbon::parse($comprobante->fecha)->format('d-m-Y');
        $empresas = Empresa::where('tipo',1)->get()->pluck('name','id');
        $datoEmpresa = Empresa::where('tipo',1)->where('id',$comprobante->empresa_id)->first();
        return view('comprobantesdetalle.create', compact('empresas','fechaComprobante','comprobante','comprobantes_detalles','plan_cuentas','plan_cuentas_auxiliares','suma_total_debe','suma_total_haber','proyectos','centros','array','datoEmpresa'));*/
        return view('comprobantes-detalles.create',compact('comprobante','user','empresa','proyectos','centros','plan_cuentas','plan_cuentas_auxiliares'));
    }

    public function insertar(Request $request){
        //dd($request->all());
        $request->validate([
            'proyecto'=> 'required',
            'centro'=> 'required',
            'plan_cuenta'=> 'required',
            'plan_cuenta_auxiliar'=> 'required',
            'glosa'=> 'required',
            'debe_bs' => 'required',
            'haber_bs' => 'required'
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

    /*public function editar($id){
        $datos = PlanCuentas::find($id);
        if($datos->cuenta_detalle == 1){
            $cuenta_detalle = "Si";
        }else{
            $cuenta_detalle = "No";
        }
        if($datos->cheque == 1){
            $cheque = "Si";
        }else{
            $cheque = "No";
        }
        $parent = PlanCuentas::find($datos->parent_id);
        return view('plandecuentas.edit',compact('datos','parent','cuenta_detalle','cheque'));
    }

    public function update(Request $request){
        
        $datos = PlanCuentas::find($request->plancuenta_id);
        $datos->nombre = $request->nombre_dependiente;
        $datos->descripcion = $request->descripcion;
        $datos->update();
        return redirect()->route('plandecuentas.index')->with('info','Plan de cuenta modificado con exito...');
    }

    public function ajaxSeleccionar($id){
        $plandecuenta = PlanCuentas::find($id);
        $plandecuentaParent = PlanCuentas::find($plandecuenta->parent_id);
        $codigo_padre = 0;
        $cuenta_detalle = "NO";
        $cheque = "NO";
        if($plandecuentaParent != null){
            $codigo_padre = $plandecuentaParent->codigo;
            if($plandecuenta->cuenta_detalle == '1'){
                $cuenta_detalle = "SI";
            }
            if($plandecuenta->cheque == '1'){
                $cheque = "SI";
            }
        }
        if($plandecuenta->count()>0){
            return response()->json([
                'id'=>$plandecuenta->id,
                'codigo_padre' => $codigo_padre,
                'codigo'=>$plandecuenta->codigo,
                'nombre'=>$plandecuenta->nombre,
                'parent_id'=>$plandecuenta->parent_id,
                'descripcion'=>$plandecuenta->descripcion,
                'cuenta_detalle'=>$cuenta_detalle,
                'cheque'=>$cheque,
            ]);
        }
        return response()->json(['error'=>'Algo Salio Mal']);
    }*/
}
