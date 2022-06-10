<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TipoCambio;
use DB;

class TipoCambioController extends Controller
{
    public function index(){    
        return view('tipo-cambio.index');
    }

    public function indexAjax(){
        return datatables()
            ->query(DB::table('tipo_cambio as a')
            ->select('a.id as tipo_cambio_id',
                    DB::raw("DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha_cambio"),
                    'a.dolar_oficial','a.dolar_compra','a.dolar_venta','a.ufv',
                    DB::raw("if(a.status = '1','ACTIVO','ELIMINADO') as status_search")))
            ->filterColumn('status_search', function($query, $keyword) {
                $sql = "if(a.status = '1','ACTIVO','ELIMINADO')  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->filterColumn('fecha_cambio', function($query, $keyword) {
                $sql = "DATE_FORMAT(a.fecha,'%d/%m/%Y')  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->addColumn('btnActions','tipo-cambio.partials.actions')
            ->rawColumns(['btnActions'])
            ->toJson();
    }

    public function search(Request $request){
        $request->validate([
            'fecha_desde' => 'required',
            'fecha_hasta' => 'required'
         ]);
        $date_cambio_desde = substr($request->fecha_desde,6,4) . '-' . substr($request->fecha_desde,3,2) . '-' . substr($request->fecha_desde,0,2);
        $date_cambio_hasta = substr($request->fecha_hasta,6,4) . '-' . substr($request->fecha_hasta,3,2) . '-' . substr($request->fecha_hasta,0,2);
        $tipos_cambios = TipoCambio::where('fecha','>=',$date_cambio_desde)->where('fecha','<=',$date_cambio_hasta)->orderBy('fecha','desc')->get();
        return view('tipo-cambio.indexSearch',compact('tipos_cambios'));
    }

    public function create(){
        return view('tipo-cambio.create');
    }

    public function store(Request $request){
        $request->validate([
           'fecha' => 'required',
           'dolar_oficial'=>'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'dolar_compra' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'dolar_venta' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'ufv' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,5})?$/'
        ]);
        $date_cambio = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        $duplicado = TipoCambio::where('fecha',$date_cambio)->where('deleted_at',null)->first();
        if($duplicado != null){
            return back()->with('info', 'La fecha seleccionada ya tiene una cotizacion activa...');
        }
        $date_actual = date('Y-m-d');
        if($date_cambio > $date_actual){
            return back()->with('info', 'Las cotizaciones no pueden ser de fechas adelantadas...');
        }
        $tipo_cambio = new TipoCambio();
        $tipo_cambio->fecha = $date_cambio;
        $tipo_cambio->ufv = $request->ufv;
        $tipo_cambio->dolar_oficial = $request->dolar_oficial;
        $tipo_cambio->dolar_compra = $request->dolar_compra;
        $tipo_cambio->dolar_venta = $request->dolar_venta;
        $tipo_cambio->status = 1;
        $tipo_cambio->save();

        return redirect()->route('tipo_cambio.index')->with('message','Se agrego un nuevo tipo de cambio...');
    }

    public function editar($tipo_cambio_id){
        $tipo_cambio = TipoCambio::where('id',$tipo_cambio_id)->first();
        return view('tipo-cambio.editar',compact('tipo_cambio'));
    }

    public function update(Request $request){
        $request->validate([
           'fecha' => 'required',
           'dolar_oficial'=>'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'dolar_compra' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'dolar_venta' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'ufv' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,5})?$/'
        ]);
        $date_cambio = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        $date_actual = date('Y-m-d');
        if($date_cambio > $date_actual){
            return back()->with('info', 'Las cotizaciones no pueden ser de fechas adelantadas...');
        }
        $tipo_cambio = TipoCambio::find($request->tipo_cambio_id);
        $tipo_cambio->fecha = $date_cambio;
        $tipo_cambio->ufv = $request->ufv;
        $tipo_cambio->dolar_oficial = $request->dolar_oficial;
        $tipo_cambio->dolar_compra = $request->dolar_compra;
        $tipo_cambio->dolar_venta = $request->dolar_venta;
        $tipo_cambio->status = 1;
        $tipo_cambio->update();

        return redirect()->route('tipo_cambio.index')->with('info','El tipo de cambio fue actualizado con exito...');
    }
}
