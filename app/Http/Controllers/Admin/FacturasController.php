<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Cotizaciones;
use App\Comprobantes;
use App\ComprobantesDetalle;
use App\PlanCuentas;
use App\PlanCuentasAuxiliares;
use App\Proveedores;
use App\Facturas;
use App\ComprobanteFacturas;
use Carbon\Carbon;
use DB;

class FacturasController extends Controller
{
    public function index(){
    //dd("index");
    return view('facturas.index'/*,compact('comprobante')*/);
    }

    public function indexAjax(){
        return datatables()
            ->query(DB::table('facturas as a')
            ->join('proveedores as b','b.id','a.proveedor_id')
            ->join('comprobante_facturas as c','c.factura_id','a.id')
            ->join('socios as d','d.id','a.socio_id')
            ->select('a.id as factura_id','b.razon_social','a.numero',
                    DB::raw("DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha"),'a.glosa',
                    DB::raw("FORMAT(a.monto, 2) as monto"),
                    DB::raw("if(c.estado = '1','VALIDO','ANULADO') as estado_search"),
                    'd.abreviatura as socio'))
            ->filterColumn('fecha', function($query, $keyword) {
                    $sql = "DATE_FORMAT(a.fecha,'%d/%m/%Y')  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
            ->filterColumn('monto', function($query, $keyword) {
                $sql = "FORMAT(a.monto, 2) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->filterColumn('estado_search', function($query, $keyword) {
                $sql = "if(c.estado = '1','VALIDO','ANULADO')  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            //->addColumn('btnActions','comprobantes.partials.actions')
            //->rawColumns(['btnActions'])
            ->toJson();
    }

    public function create($comprobante_id){
        $comprobante = DB::table('comprobantes')->where('id',$comprobante_id)->first();
        //dd($comprobante);
        //$socio = 0;//DB::table('socios')->where('id',$comprobante->socio_id)->first();
        //$proyectos = DB::table('proyectos')->pluck('nombre','id');
        //$centros = DB::table('centros')->pluck('nombre','id');
        $proveedores = DB::table('proveedores')->where('nombre_comercial','!=','S/N')->where('status',1)->where('deleted_at',null)->pluck('nombre_comercial','id');
        /*$plan_cuentas_1 = PlanCuentas::select(DB::raw("CONCAT(codigo,'&nbsp;|&nbsp;',nombre) as nombre"),'id')
                                    ->where('estado',1)                                    
                                    ->where('cuenta_detalle','1')
                                    ->pluck('nombre','id');
        $plan_cuentas_iva = PlanCuentas::select(DB::raw("CONCAT(codigo,'&nbsp;|&nbsp;',nombre) as nombre"),'id')
                                        ->where('estado',1)                                    
                                        ->where('cuenta_detalle','1')
                                        ->where(function ($query) {
                                            $query->where('id', 31)
                                                ->orWhere('id', 177);
                                        })
                                        ->pluck('nombre','id');
        $plan_cuentas_2 = PlanCuentas::select(DB::raw("CONCAT(codigo,'&nbsp;|&nbsp;',nombre) as nombre"),'id')
                                    ->where('estado',1)                                    
                                    ->where('cuenta_detalle','1')
                                    ->pluck('nombre','id');
        $plan_cuentas_auxiliares = PlanCuentasAuxiliares::where('estado',1)->pluck('nombre','id');*/
        $proyecto = DB::table('proyectos as a')->where('id',$comprobante->proyecto_id)->where('deleted_at',null)->first();
        $socios = DB::table('socios as a')->where('consorcio_id',$proyecto->consorcio_id)->where('deleted_at',null)->pluck('nombre','id');
        $facturas = DB::table('facturas as a')
                        ->join('socios as b','b.id','a.socio_id')
                        ->select('a.id as factura_id','b.abreviatura','a.fecha','a.nro_dui','a.nit','a.razon_social','a.numero','a.nro_autorizacion','a.cod_control','a.monto','a.excento','a.descuento')
                        ->where('a.proyecto_id',$comprobante->proyecto_id)
                        ->where('a.deleted_at',null)->get();
        return view('facturas.create',compact('comprobante','proveedores','facturas','socios'));
    }

    public function getProveedor($id){
        $proveedores = Proveedores::find($id);
        if($proveedores){
            return $proveedores;
        }else{
            return response()->json(['error'=>'Algo Salio Mal']);
        }
    }

    public function store(Request $request){
        $request->validate([
            'numero'=> 'required|numeric',
            'monto'=> 'required|numeric',
            'cod_control'=> 'nullable',
            'proveedor'=> 'required',
            'fecha'=> 'required',
            'nit'=> 'required',
            //'nro_dui'=> 'string|max:60',
            'nro_autorizacion'=> 'required|string|max:60',
            'razon_social'=> 'required',
            //'proyecto'=> 'required',
            //'plan_cuenta_debe'=> 'required',
            //'plan_cuenta_auxiliar'=> 'required',
            //'centro'=> 'required',
            'excento'=> 'numeric|nullable',
            'descuento'=> 'numeric|nullable',
            'socio'=> 'required',
            //'tipo'=> 'required',
            //'estado'=> 'required_if:tipo,VENTA'
        ]);
        //dd($request->all());
        $fecha_factura = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        $fecha_actual = date('Y-m-d');
        if($fecha_factura > $fecha_actual){
            return back()->withInput()->with('danger','No se pueden cargar facturas con fecha adelantada...');
        }

        /*$estado = null;
        if($request->tipo == 'COMPRA'){
            $tipo = 1;
        }else{
            $tipo = 2;
            if($request->estado == 'V'){
                $estado = 1;
            }else{
                if($request->estado == 'A'){
                    $estado = 2;
                }else{
                    $estado = 3;
                }
            }
        }*/

        $duplicado = Facturas::where('fecha',$fecha_factura)->where('numero',$request->numero)->where('nit',$request->nit)->where('nro_autorizacion',$request->nro_autorizacion)->first();
        if($duplicado != null){
            return back()->withInput()->with('danger','La factura que quiere declarar ya existe...');
        }
        
        $factura = new Facturas();
        $factura->socio_id = $request->socio;
        $factura->proyecto_id = $request->proyecto_id;
        $factura->proveedor_id = $request->proveedor;
        $factura->fecha = $fecha_factura;
        $factura->nit = $request->nit;
        $factura->razon_social = $request->razon_social;
        $factura->numero = $request->numero;
        $factura->nro_dui = $request->nro_dui;//Preguntar por el numero de dui
        $factura->nro_autorizacion = $request->nro_autorizacion;
        $factura->cod_control = $request->cod_control;
        $factura->monto = $request->monto;
        $factura->excento = $request->excento;
        $factura->descuento = $request->descuento;
        $factura->procedencia = 1; // 1 es de comprobante
        //$factura->tipo = $tipo;
        //$factura->estado = $estado;
        //$factura->comprobante_id = $request->comprobante_id;
        //$factura->plancuenta_id = $request->plan_cuenta_debe;
        //$factura->plancuentaauxiliar_id = $request->plan_cuenta_auxiliar;
        //$factura->proyecto_id = $request->proyecto;
        //$factura->centro_id = $request->centro;
        $factura->glosa = $request->glosa;
        $factura->save();

        $comprobanteFactura = new ComprobanteFacturas();
        $comprobanteFactura->comprobante_id = $request->comprobante_id; 
        $comprobanteFactura->factura_id = $factura->id;
        $comprobanteFactura->estado = 1;
        $comprobanteFactura->save();

        return back()->with('info','Factura registrada exitosamente...');
    }

    public function delete($factura_id){
        $factura = Facturas::find($factura_id);
        $factura->delete();
        $comprobanteFactura = ComprobanteFacturas::find($factura->id);
        $comprobanteFactura->delete();
        return back()->with('info','Se elimino una factura...');
    }

    //Esta funcion no se esta ocupando porque se cambio la estructura de las facturas
    public function store_factura(Request $request){
        //dd($request->all());
        $request->validate([
            'plan_cuenta_iva'=> 'required',
            'plan_cuenta_haber'=> 'required'
        ]);
        $comprobante = Comprobantes::where('id',$request->comprobante_id)->first();
        $facturas = Facturas::where('comprobante_id',$request->comprobante_id)->where('deleted_at',null)->get();
        //$cont = $facturas->count();
        //dd($cont);
        /*try{
            DB::beginTransaction();*/
            foreach($facturas as $factura){
                //Sin excento y sin descuento
                if((floatVal($factura->excento) == 0) && (floatVal($factura->descuento) == 0)){
                    echo "caso 1 <br>";
                    //Credito Fiscal
                    $comprobante_cf = new ComprobantesDetalle();
                    $comprobante_cf->comprobante_id = $comprobante->id;
                    $comprobante_cf->plancuenta_id = $request->plan_cuenta_iva;
                    $comprobante_cf->plancuentaauxiliar_id = null;
                    $comprobante_cf->proyecto_id = $factura->proyecto_id;
                    $comprobante_cf->centro_id = $factura->centro_id;
                    $comprobante_cf->glosa = $factura->glosa;
                    $comprobante_cf->debe = round(floatval($factura->monto) * 0.13,2);
                    $comprobante_cf->haber = 0;
                    $comprobante_cf->save();
                    
                    //Haber
                    $comprobante_haber = new ComprobantesDetalle();
                    $comprobante_haber->comprobante_id = $comprobante->id;
                    $comprobante_haber->plancuenta_id = $request->plan_cuenta_haber;
                    $comprobante_haber->plancuentaauxiliar_id = null;
                    $comprobante_haber->proyecto_id = $factura->proyecto_id;
                    $comprobante_haber->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_haber->glosa = $factura->glosa;
                    $comprobante_haber->debe = 0;
                    $comprobante_haber->haber = $factura->monto;
                    $comprobante_haber->save();

                    //Debe
                    $comprobante_debe = new ComprobantesDetalle();
                    $comprobante_debe->comprobante_id = $comprobante->id;
                    $comprobante_debe->plancuenta_id = $factura->plancuenta_id;
                    $comprobante_debe->plancuentaauxiliar_id = $factura->plancuentaauxiliar_id;
                    $comprobante_debe->proyecto_id = $factura->proyecto_id;
                    $comprobante_debe->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_debe->glosa = $factura->glosa;
                    $comprobante_debe->debe = round(floatval($factura->monto) * 0.87,2);
                    $comprobante_debe->haber = 0;
                    $comprobante_debe->save();
                }
                //Con descuento y sin excento
                if(floatVal($factura->excento) == 0 && floatVal($factura->descuento) != 0){
                    echo "caso 2 <br>";
                    //Credito Fiscal
                    $comprobante_cf = new ComprobantesDetalle();
                    $comprobante_cf->comprobante_id = $comprobante->id;
                    $comprobante_cf->plancuenta_id = $request->plan_cuenta_iva;
                    $comprobante_cf->plancuentaauxiliar_id = null;
                    $comprobante_cf->proyecto_id = $factura->proyecto_id;
                    $comprobante_cf->centro_id = $factura->centro_id;
                    $comprobante_cf->glosa = $factura->glosa;
                    $comprobante_cf->debe = round(floatval($factura->monto) * 0.13,2);
                    $comprobante_cf->haber = 0;
                    $comprobante_cf->save();
                    
                    //Haber
                    $comprobante_haber = new ComprobantesDetalle();
                    $comprobante_haber->comprobante_id = $comprobante->id;
                    $comprobante_haber->plancuenta_id = $request->plan_cuenta_haber;
                    $comprobante_haber->plancuentaauxiliar_id = null;
                    $comprobante_haber->proyecto_id = $factura->proyecto_id;
                    $comprobante_haber->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_haber->glosa = $factura->glosa;
                    $comprobante_haber->debe = 0;
                    $comprobante_haber->haber = $factura->monto;
                    $comprobante_haber->save();

                    //Debe
                    $comprobante_debe = new ComprobantesDetalle();
                    $comprobante_debe->comprobante_id = $comprobante->id;
                    $comprobante_debe->plancuenta_id = $factura->plancuenta_id;
                    $comprobante_debe->plancuentaauxiliar_id = $factura->plancuentaauxiliar_id;
                    $comprobante_debe->proyecto_id = $factura->proyecto_id;
                    $comprobante_debe->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_debe->glosa = $factura->glosa;
                    $comprobante_debe->debe = round(floatval($factura->monto) * 0.87,2);
                    $comprobante_debe->haber = 0;
                    $comprobante_debe->save();
                }
                //Con excento sin descuento
                if(floatVal($factura->excento) != 0 && floatVal($factura->descuento) == 0){
                    echo "caso 3 <br>";
                    //Credito Fiscal
                    $comprobante_cf = new ComprobantesDetalle();
                    $comprobante_cf->comprobante_id = $comprobante->id;
                    $comprobante_cf->plancuenta_id = $request->plan_cuenta_iva;
                    $comprobante_cf->plancuentaauxiliar_id = null;
                    $comprobante_cf->proyecto_id = $factura->proyecto_id;
                    $comprobante_cf->centro_id = $factura->centro_id;
                    $comprobante_cf->glosa = $factura->glosa;
                    $comprobante_cf->debe = round(floatval($factura->monto) * 0.13,2);
                    $comprobante_cf->haber = 0;
                    $comprobante_cf->save();
                    
                    //Haber
                    $comprobante_haber = new ComprobantesDetalle();
                    $comprobante_haber->comprobante_id = $comprobante->id;
                    $comprobante_haber->plancuenta_id = $request->plan_cuenta_haber;
                    $comprobante_haber->plancuentaauxiliar_id = null;
                    $comprobante_haber->proyecto_id = $factura->proyecto_id;
                    $comprobante_haber->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_haber->glosa = $factura->glosa;
                    $comprobante_haber->debe = 0;
                    $comprobante_haber->haber = $factura->monto;
                    $comprobante_haber->save();

                    //Debe
                    $comprobante_debe = new ComprobantesDetalle();
                    $comprobante_debe->comprobante_id = $comprobante->id;
                    $comprobante_debe->plancuenta_id = $factura->plancuenta_id;
                    $comprobante_debe->plancuentaauxiliar_id = $factura->plancuentaauxiliar_id;
                    $comprobante_debe->proyecto_id = $factura->proyecto_id;
                    $comprobante_debe->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_debe->glosa = $factura->glosa;
                    $comprobante_debe->debe = round(floatval($factura->monto) * 0.87,2);
                    $comprobante_debe->haber = 0;
                    $comprobante_debe->save();
                }
                //Con excento y con descuento
                if(floatVal($factura->excento) != 0 && floatVal($factura->descuento) != 0){
                    echo "caso 4 <br>";
                    //Credito Fiscal
                    $comprobante_cf = new ComprobantesDetalle();
                    $comprobante_cf->comprobante_id = $comprobante->id;
                    $comprobante_cf->plancuenta_id = $request->plan_cuenta_iva;
                    $comprobante_cf->plancuentaauxiliar_id = null;
                    $comprobante_cf->proyecto_id = $factura->proyecto_id;
                    $comprobante_cf->centro_id = $factura->centro_id;
                    $comprobante_cf->glosa = $factura->glosa;
                    $comprobante_cf->debe = round(floatval($factura->monto) * 0.13,2);
                    $comprobante_cf->haber = 0;
                    $comprobante_cf->save();
                    
                    //Haber
                    $comprobante_haber = new ComprobantesDetalle();
                    $comprobante_haber->comprobante_id = $comprobante->id;
                    $comprobante_haber->plancuenta_id = $request->plan_cuenta_haber;
                    $comprobante_haber->plancuentaauxiliar_id = null;
                    $comprobante_haber->proyecto_id = $factura->proyecto_id;
                    $comprobante_haber->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_haber->glosa = $factura->glosa;
                    $comprobante_haber->debe = 0;
                    $comprobante_haber->haber = $factura->monto;
                    $comprobante_haber->save();

                    //Debe
                    $comprobante_debe = new ComprobantesDetalle();
                    $comprobante_debe->comprobante_id = $comprobante->id;
                    $comprobante_debe->plancuenta_id = $factura->plancuenta_id;
                    $comprobante_debe->plancuentaauxiliar_id = $factura->plancuentaauxiliar_id;
                    $comprobante_debe->proyecto_id = $factura->proyecto_id;
                    $comprobante_debe->centro_id = $factura->centro_id;
                    //$input2['cheque_nro'] = ($request['cheque_nro_1'])?$request['cheque_nro_1']:null;
                    //$input2['cheque_orden'] = ($request['cheque_orden_1'])?$request['cheque_orden_1']:null;
                    $comprobante_debe->glosa = $factura->glosa;
                    $comprobante_debe->debe = round(floatval($factura->monto) * 0.87,2);
                    $comprobante_debe->haber = 0;
                    $comprobante_debe->save();
                }
            }
            //DB::commit();
            //dd("Realizado");
            return redirect()->route('comprobantesdetalles.create', $comprobante)->with('success','Factura guardada con exito..');
        /*}catch(\Exception $th){
            DB::rollback();
            //dd("No realizado");
            return redirect()->route('comprobantesdetalles.create', $comprobante)->with('danger','Factura no Registrada!! :(, Intente nuevamente...');
        }*/
    }
}
