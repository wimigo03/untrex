<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cotizaciones;
use App\Comprobantes;
use Carbon\Carbon;
use DB;

class ComprobantesController extends Controller
{
    public function index(){
        return view('comprobantes.index');
    }

    public function create(){
        $date = date('Y-m-d');
        $tipo_cambio = Cotizaciones::where('fecha',$date)->where('deleted_at',null)->first();
        if($tipo_cambio == null){
            return back()->with('danger', 'No exite un tipo de cambio para el dia de hoy...');
        }
        $nombre = auth()->user()->name;
        $user_id = auth()->user()->id;
        return view('comprobantes.create',compact('tipo_cambio','nombre','user_id'));
    }

    public function store(Request $request){//dd($request->all());
        $request->validate([
            'centro'=> 'required',
            'tipo'=> 'required',
            'fecha'=> 'required',
            'entregado_recibido'=> 'required_unless:tipo,3',
            'moneda'=> 'required',
            'taza_cambio' => 'required',
        ]);
        $fecha = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        if($fecha >= Carbon::now()->toDateString()){
            return back()->withInput()->with('danger','No puede cargar datos a una fecha futura...');
        }
        $cotizacion = Cotizaciones::where('fecha',$fecha)->first();
        if($cotizacion == null){
            return back()->withInput()->with('info','Tipo de Cambio y UFV no encontradas...');
        }
        $ultimoComprobante = Comprobantes::whereNotNull('nro_comprobante')
                                        ->where('tipo',$request->tipo)
                                        ->where('centro_id',$request->centro)
                                        ->whereMonth('fecha', date('m', strtotime($fecha)))
                                        ->whereYear('fecha', date('Y', strtotime($fecha)))
                                        ->orderBy('nro_comprobante_id','desc')
                                        ->first();
        if($ultimoComprobante == null){
            $numero = 1;
        }else{
            $numero = intval($ultimoComprobante != null ? $ultimoComprobante->nro_comprobante_id: 0) + 1;
        }
        $tipos = ['','CI','CE','CT'];
        $codigo = $tipos[$request->tipo] . '1';
        $fecha = Carbon::parse($fecha);
        $codigo = $codigo . "-" . substr($fecha->toDateString(),2,2);
        if($fecha->month < 10){
            $codigo = $codigo . '0' . $fecha->month;
        }else{
            $codigo = $codigo . $fecha->month;
        }
        $nro_comprobante = $codigo . "-" . (str_pad($numero,4,"0",STR_PAD_LEFT));
        if(isset($request->entregado_recibido)){
            $entregado_recibido = $request->entregado_recibido;
        }else{
            $entregado_recibido = null;
        }
        $comprobante = new Comprobantes();
        $comprobante->user_id = $request->user_id;
        $comprobante->centro_id = $request->centro;
        $comprobante->nro_comprobante = $nro_comprobante;
        $comprobante->nro_comprobante_id = $numero;
        $comprobante->tipo_cambio = $request->taza_cambio;
        $comprobante->ufv = $request->ufv;
        $comprobante->tipo = $request->tipo;
        $comprobante->entregado_recibido = $entregado_recibido;
        $comprobante->fecha = $fecha;
        $comprobante->moneda = $request->moneda;
        $comprobante->status = 0;
        $comprobante->status_validate = 1;
        $comprobante->save();

        return redirect()->route('comprobantesdetalles.create',$comprobante)->with('message','Los datos ingresados se guardaron correctamente...');
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
