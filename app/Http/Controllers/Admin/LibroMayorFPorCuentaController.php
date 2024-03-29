<?php

namespace App\Http\Controllers\Admin;

use DB;
use PDF;
use Carbon\Carbon;
use App\Proyectos;
use App\PlanCuentas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Luecano\NumeroALetras\NumeroALetras;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibroMayorFPorCuentaGeneralExport;
use App\Exports\LibroMayorFPorCuentaAuxiliar1Export;
use App\Exports\LibroMayorFPorCuentaAuxiliar2Export;
class LibroMayorFPorCuentaController extends Controller
{
    public function index(){
        $proyectos = Proyectos::pluck('nombre','id');
        return view('libro-mayor-f.por-cuenta.index',compact('proyectos'));
    }

    public function search(Request $request){
        $request->validate([
            'proyecto'=> 'required',
            'fecha_inicial'=> 'required',
            'fecha_final'=> 'required',
            'tipo' => 'required',
            'plancuenta_id' => 'required'
        ]);
        if($request->tipo == 'General'){
            $datos = $this->searchGeneral($request->proyecto,$request->fecha_inicial,$request->fecha_final,$request->tipo,$request->plancuenta_id);
            $proyecto = $datos['proyecto'];
            $tipo = $datos['tipo'];
            $plancuenta = $datos['plancuenta'];
            $fecha_inicial = $datos['fecha_inicial'];
            $fecha_final = $datos['fecha_final'];
            $comprobantes = $datos['comprobantes'];
            $saldo = $datos['saldo'];
            $saldo_final = $datos['saldo_final'];
            $total_debe = $datos['total_debe'];
            $total_haber = $datos['total_haber'];
            return view('libro-mayor-f.por-cuenta.search-general',compact('proyecto','tipo','plancuenta','fecha_inicial','fecha_final','comprobantes','saldo','saldo_final','total_debe','total_haber'));
        }else{
            $datos = $this->searchAuxiliar($request->proyecto,$request->fecha_inicial,$request->fecha_final,$request->tipo,$request->plancuenta_id);
            $fecha_saldo_inicial = $datos['fecha_saldo_inicial'];
            $proyecto = $datos['proyecto'];
            $tipo = $datos['tipo'];
            $plancuenta = $datos['plancuenta'];
            $fecha_inicial = $datos['fecha_inicial'];
            $fecha_final = $datos['fecha_final'];
            $find_auxiliares = $datos['find_auxiliares'];
            $auxiliares = $datos['auxiliares'];
            return view('libro-mayor-f.por-cuenta.search-auxiliar',compact('fecha_saldo_inicial','proyecto','tipo','plancuenta','fecha_inicial','fecha_final','find_auxiliares','auxiliares'));
        }
    }

    private function searchGeneral($proyecto_id,$fecha_inicial,$fecha_final,$tipo,$plancuenta_id){
        $fecha_inicial = substr($fecha_inicial,6,4) . '-' . substr($fecha_inicial,3,2) . '-' . substr($fecha_inicial,0,2);
        $fecha_final = substr($fecha_final,6,4) . '-' . substr($fecha_final,3,2) . '-' . substr($fecha_final,0,2);
        $gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->join('centros as c','c.id','b.centro_id')
                                ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->where('b.deleted_at',null)
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
                                ->join('centros as c','c.id','b.centro_id')
                                ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->where('b.deleted_at',null)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','c.abreviatura as centro',DB::raw("if(isnull(d.nombre),'S/N',d.nombre) as auxiliar"),'b.cheque_nro','b.glosa','b.debe','b.haber')
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
            'tipo'              =>  $tipo,
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

    private function searchAuxiliar($proyecto_id,$fecha_inicial,$fecha_final,$tipo,$plancuenta_id){
        $fecha_inicial = substr($fecha_inicial,6,4) . '-' . substr($fecha_inicial,3,2) . '-' . substr($fecha_inicial,0,2);
        $fecha_final = substr($fecha_final,6,4) . '-' . substr($fecha_final,3,2) . '-' . substr($fecha_final,0,2);
        $gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $find_auxiliares = DB::table('comprobantes_fiscales as a')
                            ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                            ->leftjoin('plan_cuentas_auxiliares as c','c.id','b.plancuentaauxiliar_id')
                            ->select('c.id as plancuentaauxiliar_id','c.nombre as auxiliar')
                            ->where('a.proyecto_id',$proyecto_id)
                            ->where('b.plancuenta_id',$plancuenta_id)
                            ->where('a.status','!=','2')
                            ->where('a.fecha','>=',$fecha_inicial)
                            ->where('a.fecha','<=',$fecha_final)
                            ->where('b.deleted_at',null)
                            ->groupBy('c.id','c.nombre')
                            ->orderBy('c.id','asc')->get();
        $auxiliares = DB::table('comprobantes_fiscales as a')
                            ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                            ->join('centros as c','c.id','b.centro_id')
                            ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                            ->select('d.id as plancuentaauxiliar_id','d.nombre as auxiliar',
                                        DB::raw("SUM(b.debe) as total_auxiliar_debe"),
                                        DB::raw("SUM(b.haber) as total_auxiliar_haber"),
                                        /*DB::raw("SUM($condicion) as saldoTotal")*/)
                            ->where('a.proyecto_id',$proyecto_id)
                            ->where('b.plancuenta_id',$plancuenta_id)
                            ->where('a.status','!=','2')
                            ->where('a.fecha','>=',$fecha_inicial)
                            ->where('a.fecha','<=',$fecha_final)
                            ->where('b.deleted_at',null)
                            ->groupBy('d.id','d.nombre')
                            ->orderBy('d.id','asc')->get();
        return ([
            'fecha_saldo_inicial'   =>  $fecha_saldo_inicial,
            'proyecto'              =>  $proyecto,
            'tipo'                  =>  $tipo,
            'plancuenta'            =>  $plancuenta,
            'fecha_inicial'         =>  $fecha_inicial,
            'fecha_final'           =>  $fecha_final,
            'find_auxiliares'       =>  $find_auxiliares,
            'auxiliares'            =>  $auxiliares
        ]);
    }

    public function findauxiliar(Request $request){
        $proyecto_id = $request->proyecto_id;
        $fecha_inicial = $request->fecha_inicial;
        $fecha_final = $request->fecha_final;
        $tipo = $request->tipo;
        $plancuenta_id = $request->plancuenta_id;
        $plancuentaauxiliar_id = $request->plancuentaauxiliar_id;

        $gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $find_auxiliares = DB::table('comprobantes_fiscales as a')
                            ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                            ->leftjoin('plan_cuentas_auxiliares as c','c.id','b.plancuentaauxiliar_id')
                            ->select('c.id as plancuentaauxiliar_id','c.nombre as auxiliar')
                            ->where('a.proyecto_id',$proyecto_id)
                            ->where('b.plancuenta_id',$plancuenta_id)
                            ->where('a.status','!=','2')
                            ->where('a.fecha','>=',$fecha_inicial)
                            ->where('a.fecha','<=',$fecha_final)
                            ->where('b.deleted_at',null)
                            ->groupBy('c.id','c.nombre')
                            ->orderBy('c.id','asc')->get();
        $auxiliares = DB::table('comprobantes_fiscales as a')
                            ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                            ->join('centros as c','c.id','b.centro_id')
                            ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                            ->select('d.id as plancuentaauxiliar_id','d.nombre as auxiliar',
                                        DB::raw("SUM(b.debe) as total_auxiliar_debe"),
                                        DB::raw("SUM(b.haber) as total_auxiliar_haber"),
                                        /*DB::raw("SUM($condicion) as saldoTotal")*/)
                            ->where('a.proyecto_id',$proyecto_id)
                            ->where('b.plancuenta_id',$plancuenta_id)
                            ->where('b.plancuentaauxiliar_id',$plancuentaauxiliar_id)
                            ->where('a.status','!=','2')
                            ->where('a.fecha','>=',$fecha_inicial)
                            ->where('a.fecha','<=',$fecha_final)
                            ->where('b.deleted_at',null)
                            ->groupBy('d.id','d.nombre')
                            ->orderBy('d.id','asc')->get();
        return view('libro-mayor-f.por-cuenta.search-auxiliar',compact('fecha_saldo_inicial','proyecto','tipo','plancuenta','fecha_inicial','fecha_final','find_auxiliares','auxiliares','plancuentaauxiliar_id'));
    }

    public function seleccionar(Request $request){
        $input = $request->all();
        $id = $input['id'];
        $plancuenta = DB::table('plan_cuentas as a')
                            ->where('a.proyecto_id', $id)
                            ->where('a.cuenta_detalle','1')
                            ->where('a.deleted_at',null)
                            ->select(DB::raw("CONCAT(a.codigo,' ',a.nombre) as cuenta"),'a.id')
                            ->orderBy('a.codigo', 'asc')
                            ->get()->toJson();
        if($plancuenta){
            return response()->json([
                'plancuenta' => $plancuenta
            ]);
        }
        return response()->json(['error' => 'Algo Salio Mal']);
    }

    public function generalPdf($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id){
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
            
            $datos = $this->getGeneralPdf($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id);
            $proyecto = $datos['proyecto'];
            $tipo = $datos['tipo'];
            $plancuenta = $datos['plancuenta'];
            $fecha_inicial = $datos['fecha_inicial'];
            $comprobantes = $datos['comprobantes'];
            $saldo = $datos['saldo'];
            $saldo_final = $datos['saldo_final'];
            $total_debe = $datos['total_debe'];
            $total_haber = $datos['total_haber'];
            $pdf = PDF::loadView('libro-mayor-f.por-cuenta.pdf-general',compact(['proyecto','tipo','plancuenta','fecha_inicial','fecha_final','comprobantes','saldo','saldo_final','total_debe','total_haber']));
            $pdf->setPaper('LETTER', 'portrait');//landscape
            return $pdf->stream();
            
        } catch (\Throwable $th){
            return '[ERROR_500]';
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function generalExcel($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id){
        set_time_limit(0);ini_set('memory_limit', '1G');
        $datos = $this->getGeneralPdf($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id);
        $proyecto = $datos['proyecto'];
        $tipo = $datos['tipo'];
        $plancuenta = $datos['plancuenta'];
        $fecha_inicial = $datos['fecha_inicial'];
        $comprobantes = $datos['comprobantes'];
        $saldo = $datos['saldo'];
        $saldo_final = $datos['saldo_final'];
        $total_debe = $datos['total_debe'];
        $total_haber = $datos['total_haber'];
        $file_name = 'LibroMayorPorCuentaGeneral';
        return Excel::download(new LibroMayorFPorCuentaGeneralExport($proyecto,$tipo,$plancuenta,$fecha_inicial,$fecha_final,$comprobantes,$saldo,$saldo_final,$total_debe,$total_haber),$file_name . '.xlsx');
    }

    public function getGeneralPdf($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id){
        $gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $sumarRestar = DB::table('comprobantes_fiscales as a')
                                ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                                ->join('centros as c','c.id','b.centro_id')
                                ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                //->where('a.fecha','>=',$fecha_saldo_inicial)
                                ->where('a.fecha','<',$fecha_inicial)
                                ->where('b.deleted_at',null)
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
                                ->join('centros as c','c.id','b.centro_id')
                                ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                                ->where('a.proyecto_id',$proyecto_id)
                                ->where('b.plancuenta_id',$plancuenta_id)
                                ->where('a.status','!=','2')
                                ->where('a.fecha','>=',$fecha_inicial)
                                ->where('a.fecha','<=',$fecha_final)
                                ->where('b.deleted_at',null)
                                ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','c.abreviatura as centro',DB::raw("if(isnull(d.nombre),'S/N',d.nombre) as auxiliar"),'b.cheque_nro','b.glosa','b.debe','b.haber')
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
            'proyecto' => $proyecto,
            'tipo' => $tipo,
            'plancuenta' => $plancuenta,
            'fecha_inicial' => $fecha_inicial,
            'comprobantes' => $comprobantes,
            'saldo' => $saldo,
            'saldo_final' => $saldo_final,
            'total_debe' => $total_debe,
            'total_haber' => $total_haber
        ]);
    }

    public function auxiliarPdf1($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id){
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
            //set_time_limit(0);ini_set('memory_limit', '1G');

            $datos = $this->getAuxiliarPdf1($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id);
            $fecha_saldo_inicial = $datos['fecha_saldo_inicial'];
            $proyecto = $datos['proyecto'];
            $plancuenta = $datos['plancuenta'];
            $auxiliares = $datos['auxiliares'];
            $pdf = PDF::loadView('libro-mayor-f.por-cuenta.pdf-auxiliar',compact(['fecha_saldo_inicial','proyecto','tipo','plancuenta','fecha_inicial','fecha_final','auxiliares']));
            $pdf->setPaper('LETTER', 'portrait');//landscape
            return $pdf->stream();

        } catch (\Throwable $th){
            return '[ERROR_500]';
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function auxiliarExcel1($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id){
        $datos = $this->getAuxiliarPdf1($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id);
        $fecha_saldo_inicial = $datos['fecha_saldo_inicial'];
        $proyecto = $datos['proyecto'];
        $plancuenta = $datos['plancuenta'];
        $auxiliares = $datos['auxiliares'];
        $file_name = 'LibroMayorPorCuentaAuxiliar';
        return Excel::download(new LibroMayorFPorCuentaAuxiliar1Export($fecha_saldo_inicial,$proyecto,$tipo,$plancuenta,$fecha_inicial,$fecha_final,$auxiliares),$file_name . '.xlsx');
    }

    private function getAuxiliarPdf1($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id){
        $gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $auxiliares = DB::table('comprobantes_fiscales as a')
                            ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                            ->join('centros as c','c.id','b.centro_id')
                            ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                            ->select('d.id as plancuentaauxiliar_id','d.nombre as auxiliar',
                                        DB::raw("SUM(b.debe) as total_auxiliar_debe"),
                                        DB::raw("SUM(b.haber) as total_auxiliar_haber"),
                                        /*DB::raw("SUM($condicion) as saldoTotal")*/)
                            ->where('a.proyecto_id',$proyecto_id)
                            ->where('b.plancuenta_id',$plancuenta_id)
                            ->where('a.status','!=','2')
                            ->where('a.fecha','>=',$fecha_inicial)
                            ->where('a.fecha','<=',$fecha_final)
                            ->where('b.deleted_at',null)
                            ->groupBy('d.id','d.nombre')
                            ->orderBy('d.id','asc')->get();
        return ([
            'fecha_saldo_inicial' => $fecha_saldo_inicial,
            'proyecto' => $proyecto,
            'plancuenta' => $plancuenta,
            'auxiliares' => $auxiliares
        ]);
    }

    public function auxiliarPdf2($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id,$plancuentaauxiliar_id){
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
            //set_time_limit(0);ini_set('memory_limit', '1G');
            $datos = $this->getAuxiliarPdf2($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id,$plancuentaauxiliar_id);
            $fecha_saldo_inicial = $datos['fecha_saldo_inicial'];
            $proyecto = $datos['proyecto'];
            $plancuenta = $datos['plancuenta'];
            $auxiliares = $datos['auxiliares'];
            $pdf = PDF::loadView('libro-mayor-f.por-cuenta.pdf-auxiliar',compact(['fecha_saldo_inicial','proyecto','tipo','plancuenta','fecha_inicial','fecha_final','auxiliares','plancuentaauxiliar_id']));
            $pdf->setPaper('LETTER', 'portrait');//landscape
            return $pdf->stream();

        } catch (\Throwable $th){
            return '[ERROR_500]';
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function auxiliarExcel2($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id,$plancuentaauxiliar_id){
        $datos = $this->getAuxiliarPdf2($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id,$plancuentaauxiliar_id);
        $fecha_saldo_inicial = $datos['fecha_saldo_inicial'];
        $proyecto = $datos['proyecto'];
        $plancuenta = $datos['plancuenta'];
        $auxiliares = $datos['auxiliares'];
        $file_name = 'LibroMayorPorCuentaAuxiliar';
        return Excel::download(new LibroMayorFPorCuentaAuxiliar2Export($fecha_saldo_inicial,$proyecto,$tipo,$plancuenta,$fecha_inicial,$fecha_final,$auxiliares,$plancuentaauxiliar_id),$file_name . '.xlsx');
    }

    private function getAuxiliarPdf2($proyecto_id,$tipo,$fecha_inicial,$fecha_final,$plancuenta_id,$plancuentaauxiliar_id){
        $gestion = Carbon::parse($fecha_inicial);
        if($gestion->month < 4){
            $gestion = $gestion->year - 1;
        }else{
            $gestion = $gestion->year;
        }
        $fecha_saldo_inicial = $gestion . '-04-01';
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        $plancuenta = PlanCuentas::where('id',$plancuenta_id)->first();
        $auxiliares = DB::table('comprobantes_fiscales as a')
                            ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                            ->join('centros as c','c.id','b.centro_id')
                            ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                            ->select('d.id as plancuentaauxiliar_id','d.nombre as auxiliar',
                                        DB::raw("SUM(b.debe) as total_auxiliar_debe"),
                                        DB::raw("SUM(b.haber) as total_auxiliar_haber"),
                                        /*DB::raw("SUM($condicion) as saldoTotal")*/)
                            ->where('a.proyecto_id',$proyecto_id)
                            ->where('b.plancuenta_id',$plancuenta_id)
                            ->where('b.plancuentaauxiliar_id',$plancuentaauxiliar_id)
                            ->where('a.status','!=','2')
                            ->where('a.fecha','>=',$fecha_inicial)
                            ->where('a.fecha','<=',$fecha_final)
                            ->where('b.deleted_at',null)
                            ->groupBy('d.id','d.nombre')
                            ->orderBy('d.id','asc')->get();
        return ([
            'fecha_saldo_inicial' => $fecha_saldo_inicial,
            'proyecto' => $proyecto,
            'plancuenta' => $plancuenta,
            'auxiliares' => $auxiliares
        ]);
    }
}
