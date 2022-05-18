<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cotizaciones;
use App\Comprobantes;
use App\ComprobantesDetalle;
use App\ComprobantesFiscales;
use App\ComprobantesFiscalesDetalle;
use App\Socios;
use Carbon\Carbon;
use DB;

class ComprobantesFiscalesController extends Controller
{
    public function index(){
        $fechaActual = Carbon::now();
        $cotizacion = Cotizaciones::where('fecha',Carbon::now()->toDateString())->first();
        if($cotizacion == null){
            return redirect()->route('cotizaciones.index')->with('message','Se debe actualizar el tipo de cambio para continuar...');    
        }
        $comprobantes_fiscales = DB::table('comprobantes_fiscales as a')
                                    ->join('socios as b','b.id','a.socio_id')
                                    ->select('a.id as comprobante_fiscal_id','a.fecha','a.nro_comprobante','a.concepto','b.empresa','a.monto','a.status')
                                    ->orderBy('a.id','desc')->paginate();
        return view('comprobantes-fiscales.index',compact('comprobantes_fiscales'));
    }

    public function show($comprobante_fiscal_id){
        $comprobante_fiscal = DB::table('comprobantes_fiscales as a')
                                ->join('users as b','b.id','a.user_id')
                                ->join('socios as c','c.id','a.socio_id')
                                ->leftjoin('users as d','d.id','a.user_autorizado_id')
                                ->where('a.id',$comprobante_fiscal_id)
                                ->select('a.id as comprobante_id','a.nro_comprobante','a.moneda','b.name as creador',
                                DB::raw("if(a.tipo = 1,'INGRESO',if(a.tipo = 2,'EGRESO','TRASPASO')) as tipo_comprobante"),
                                'a.status','a.concepto','c.empresa','d.name as autorizado','a.fecha','a.monto')
                                ->first();
        $comprobante_fiscal_detalle = DB::table('comprobantes_fiscales_detalles as a')
                                        ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                                        ->join('proyectos as c','c.id','a.proyecto_id')
                                        ->join('centros as d','d.id','a.centro_id')
                                        ->join('plan_cuentas_auxiliares as e','e.id','a.plancuentaauxiliar_id')
                                        ->select('b.codigo','b.nombre as plancuenta','c.nombre as proyecto','d.nombre as centro','e.nombre as auxiliar','a.glosa','a.debe','a.haber')
                                        ->where('a.comprobante_fiscal_id',$comprobante_fiscal_id)
                                        ->where('a.deleted_at',null)->get();
        $total_debe = $comprobante_fiscal_detalle->sum('debe');
        $total_haber = $comprobante_fiscal_detalle->sum('haber');
        return view('comprobantes-fiscales.show',compact('comprobante_fiscal','comprobante_fiscal_detalle','total_debe','total_haber'));
    }

    public function create(){
        $date = date('Y-m-d');
        $tipo_cambio = Cotizaciones::where('fecha',$date)->where('deleted_at',null)->first();
        if($tipo_cambio == null){
            return back()->with('danger', 'No exite un tipo de cambio para el dia de hoy...');
        }
        $socios = Socios::pluck('empresa','id');
        $nombre = auth()->user()->name;
        $user_id = auth()->user()->id;
        return view('comprobantes.create',compact('tipo_cambio','socios','nombre','user_id'));
    }

    public function store(Request $request){//dd($request->all());
        $request->validate([
            'socio'=> 'required',
            'tipo'=> 'required',
            'fecha'=> 'required',
            'entregado_recibido'=> 'required_unless:tipo,3',
            'moneda'=> 'required',
            'taza_cambio' => 'required',
            'copia' => 'required'
        ]);
        $fecha = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        if($fecha > Carbon::now()->toDateString()){
            return back()->withInput()->with('danger','No puede cargar datos a una fecha futura...');
        }
        $cotizacion = Cotizaciones::where('fecha',$fecha)->first();
        if($cotizacion == null){
            return back()->withInput()->with('info','Tipo de Cambio y UFV no encontradas...');
        }
        $ultimoComprobante = Comprobantes::whereNotNull('nro_comprobante')
                                        ->where('tipo',$request->tipo)
                                        ->where('socio_id',$request->socio)
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
        $comprobante->socio_id = $request->socio;
        $comprobante->nro_comprobante = $nro_comprobante;
        $comprobante->nro_comprobante_id = $numero;
        $comprobante->tipo_cambio = $request->taza_cambio;
        $comprobante->ufv = $request->ufv;
        $comprobante->tipo = $request->tipo;
        $comprobante->entregado_recibido = $entregado_recibido;
        $comprobante->fecha = $fecha;
        $comprobante->concepto = $request->concepto;
        $comprobante->moneda = $request->moneda;
        $comprobante->copia = $request->copia;
        $comprobante->status = 0;
        $comprobante->status_validate = 0;
        $comprobante->save();

        return redirect()->route('comprobantesdetalles.create',$comprobante)->with('message','Los datos ingresados se guardaron correctamente...');
    }

    public function aprobar($comprobante_id){
        /*try{
            DB::beginTransaction();*/
            $comprobante = Comprobantes::find($comprobante_id);
            $comprobante->user_autorizado_id = auth()->user()->id;;
            $comprobante->status = 1;
            $comprobante->update();
            if($comprobante->copia == '1'){
                $this->copia_comprobante($comprobante);
            }
        /*}catch (\Exception $th){
            DB::rollback(); 
            return back()->with('danger','La accion no se pudo completar... por favor revise la informacion procesada...');
        }*/
        return redirect()->route('comprobantes.index')->with('message','El comprobante '. $comprobante->nro_comprobante . ' fue aprobado...');
    }

    private function copia_comprobante($comprobante){
        $ultimoComprobante = ComprobantesFiscales::whereNotNull('nro_comprobante')
                                                ->where('tipo',$comprobante->tipo)
                                                ->where('socio_id',$comprobante->socio_id)
                                                ->whereMonth('fecha', date('m', strtotime($comprobante->fecha)))
                                                ->whereYear('fecha', date('Y', strtotime($comprobante->fecha)))
                                                ->orderBy('nro_comprobante_id','desc')
                                                ->first();
        if($ultimoComprobante == null){
            $numero = 1;
        }else{
            $numero = intval($ultimoComprobante != null ? $ultimoComprobante->nro_comprobante_id: 0) + 1;
        }
        $tipos = ['','CI','CE','CT'];
        $codigo = $tipos[$comprobante->tipo] . '0';
        $fecha = Carbon::parse($comprobante->fecha);
        $codigo = $codigo . "-" . substr($fecha->toDateString(),2,2);
        if($fecha->month < 10){
            $codigo = $codigo . '0' . $fecha->month;
        }else{
            $codigo = $codigo . $fecha->month;
        }
        $nro_comprobante = $codigo . "-" . (str_pad($numero,4,"0",STR_PAD_LEFT));
        if(isset($comprobante->entregado_recibido)){
            $entregado_recibido = $comprobante->entregado_recibido;
        }else{
            $entregado_recibido = null;
        }
        $comprobante_fiscal = new ComprobantesFiscales();
        $comprobante_fiscal->comprobante_interno_id = $comprobante->id;
        $comprobante_fiscal->user_id = $comprobante->user_id;
        $comprobante_fiscal->socio_id = $comprobante->socio_id;
        $comprobante_fiscal->nro_comprobante = $nro_comprobante;
        $comprobante_fiscal->nro_comprobante_id = $numero;
        $comprobante_fiscal->tipo_cambio = $comprobante->tipo_cambio;
        $comprobante_fiscal->ufv = $comprobante->ufv;
        $comprobante_fiscal->tipo = $comprobante->tipo;
        $comprobante_fiscal->entregado_recibido = $entregado_recibido;
        $comprobante_fiscal->fecha = $fecha;
        $comprobante_fiscal->concepto = $comprobante->concepto;
        $comprobante_fiscal->monto = $comprobante->monto;
        $comprobante_fiscal->moneda = $comprobante->moneda;
        $comprobante_fiscal->status = 1;
        $comprobante_fiscal->save();

        $comprobante_detalle = ComprobantesDetalle::where('comprobante_id',$comprobante->id)->get();
        foreach($comprobante_detalle as $datos){
            $comprobante_fiscal_detalle = new ComprobantesFiscalesDetalle();
            $comprobante_fiscal_detalle->comprobante_fiscal_id = $comprobante_fiscal->id;
            $comprobante_fiscal_detalle->plancuenta_id = $datos->plancuenta_id;
            $comprobante_fiscal_detalle->plancuentaauxiliar_id = $datos->plancuentaauxiliar_id;
            $comprobante_fiscal_detalle->proyecto_id = $datos->proyecto_id;
            $comprobante_fiscal_detalle->centro_id = $datos->centro_id;
            $comprobante_fiscal_detalle->glosa = $datos->glosa;
            $comprobante_fiscal_detalle->debe = $datos->debe;
            $comprobante_fiscal_detalle->haber = $datos->haber;
            $comprobante_fiscal_detalle->tipo_transaccion = $datos->tipo_transaccion;
            $comprobante_fiscal_detalle->cheque_nro = $datos->cheque_nro;
            $comprobante_fiscal_detalle->cheque_orden = $datos->cheque_orden;
            $comprobante_fiscal_detalle->save();
        }
    }

    public function rechazar($comprobante_id){
        $comprobante = Comprobantes::find($comprobante_id);
        $comprobante->user_autorizado_id = auth()->user()->id;;
        $comprobante->status = 2;
        $comprobante->update();
        return redirect()->route('comprobantes.index')->with('danger','El comprobante '. $comprobante->nro_comprobante . ' fue rechazado...');
    }
}
