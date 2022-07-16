<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Proyectos;
use App\TipoCambio;
use App\Comprobantes;
use App\BalanceAperturas;
use App\ComprobantesDetalle;
use Carbon\Carbon;
use DB;

class BalanceAperturaController extends Controller
{
    public function proyectos(){
        $proyectos = DB::table('proyectos')->where('user_id',Auth()->user()->id)->pluck('nombre','id');
        return view('balance-apertura.proyectos',compact('proyectos'));
    }
    public function index($proyecto_id){
        $proyectos = DB::table('proyectos')->where('user_id',Auth()->user()->id)->pluck('nombre','id');
        $balance_apertura = DB::table('balance_aperturas as a')
                                ->join('comprobantes as b','b.id','a.comprobante_id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->select('a.id as balance_apertura_id','b.nro_comprobante','a.fecha_creacion','a.gestion','a.moneda')
                                ->get();
        return view('balance-apertura.index',compact('balance_apertura','proyectos','proyecto_id'));
    }

    public function search(Request $request){
        dd($request->all());
        $proveedor = DB::table('proveedores as a')
                        ->where('a.razon_social','like','%' . $request->razon_social .'%')
                        ->select('a.id as proveedor_id','a.razon_social','a.nombre_comercial','a.nit','a.ciudad')
                        ->get();
    }

    public function create($proyecto_id){
        $anho_actual = date('Y');
        for($i=($anho_actual-2);$i<=($anho_actual+2);$i++){
            $gestion[$i] = $i;
        }
        $proyectos = Proyectos::pluck('nombre','id');
        return view('balance-apertura.create',compact('proyectos','gestion','proyecto_id'));
    }

    public function store(Request $request){
        $request->validate([
            'proyecto' => 'required',
            'gestion' => 'required',
            'moneda' => 'required'
        ]);
        $fecha = $request->gestion  . '-04-01';
        $cotizacion = TipoCambio::where('fecha',$fecha)->first();
        if($cotizacion == null){
            return back()->withInput()->with('info','Tipo de Cambio y UFV para el Balance de apertura no encontrado...');
        }
        DB::beginTransaction();
        try {   
            $fecha = Carbon::parse($fecha);
            $nro_comprobante = 'CT1-' . substr($fecha->toDateString(),2,2) . '04-0001';
            
            $comprobante = new Comprobantes();
            $comprobante->user_id = Auth()->user()->id;
            $comprobante->proyecto_id = $request->proyecto;
            $comprobante->nro_comprobante = $nro_comprobante;
            $comprobante->nro_comprobante_id = 1;
            $comprobante->tipo_cambio = $cotizacion->dolar_oficial;
            $comprobante->ufv = $cotizacion->ufv;
            $comprobante->tipo = 3;
            $comprobante->entregado_recibido = null;
            $comprobante->fecha = $fecha;
            $comprobante->concepto = 'ASIENTO DE APERTURA';
            $comprobante->moneda = $request->moneda;
            $comprobante->copia = 1;
            $comprobante->status = 0;
            $comprobante->save();

            $activos = DB::table('plan_cuentas')->where('codigo','like','1%')
                                                ->where('proyecto_id',$request->proyecto)
                                                ->where('cuenta_detalle',1)
                                                ->where('estado','1')
                                                ->orderBy('codigo')
                                                ->get();
            foreach($activos as $datos){
                $comprobante_detalle = new ComprobantesDetalle();
                $comprobante_detalle->comprobante_id = $comprobante->id;
                $comprobante_detalle->plancuenta_id = $datos->id;
                $comprobante_detalle->glosa = 'ASIENTO DE APERTURA';
                $comprobante_detalle->save();
            }
            $pasivos = DB::table('plan_cuentas')->where('codigo','like','2%')
                                                ->where('proyecto_id',$request->proyecto)
                                                ->where('cuenta_detalle',1)
                                                ->where('estado','1')
                                                ->orderBy('codigo')
                                                ->get();
            foreach($pasivos as $datos){
                $comprobante_detalle = new ComprobantesDetalle();
                $comprobante_detalle->comprobante_id = $comprobante->id;
                $comprobante_detalle->plancuenta_id = $datos->id;
                $comprobante_detalle->glosa = 'ASIENTO DE APERTURA';
                $comprobante_detalle->save();
            }
            $patrimonios = DB::table('plan_cuentas')->where('codigo','like','3%')
                                                ->where('proyecto_id',$request->proyecto)
                                                ->where('cuenta_detalle',1)
                                                ->where('estado','1')
                                                ->orderBy('codigo')
                                                ->get();
            foreach($patrimonios as $datos){
                $comprobante_detalle = new ComprobantesDetalle();
                $comprobante_detalle->comprobante_id = $comprobante->id;
                $comprobante_detalle->plancuenta_id = $datos->id;
                $comprobante_detalle->glosa = 'ASIENTO DE APERTURA';
                $comprobante_detalle->save();
            }

            $balance_apertura = new BalanceAperturas();
            $balance_apertura->proyecto_id = $request->proyecto;
            $balance_apertura->comprobante_id = $comprobante->id;
            $balance_apertura->fecha_creacion = date('Y-m-d');
            $balance_apertura->gestion = $request->gestion;
            $balance_apertura->moneda = $request->moneda;
            $balance_apertura->base = 1;
            $balance_apertura->estado = 1;
            $balance_apertura->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //return response()->json(['message' => 'Error']);
            return back()->withInput()->with('danger','Hay un error en el sistema, por favor llamar al encargado de desarrollo...');
        }
        return redirect()->route('balanceapertura.index', $request->proyecto)->with('message','Se creo un nuevo balance de apertura...');
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

    public function editar($balance_apertura_id){
        $balance_apertura = DB::table('balance_aperturas')->where('id',$balance_apertura_id)->first();
        $comprobante = DB::table('comprobantes as a')
                            ->join('users as b','b.id','a.user_id')
                            ->join('proyectos as c','c.id','a.proyecto_id')
                            ->leftjoin('users as d','d.id','a.user_autorizado_id')
                            ->where('a.id',$balance_apertura->comprobante_id)
                            ->select('a.id as comprobante_id','a.nro_comprobante','a.moneda','b.name as creador',
                                        DB::raw("if(a.tipo = 1,'INGRESO',if(a.tipo = 2,'EGRESO','TRASPASO')) as tipo_comprobante"),
                                        'a.status','a.concepto','c.nombre','d.name as autorizado','a.fecha','a.copia','a.monto')
                            ->first();
        $comprobante_detalle = DB::table('comprobantes_detalles as a')
                                    ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                                    ->select('a.id as comprobante_detalle_id','b.codigo','b.nombre as cuenta','a.plancuentaauxiliar_id as auxiliar',
                                            'a.centro_id as centro','a.glosa','a.debe','a.haber')
                                    ->where('comprobante_id',$balance_apertura->comprobante_id)
                                    ->get();
        $auxiliares = DB::table('plan_cuentas_auxiliares')
                                    ->where('proyecto_id',$balance_apertura->proyecto_id)
                                    ->where('estado',1)
                                    ->pluck('nombre','id');
        $centros = DB::table('centros')->where('proyecto_id',$balance_apertura->proyecto_id)->pluck('abreviatura','id');
        return view('balance-apertura.editar',compact('balance_apertura','comprobante','comprobante_detalle','auxiliares','centros'));
    }

    public function update(Request $request){
        DB::beginTransaction();
        try {
            $cont = 0;
            while($cont < count($request->comprobante_detalle_id)){
                $comprobante_detalle = ComprobantesDetalle::find($request->comprobante_detalle_id[$cont]);
                $comprobante_detalle->plancuentaauxiliar_id = $request->auxiliar_id[$cont];
                $comprobante_detalle->centro_id = $request->centro_id[$cont];
                $comprobante_detalle->glosa = $request->glosa[$cont];
                $comprobante_detalle->debe = $request->debe[$cont];
                $comprobante_detalle->haber = $request->haber[$cont];
                $comprobante_detalle->update();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //return response()->json(['message' => 'Error']);
            return back()->withInput()->with('danger','Hay un error en el sistema, por favor llamar al encargado de desarrollo...');
        }
        return back()->with('info','Los datos fueron actualizados...');
    }
}
