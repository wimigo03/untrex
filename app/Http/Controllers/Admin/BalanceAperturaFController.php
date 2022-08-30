<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Proyectos;
use App\TipoCambio;
use App\ComprobantesFiscales;
use App\BalanceAperturasFiscales;
use App\ComprobantesFiscalesDetalle;
use Carbon\Carbon;
use DB;

class BalanceAperturaFController extends Controller
{
    public function proyectos(){
        $proyectos = DB::table('user_proyectos as a')
                            ->join('proyectos as b','b.id','a.proyecto_id')
                            ->where('a.user_id',auth()->user()->id)
                            ->where('a.estado','1')
                            ->select('b.id','b.nombre')
                            ->pluck('nombre','id');
        return view('balance-apertura-f.proyectos',compact('proyectos'));
    }
    public function index($proyecto_id){
        $proyectos = DB::table('proyectos')->where('user_id',Auth()->user()->id)->pluck('nombre','id');
        $balance_apertura = DB::table('balance_aperturas_fiscales as a')
                                ->join('comprobantes_fiscales as b','b.id','a.comprobante_fiscal_id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->select('a.id as balance_apertura_id','b.nro_comprobante','a.fecha_creacion','a.gestion','a.moneda')
                                ->get();
        return view('balance-apertura-f.index',compact('balance_apertura','proyectos','proyecto_id'));
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
        return view('balance-apertura-f.create',compact('proyectos','gestion','proyecto_id'));
    }

    public function store(Request $request){
        $request->validate([
            'proyecto' => 'required',
            'gestion' => 'required',
            'moneda' => 'required'
        ]);
        $balance_apertura = DB::table('balance_aperturas_fiscales')->where('gestion',$request->gestion)->where('moneda',$request->moneda)->where('estado',1)->first();
        if($balance_apertura != null){
            return back()->withInput()->with('info','Ya existe un balance la gestion seleccionada...');
        }
        $fecha = $request->gestion  . '-04-01';
        $cotizacion = TipoCambio::where('fecha',$fecha)->first();
        if($cotizacion == null){
            return back()->withInput()->with('info','Tipo de Cambio y UFV para el Balance de apertura no encontrado...');
        }
        DB::beginTransaction();
        try {
            $fecha = Carbon::parse($fecha);
            $nro_comprobante = 'CT0-' . substr($fecha->toDateString(),2,2) . '04-0001';
            
            $comprobante = new ComprobantesFiscales();
            $comprobante->user_id = Auth()->user()->id;
            $comprobante->proyecto_id = $request->proyecto;
            $comprobante->nro_comprobante = $nro_comprobante;
            $comprobante->nro_comprobante_id = 1;
            $comprobante->tipo_cambio = $cotizacion->dolar_oficial;
            $comprobante->ufv = $cotizacion->ufv;
            $comprobante->tipo = 3;
            $comprobante->entregado_recibido = null;
            $comprobante->fecha = $fecha;
            $comprobante->concepto = 'BALANCE DE APERTURA';
            $comprobante->moneda = $request->moneda;
            $comprobante->status = 0;
            $comprobante->save();

            $balance_apertura = new BalanceAperturasFiscales();
            $balance_apertura->proyecto_id = $request->proyecto;
            $balance_apertura->comprobante_fiscal_id = $comprobante->id;
            $balance_apertura->fecha_creacion = date('Y-m-d');
            $balance_apertura->gestion = $request->gestion;
            $balance_apertura->moneda = $request->moneda;
            $balance_apertura->estado = 1;
            $balance_apertura->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //return response()->json(['message' => 'Error']);
            return back()->withInput()->with('danger','Hay un error en el sistema, por favor llamar al encargado de desarrollo...');
        }
        return redirect()->route('balanceaperturaf.index', $request->proyecto)->with('message','Se creo un nuevo balance de apertura...');
    }

    public function editar($balance_apertura_id){
        $balance_apertura = DB::table('balance_aperturas_fiscales')->where('id',$balance_apertura_id)->first();
        $comprobante = DB::table('comprobantes_fiscales as a')
                            ->join('users as b','b.id','a.user_id')
                            ->join('proyectos as c','c.id','a.proyecto_id')
                            ->leftjoin('users as d','d.id','a.user_autorizado_id')
                            ->where('a.id',$balance_apertura->comprobante_fiscal_id)
                            ->select('a.id as comprobante_id','a.nro_comprobante','a.moneda','b.name as creador',
                                        DB::raw("if(a.tipo = 1,'INGRESO',if(a.tipo = 2,'EGRESO','TRASPASO')) as tipo_comprobante"),
                                        'a.status','a.concepto','c.nombre','d.name as autorizado','a.fecha','a.monto')
                            ->first();
        $comprobante_detalle = DB::table('comprobantes_fiscales_detalles as a')
                                    ->join('plan_cuentas as b','b.id','a.plancuenta_id')
                                    ->leftjoin('plan_cuentas_auxiliares as c','c.id','a.plancuentaauxiliar_id')
                                    ->join('centros as d','d.id','a.centro_id')
                                    ->select('a.id as comprobante_detalle_id','b.codigo','b.nombre as cuenta','c.nombre as auxiliar',
                                            'd.abreviatura as centro','a.glosa','a.debe','a.haber')
                                    ->where('comprobante_fiscal_id',$balance_apertura->comprobante_fiscal_id)
                                    ->get();
        $total_debe = $comprobante_detalle->sum('debe');
        $total_haber = $comprobante_detalle->sum('haber');
        $plan_cuentas = DB::table('plan_cuentas')->where(function ($query) {
                                            $query->where('codigo','LIKE','1%')
                                                ->orWhere('codigo','LIKE','2%')
                                                ->orWhere('codigo','LIKE','3%');
                                        })
                                        ->where('proyecto_id',$balance_apertura->proyecto_id)
                                        ->where('cuenta_detalle',1)
                                        ->where('estado','1')
                                        ->orderBy('codigo')
                                        ->pluck('nombre','id');
        $auxiliares = DB::table('plan_cuentas_auxiliares')
                                    ->where('proyecto_id',$balance_apertura->proyecto_id)
                                    ->where('estado',1)
                                    ->pluck('nombre','id');
        $centros = DB::table('centros')->where('proyecto_id',$balance_apertura->proyecto_id)->pluck('nombre','id');
        return view('balance-apertura-f.editar',compact('balance_apertura','comprobante','comprobante_detalle','total_debe','total_haber','plan_cuentas','auxiliares','centros'));
    }

    public function update(Request $request){
        $request->validate([
            'plancuenta' => 'required',
            'centro' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $comprobante_detalle = new ComprobantesFiscalesDetalle();
            $comprobante_detalle->comprobante_fiscal_id = $request->comprobante_id;
            $comprobante_detalle->plancuenta_id = $request->plancuenta;
            $comprobante_detalle->plancuentaauxiliar_id = $request->auxiliar;
            $comprobante_detalle->centro_id = $request->centro;
            $comprobante_detalle->glosa = $request->concepto;
            $comprobante_detalle->debe = $request->debe;
            $comprobante_detalle->haber = $request->haber;
            $comprobante_detalle->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //return response()->json(['message' => 'Error']);
            return back()->withInput()->with('danger','Hay un error en el sistema, por favor llamar al encargado de desarrollo...');
        }
        return back()->with('info','Los datos fueron actualizados...');
    }

    public function aprobar($balance_apertura_id){
        $balance_apertura = BalanceAperturasFiscales::find($balance_apertura_id);
        $comprobante_detalle = DB::table('comprobantes_fiscales_detalles')->where('comprobante_fiscal_id',$balance_apertura->comprobante_fiscal_id)->where('deleted_at',null)->get();
        $total_debe = $comprobante_detalle->sum('debe');
        $total_haber = $comprobante_detalle->sum('haber');
        if($total_debe == 0 && $total_haber == 0){
            return back()->withInput()->with('danger','No hay suficientes datos para procesar la peticion...');
        }
        if($total_debe != $total_haber){
            return back()->withInput()->with('danger','El debe y el haber no coinciden...');
        }
        $balance_apertura->estado = 2;
        $balance_apertura->update();
        return redirect()->route('balanceaperturaf.index', $balance_apertura->proyecto_id)->with('message','Balance de apertura aprobado...');
    }
}
