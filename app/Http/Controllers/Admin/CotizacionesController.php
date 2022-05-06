<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cotizaciones;
use DB;
//use Datatables;

class CotizacionesController extends Controller
{
    public function index(){
        $cotizaciones = Cotizaciones::paginate();
        return view('cotizaciones.index',compact('cotizaciones'));
    }

    public function search(Request $request){
        $request->validate([
            'fecha' => 'required'
         ]);
        $date_cambio = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        $cotizaciones = Cotizaciones::where('fecha','like',$date_cambio)->paginate();
        return view('cotizaciones.index',compact('cotizaciones'));
    }

    public function create(){
        return view('cotizaciones.create');
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
        $duplicado = Cotizaciones::where('fecha',$date_cambio)->where('deleted_at',null)->first();
        if($duplicado != null){
            return back()->with('info', 'La fecha seleccionada ya tiene una cotizacion activa...');
        }
        $date_actual = date('Y-m-d');
        if($date_cambio > $date_actual){
            return back()->with('info', 'Las cotizaciones no pueden ser de fechas adelantadas...');
        }
        $cotizacion = new Cotizaciones();
        $cotizacion->fecha = $date_cambio;
        $cotizacion->ufv = $request->ufv;
        $cotizacion->dolar_oficial = $request->dolar_oficial;
        $cotizacion->dolar_compra = $request->dolar_compra;
        $cotizacion->dolar_venta = $request->dolar_venta;
        $cotizacion->status = 1;
        $cotizacion->save();

        return redirect()->route('cotizaciones.index')->with('message','Se agrego un nueva cotizacion...');
    }
}
