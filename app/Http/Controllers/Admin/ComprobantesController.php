<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
//use App\Cotizaciones;
use App\TipoCambio;
use App\Proyectos;
use App\Comprobantes;
use App\ComprobantesDetalle;
use App\ComprobantesFiscales;
use App\ComprobantesFiscalesDetalle;
use App\Socios;
use Carbon\Carbon;
use DB;
use PDF;

class ComprobantesController extends Controller
{
    public function index(){
        $cotizacion = TipoCambio::where('fecha',Carbon::now()->toDateString())->first();
        if($cotizacion == null && Auth()->user()->id != 1){
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

    public function indexAjax(){
        return datatables()
            ->query(DB::table('comprobantes as a')
            ->join('proyectos as b','b.id','a.proyecto_id')
            ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.concepto','b.abreviatura',
                    DB::raw("FORMAT(a.monto, 2) as monto"),
                    'a.status','a.copia',
                    DB::raw("if(a.status = '0','BORRADOR',if(a.status = '1', 'APROBADO','ANULADO')) as status_search"),
                    DB::raw("DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha_comprobante")))
            ->filterColumn('monto', function($query, $keyword) {
                $sql = "FORMAT(a.monto, 2) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->filterColumn('status_search', function($query, $keyword) {
                $sql = "if(a.status = '0','BORRADOR',if(a.status = '1', 'APROBADO', 'ANULADO'))  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->filterColumn('fecha_comprobante', function($query, $keyword) {
                $sql = "DATE_FORMAT(a.fecha,'%d/%m/%Y')  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->addColumn('btnActions','comprobantes.partials.actions')
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
        $comprobantes = DB::table('comprobantes as a')
                    ->join('proyectos as b','b.id','a.proyecto_id')
                    ->where('a.fecha',"LIKE",$fecha)
                    ->where('a.nro_comprobante',"LIKE",'%' . $request->nro_comprobante . '%')
                    ->where('b.id',"LIKE",$request->proyecto)
                    ->where('a.tipo',"LIKE",$request->tipo)
                    ->where('a.status',"LIKE",$request->estado)
                    //->where('a.concepto',"LIKE","%".$request->concepto."%")
                    ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.concepto','b.abreviatura','a.monto','a.status','a.copia')
                    ->orderBy('a.id','desc')->get();
        $back = false;
        return view('comprobantes.indexSearch',compact('comprobantes','proyectos','back'));
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
        $comprobante_fiscal = DB::table('comprobantes_fiscales')->where('comprobante_interno_id',$comprobante_id)->first();
        $total_debe = $comprobante_detalle->sum('debe');
        $total_haber = $comprobante_detalle->sum('haber');
        return view('comprobantes.show',compact('comprobante','comprobante_detalle','comprobante_fiscal','total_debe','total_haber'));
    }

    public function create(){
        $date = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$date)->where('deleted_at',null)->first();
        if($tipo_cambio == null){
            return back()->with('danger', 'No exite un tipo de cambio para el dia de hoy...');
        }
        $proyectos = Proyectos::pluck('nombre','id');
        $nombre = auth()->user()->name;
        $user_id = auth()->user()->id;
        return view('comprobantes.create',compact('tipo_cambio','proyectos','nombre','user_id'));
    }

    public function store(Request $request){
        $request->validate([
            'proyecto'=> 'required',
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
        $cotizacion = TipoCambio::where('fecha',$fecha)->first();
        if($cotizacion == null){
            return back()->withInput()->with('info','Tipo de Cambio y UFV no encontradas...');
        }
        $ultimoComprobante = Comprobantes::whereNotNull('nro_comprobante')
                                        ->where('tipo',$request->tipo)
                                        ->where('proyecto_id',$request->proyecto)
                                        ->whereMonth('fecha', date('m', strtotime($fecha)))
                                        ->whereYear('fecha', date('Y', strtotime($fecha)))
                                        ->orderBy('nro_comprobante_id','desc')
                                        ->first();
        if($ultimoComprobante == null){
            if(date('m', strtotime($fecha)) == '04' && $request->tipo == 3){
                $numero = 2;
            }else{
                $numero = 1;
            }
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
        $comprobante->proyecto_id = $request->proyecto;
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
        $comprobante->save();

        return redirect()->route('comprobantesdetalles.create',$comprobante)->with('message','Los datos ingresados se guardaron correctamente...');
    }

    public function editar($comprobante_id){
        $comprobante = Comprobantes::where('id',$comprobante_id)->first();
        $date = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$date)->where('deleted_at',null)->first();
        if($tipo_cambio == null){
            return back()->with('danger', 'No exite un tipo de cambio para el dia de hoy...');
        }
        $proyectos = Proyectos::pluck('nombre','id');
        $nombre = auth()->user()->name;
        $user_id = auth()->user()->id;
        return view('comprobantes.editar',compact('comprobante','tipo_cambio','proyectos','nombre','user_id'));
    }

    public function update(Request $request){
        $request->validate([
            'tipo_2'=> 'required',
            'fecha_2'=> 'required',
            'entregado_recibido'=> 'required_unless:tipo_2,3'
        ]);
        $fecha_2 = substr($request->fecha_2,6,4) . '-' . substr($request->fecha_2,3,2) . '-' . substr($request->fecha_2,0,2);
        if($fecha_2 > Carbon::now()->toDateString()){
            return back()->withInput()->with('danger','No puede cargar datos a una fecha futura...');
        }
        $cotizacion = TipoCambio::where('fecha',$fecha_2)->first();
        if($cotizacion == null){
            return back()->withInput()->with('info','Tipo de Cambio y UFV no encontradas...');
        }
        $comprobante_1 = Comprobantes::find($request->comprobante_id);
        if(($request->fecha_1 == $fecha_2) && ($request->tipo_1 == $request->tipo_2)){
           $comprobante_1->entregado_recibido = $request->entregado_recibido;
           $comprobante_1->concepto = $request->concepto;
           $comprobante_1->update();
           return redirect()->route('comprobantesdetalles.create',$comprobante_1)->with('message','Los datos ingresados se actualizaron correctamente...');
        }else{
            $ultimoComprobante = Comprobantes::whereNotNull('nro_comprobante')
                                                ->where('tipo',$request->tipo_2)
                                                ->where('proyecto_id',$comprobante_1->proyecto_id)
                                                ->whereMonth('fecha', date('m', strtotime($fecha_2)))
                                                ->whereYear('fecha', date('Y', strtotime($fecha_2)))
                                                ->orderBy('nro_comprobante_id','desc')
                                                ->first();
            if($ultimoComprobante == null){
                $numero = 1;
            }else{
                $numero = intval($ultimoComprobante != null ? $ultimoComprobante->nro_comprobante_id: 0) + 1;
            }
            $tipos = ['','CI','CE','CT'];
            $codigo = $tipos[$request->tipo_2] . '1';
            $fecha = Carbon::parse($fecha_2);
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
            $comprobante_2 = new Comprobantes();
            $comprobante_2->user_id = $comprobante_1->user_id;
            $comprobante_2->proyecto_id = $comprobante_1->proyecto_id;
            $comprobante_2->nro_comprobante = $nro_comprobante;
            $comprobante_2->nro_comprobante_id = $numero;
            $comprobante_2->tipo_cambio = $cotizacion->dolar_oficial;
            $comprobante_2->ufv = $cotizacion->ufv;
            $comprobante_2->tipo = $request->tipo_2;
            $comprobante_2->entregado_recibido = $entregado_recibido;
            $comprobante_2->fecha = $fecha;
            $comprobante_2->concepto = $request->concepto;
            $comprobante_2->moneda = $comprobante_1->moneda;
            $comprobante_2->copia = $comprobante_1->copia;
            $comprobante_2->status = 0;
            $comprobante_2->save();

            $comprobante_detalle = ComprobantesDetalle::where('comprobante_id',$request->comprobante_id)->get();
            foreach($comprobante_detalle as $datos){
                $comprobanteDetalle = new ComprobantesDetalle();
                $comprobanteDetalle->comprobante_id = $comprobante_2->id;
                $comprobanteDetalle->plancuenta_id = $datos->plancuenta_id;
                $comprobanteDetalle->plancuentaauxiliar_id = $datos->plancuentaauxiliar_id;
                $comprobanteDetalle->centro_id = $datos->centro_id;
                $comprobanteDetalle->glosa = strtoupper($datos->glosa);
                $comprobanteDetalle->debe = $datos->debe;
                $comprobanteDetalle->haber = $datos->haber;
                $comprobanteDetalle->tipo_transaccion = $datos->tipo_transaccion;
                $comprobanteDetalle->cheque_nro = $datos->cheque_nro;
                $comprobanteDetalle->cheque_orden = $datos->cheque_orden;
                $comprobanteDetalle->save();
            }
            $comprobante_1->concepto = 'ANULADO POR EDICION DE CABECERA EL NUEVO COMPROBANTE ES ' . $comprobante_2->nro_comprobante;
            $comprobante_1->status = 2;
            $comprobante_1->update();
            return redirect()->route('comprobantesdetalles.create',$comprobante_2)->with('message','Los datos ingresados se actualizaron correctamente...');
        }
    }

    public function aprobar($comprobante_id){
        /*try{
            DB::beginTransaction();*/
            $comprobante_detalle = DB::table('comprobantes_detalles')->where('comprobante_id',$comprobante_id)->where('deleted_at',null)->get();
            if($comprobante_detalle->count() == 0){
                return back()->withInput()->with('danger','Imposible continuar. El detalle del comprobante esta vacio...');
            }
            $comprobante_detalle = ComprobantesDetalle::where('comprobante_id',$comprobante_id)->get();
            $total_debe = round($comprobante_detalle->sum('debe'),2);
            $total_haber = round($comprobante_detalle->sum('haber'),2);
            if($total_debe != $total_haber){
                return back()->withInput()->with('danger','Imposible Aprobar el comprobante. El total debe y haber no son iguales...');
            }
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
                                                ->where('proyecto_id',$comprobante->proyecto_id)
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
        $comprobante_fiscal->proyecto_id = $comprobante->proyecto_id;
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
        $comprobante_fiscal->status = 0;
        $comprobante_fiscal->save();

        $comprobante_detalle = ComprobantesDetalle::where('comprobante_id',$comprobante->id)->get();
        foreach($comprobante_detalle as $datos){
            $comprobante_fiscal_detalle = new ComprobantesFiscalesDetalle();
            $comprobante_fiscal_detalle->comprobante_fiscal_id = $comprobante_fiscal->id;
            $comprobante_fiscal_detalle->plancuenta_id = $datos->plancuenta_id;
            $comprobante_fiscal_detalle->plancuentaauxiliar_id = $datos->plancuentaauxiliar_id;
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

    public function pdf($comprobante_id){
        //dd($id);
        //echo $numberLetras->toWords($number);
        //echo $numberLetras->toWords($decimal);
        //echo $numberLetras->toInvoice($decimal, 2, 'Bolivianos');
        set_time_limit(0);ini_set('memory_limit', '1G');
        $comprobante = DB::table('comprobantes as a')
                    ->join('users as b','b.id','a.user_id')
                    ->join('proyectos as c','c.id','a.proyecto_id')
                    ->leftjoin('users as d','d.id','a.user_autorizado_id')
                    ->where('a.id',$comprobante_id)
                    ->select('a.id as comprobante_id','a.nro_comprobante','a.moneda','b.name as creador',
                                DB::raw("if(a.tipo = 1,'INGRESO',if(a.tipo = 2,'EGRESO','TRASPASO')) as tipo_comprobante"),
                                'a.status','a.concepto','c.nombre','d.name as autorizado','a.fecha','a.copia','a.monto','a.tipo_cambio','a.ufv','a.entregado_recibido')
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
        $comprobante_detalle = DB::table('comprobantes_detalles as a')
                            ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                            ->leftjoin('centros as d','d.id','a.centro_id')
                            ->leftjoin('plan_cuentas_auxiliares as e','e.id','a.plancuentaauxiliar_id')
                            ->select('b.codigo','b.nombre as plancuenta','d.nombre as centro','e.nombre as auxiliar','a.glosa','a.debe',
                                    'a.haber','e.id as plancuentaauxiliar_id','a.cheque_nro','d.abreviatura as ab_centro')
                            ->where('a.comprobante_id',$comprobante_id)
                            ->where('a.deleted_at',null)
                            ->orderBy('a.id','asc')->get();
        $total_debe = $comprobante_detalle->sum('debe');
        $total_haber = $comprobante_detalle->sum('haber');
        $numberLetras = new NumeroALetras();
        $monto_total = number_format($total_debe,2,',','.');
        $monto_total_letras = $numberLetras->toInvoice($total_debe, 2, 'Bolivianos');
        $i_f = 1;
        $detalles_comprobantes_cheques = ComprobantesDetalle::where('comprobante_id',$comprobante_id)->where('cheque_nro','<>','')->orderBy('debe','desc')->get();
        $pdf = PDF::loadView('comprobantes.pdf',compact(['comprobante','comprobante_detalle','total_debe','total_haber','estado','i_f','detalles_comprobantes_cheques','monto_total','monto_total_letras']));
        $pdf->setPaper('LETTER', 'portrait');//landscape
        return $pdf->stream();
    }
}
