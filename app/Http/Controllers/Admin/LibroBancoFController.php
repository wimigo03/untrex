<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use App\Proyectos;
use App\PlanCuentas;
use Carbon\Carbon;
use DB;
use PDF;

class LibroBancoFController extends Controller
{
    public function index(){
        $proyectos = Proyectos::pluck('nombre','id');
        return view('libro-banco-f.index',compact('proyectos'));
    }

    public function seleccionar(Request $request){
        $input = $request->all();
        $id = $input['id'];
        $plancuenta = DB::table('plan_cuentas as a')
                        ->where('a.proyecto_id', $id)
                        ->where('a.cuenta_detalle','1')
                        ->where('a.codigo','LIKE','1.1.1.2.%')
                        ->select('a.id','a.nombre')
                        ->orderBy('a.id', 'desc')
                        ->get()->toJson();
        if($plancuenta){
            return response()->json([
                'plancuenta' => $plancuenta
            ]);
        }
        return response()->json(['error' => 'Algo Salio Mal']);
    }

    public function search(Request $request){
        $request->validate([
            'proyecto'=> 'required',
            'fecha_inicial'=> 'required',
            'fecha_final'=> 'required',
            'plancuenta_id' => 'required'
        ]);
        
        if($request->tipo == "Todo"){
            $datos = $this->todo($request->proyecto,$request->fecha_inicial,$request->fecha_final,$request->plancuenta_id);
            $proyecto = $datos['proyecto'];
            $plancuenta = $datos['plancuenta'];
            $fecha_inicial = $datos['fecha_inicial'];
            $fecha_final = $datos['fecha_final'];
            $nro_inicial = 'null';
            $nro_final = 'null';
            $comprobantes = $datos['comprobantes'];
            $saldo = $datos['saldo'];
            $saldo_final = $datos['saldo_final'];
            $total_debe = $datos['total_debe'];
            $total_haber = $datos['total_haber'];
            $tipo = $request->tipo;
            return view('libro-banco-f.search',compact('proyecto','plancuenta','fecha_inicial','fecha_final','nro_inicial','nro_final','comprobantes','saldo','saldo_final','total_debe','total_haber','tipo'));
        }else{
            if(($request->nro_inicial == null) || ($request->nro_final == null)){
                return back()->with('danger', 'Los datos son insuficientes para procesar la peticion...');
            }
            if($request->tipo == "Cheque"){  
                $datos = $this->cheque($request->proyecto,$request->fecha_inicial,$request->fecha_final,$request->nro_inicial,$request->nro_final,$request->plancuenta_id);
            }else{
                $datos = $this->transferencia($request->proyecto,$request->fecha_inicial,$request->fecha_final,$request->nro_inicial,$request->nro_final,$request->plancuenta_id);
            }
            $proyecto = $datos['proyecto'];
            $plancuenta = $datos['plancuenta'];
            $fecha_inicial = $datos['fecha_inicial'];
            $fecha_final = $datos['fecha_final'];
            $nro_inicial = $datos['nro_inicial'];
            $nro_final = $datos['nro_final'];
            $comprobantes = $datos['comprobantes'];
            $saldo = $datos['saldo'];
            $saldo_final = $datos['saldo_final'];
            $total_debe = $datos['total_debe'];
            $total_haber = $datos['total_haber'];
            $tipo = $request->tipo;
            return view('libro-banco-f.search',compact('proyecto','plancuenta','fecha_inicial','fecha_final','nro_inicial','nro_final','comprobantes','saldo','saldo_final','total_debe','total_haber','tipo'));
        }
    }

    private function todo($proyecto_id,$fecha_inicial,$fecha_final,$plancuenta_id){
        $fecha_inicial = substr($fecha_inicial,6,4) . '-' . substr($fecha_inicial,3,2) . '-' . substr($fecha_inicial,0,2);
        $fecha_final = substr($fecha_final,6,4) . '-' . substr($fecha_final,3,2) . '-' . substr($fecha_final,0,2);
        /*$gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }*/
        //$fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->select('b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo = 0;
        foreach($sumarRestar as $datos){
            $saldo += $datos->debe;
            $saldo -= $datos->haber;
        }

        $comprobantes = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','b.tipo_transaccion','b.cheque_nro','b.cheque_orden','b.glosa','b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo_final = $saldo;
        $total_debe = 0;
        $total_haber = 0;
        foreach ($comprobantes as $datos) {
            $saldo_final += $datos->debe;
            $saldo_final -= $datos->haber;
            $total_debe += $datos->debe;
            $total_haber += $datos->haber;
        }
        return ([
            'proyecto'          =>  $proyecto,
            'plancuenta'        =>  $plancuenta,
            'fecha_inicial'     =>  $fecha_inicial,
            'fecha_final'       =>  $fecha_final,
            'comprobantes'      =>  $comprobantes,
            'saldo'             =>  $saldo,
            'saldo_final'       =>  $saldo_final,
            'total_debe'        =>  $total_debe,
            'total_haber'       =>  $total_haber
        ]);
    }

    private function cheque($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id){
        $fecha_inicial = substr($fecha_inicial,6,4) . '-' . substr($fecha_inicial,3,2) . '-' . substr($fecha_inicial,0,2);
        $fecha_final = substr($fecha_final,6,4) . '-' . substr($fecha_final,3,2) . '-' . substr($fecha_final,0,2);
        /*$gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }*/
        //$fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','CHEQUE')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->select('b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo = 0;
        foreach($sumarRestar as $datos){
            $saldo += $datos->debe;
            $saldo -= $datos->haber;
        }

        $comprobantes = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','CHEQUE')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','b.tipo_transaccion','b.cheque_nro','b.cheque_orden','b.glosa','b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo_final = $saldo;
        $total_debe = 0;
        $total_haber = 0;
        foreach ($comprobantes as $datos) {
            $saldo_final += $datos->debe;
            $saldo_final -= $datos->haber;
            $total_debe += $datos->debe;
            $total_haber += $datos->haber;
        }
        return ([
            'proyecto'          =>  $proyecto,
            'plancuenta'        =>  $plancuenta,
            'fecha_inicial'     =>  $fecha_inicial,
            'fecha_final'       =>  $fecha_final,
            'nro_inicial'       =>  $nro_inicial,
            'nro_final'         =>  $nro_final,
            'comprobantes'      =>  $comprobantes,
            'saldo'             =>  $saldo,
            'saldo_final'       =>  $saldo_final,
            'total_debe'        =>  $total_debe,
            'total_haber'       =>  $total_haber
        ]);
    }
    private function transferencia($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id){
        $fecha_inicial = substr($fecha_inicial,6,4) . '-' . substr($fecha_inicial,3,2) . '-' . substr($fecha_inicial,0,2);
        $fecha_final = substr($fecha_final,6,4) . '-' . substr($fecha_final,3,2) . '-' . substr($fecha_final,0,2);
        /*$gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }*/
        //$fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','TRANSFERENCIA')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->select('b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo = 0;
        foreach($sumarRestar as $datos){
            $saldo += $datos->debe;
            $saldo -= $datos->haber;
        }
        $comprobantes = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','TRANSFERENCIA')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','b.tipo_transaccion','b.cheque_nro','b.cheque_orden','b.glosa','b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo_final = $saldo;
        $total_debe = 0;
        $total_haber = 0;
        foreach ($comprobantes as $datos) {
            $saldo_final += $datos->debe;
            $saldo_final -= $datos->haber;
            $total_debe += $datos->debe;
            $total_haber += $datos->haber;
        }
        return ([
            'proyecto'          =>  $proyecto,
            'plancuenta'        =>  $plancuenta,
            'fecha_inicial'     =>  $fecha_inicial,
            'fecha_final'       =>  $fecha_final,
            'nro_inicial'       =>  $nro_inicial,
            'nro_final'         =>  $nro_final,
            'comprobantes'      =>  $comprobantes,
            'saldo'             =>  $saldo,
            'saldo_final'       =>  $saldo_final,
            'total_debe'        =>  $total_debe,
            'total_haber'       =>  $total_haber
        ]);
    }

    public function pdf1($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id){
        //dd($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id);
        set_time_limit(0);ini_set('memory_limit', '1G');
        /*$gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';*/
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->select('b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo = 0;
        foreach($sumarRestar as $datos){
            $saldo += $datos->debe;
            $saldo -= $datos->haber;
        }

        $comprobantes = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','b.tipo_transaccion','b.cheque_nro','cheque_orden','b.glosa','b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo_final = $saldo;
        $total_debe = 0;
        $total_haber = 0;
        foreach ($comprobantes as $datos) {
            $saldo_final += $datos->debe;
            $saldo_final -= $datos->haber;
            $total_debe += $datos->debe;
            $total_haber += $datos->haber;
        }
        $tipo = 'TRANSFERENCIA && CHEQUE';
        $pdf = PDF::loadView('libro-banco-f.pdf',compact(['proyecto','plancuenta','fecha_inicial','fecha_final','comprobantes','saldo','saldo_final','total_debe','total_haber','tipo']));
        $pdf->setPaper('LETTER', 'portrait');//landscape
        return $pdf->stream();
    }

    public function pdf2($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id){
        //dd($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id);
        set_time_limit(0);ini_set('memory_limit', '1G');
        /*$gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';*/
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','TRANSFERENCIA')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->select('b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo = 0;
        foreach($sumarRestar as $datos){
            $saldo += $datos->debe;
            $saldo -= $datos->haber;
        }

        $comprobantes = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','TRANSFERENCIA')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','b.tipo_transaccion','b.cheque_nro','cheque_orden','b.glosa','b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo_final = $saldo;
        $total_debe = 0;
        $total_haber = 0;
        foreach ($comprobantes as $datos) {
            $saldo_final += $datos->debe;
            $saldo_final -= $datos->haber;
            $total_debe += $datos->debe;
            $total_haber += $datos->haber;
        }
        $tipo = 'TRANSFERENCIA';
        $pdf = PDF::loadView('libro-banco-f.pdf',compact(['proyecto','plancuenta','fecha_inicial','fecha_final','comprobantes','saldo','saldo_final','total_debe','total_haber','tipo']));
        $pdf->setPaper('LETTER', 'portrait');//landscape
        return $pdf->stream();
    }

    public function pdf3($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id){
        //dd($proyecto_id,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$plancuenta_id);
        set_time_limit(0);ini_set('memory_limit', '1G');
        /*$gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';*/
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','CHEQUE')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->select('b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo = 0;
        foreach($sumarRestar as $datos){
            $saldo += $datos->debe;
            $saldo -= $datos->haber;
        }

        $comprobantes = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('b.tipo_transaccion','CHEQUE')
                                ->where('b.cheque_nro','>=',intval($nro_inicial))
                                ->where('b.cheque_nro','<=',intval($nro_final))
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','b.tipo_transaccion','b.cheque_nro','cheque_orden','b.glosa','b.debe','b.haber')
                                ->orderBy('a.fecha','asc')
                                ->get();
        $saldo_final = $saldo;
        $total_debe = 0;
        $total_haber = 0;
        foreach ($comprobantes as $datos) {
            $saldo_final += $datos->debe;
            $saldo_final -= $datos->haber;
            $total_debe += $datos->debe;
            $total_haber += $datos->haber;
        }
        $tipo = 'CHEQUE';
        $pdf = PDF::loadView('libro-banco-f.pdf',compact(['proyecto','plancuenta','fecha_inicial','fecha_final','comprobantes','saldo','saldo_final','total_debe','total_haber','tipo']));
        $pdf->setPaper('LETTER', 'portrait');//landscape
        return $pdf->stream();
    }
}
