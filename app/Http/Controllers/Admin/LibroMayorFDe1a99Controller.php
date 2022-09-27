<?php

namespace App\Http\Controllers\Admin;

use DB;
use PDF;
use Carbon\Carbon;
use App\Proyectos;
use App\PlanCuentas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibroMayorFDe1a99Export;

class LibroMayorFDe1a99Controller extends Controller
{
    public function index(){
        $proyectos = Proyectos::pluck('nombre','id');
        return view('libro-mayor-f.de-1-a-99.index',compact('proyectos'));
    }

    public function search(Request $request){
        $request->validate([
            'proyecto'=> 'required',
            'fecha_inicial'=> 'required',
            'fecha_final'=> 'required',
            'estado' => 'required',
            'plancuenta_inicial_id' => 'required',
            'plancuenta_final_id' => 'required'
        ]);
        $fecha_inicial = substr($request->fecha_inicial,6,4) . '-' . substr($request->fecha_inicial,3,2) . '-' . substr($request->fecha_inicial,0,2);
        $fecha_final = substr($request->fecha_final,6,4) . '-' . substr($request->fecha_final,3,2) . '-' . substr($request->fecha_final,0,2);

        $datos = $this->getSearch($request->proyecto,$fecha_inicial,$fecha_final,$request->estado,$request->plancuenta_inicial_id,$request->plancuenta_final_id);
        
        $proyecto = $datos['proyecto'];
        $plancuenta_inicial = $datos['plancuenta_inicial'];
        $plancuenta_final = $datos['plancuenta_final'];
        $comprobantes = $datos['comprobantes'];
        $fecha_inicial = $datos['fecha_inicial'];
        $fecha_final = $datos['fecha_final'];
        $total_debe = $datos['total_debe'];
        $total_haber = $datos['total_haber'];
        $estado = $datos['estado'];
        
        return view('libro-mayor-f.de-1-a-99.search',compact('proyecto','plancuenta_inicial','plancuenta_final','comprobantes','fecha_inicial','fecha_final','total_debe','total_haber','estado'));
    }

    private function getSearch($proyecto_id,$fecha_inicial,$fecha_final,$estado,$plancuenta_inicial_id,$plancuenta_final_id){
        
        if($estado == "A_B"){
            $estado_search = ['0','1'];
        }else if($estado == 'B'){
            $estado_search = ['0'];
        }else if($estado == "A"){
            $estado_search = ['1'];
        }

        $plancuenta_inicial = PlanCuentas::where('id',$plancuenta_inicial_id)->select('id','nombre','codigo')->first();
        $plancuenta_final = PlanCuentas::where('id',$plancuenta_final_id)->select('id','nombre','codigo')->first();
        $proyecto = Proyectos::where('id',$proyecto_id)->first();

        $cuentas = PlanCuentas::whereBetween('codigo',[$plancuenta_inicial->codigo,$plancuenta_final->codigo])
                                ->where('proyecto_id',$proyecto_id)
                                ->where('cuenta_detalle','1')
                                ->select('id','codigo')
                                //->get()
                                ->pluck('id')
                                ->toArray();

        $comprobantes = DB::table('comprobantes_fiscales as a')
                            ->join('comprobantes_fiscales_detalles as b','b.comprobante_fiscal_id','a.id')
                            ->leftjoin('plan_cuentas as c','c.id','b.plancuenta_id')
                            ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                            ->leftjoin('centros as e','e.id','b.centro_id')
                            ->whereIn('b.plancuenta_id',$cuentas)
                            ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                            ->where('a.proyecto_id',$proyecto_id)
                            ->whereIn('a.status',$estado_search)
                            ->where('b.deleted_at',null)
                            ->orderBy('c.codigo','asc')
                            ->orderBy('a.fecha','asc')
                            ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','c.codigo','c.nombre as cuenta',
                                        DB::raw("if(isnull(b.plancuentaauxiliar_id),'S/A',d.nombre) as auxiliar"),
                                        DB::raw("if(isnull(b.centro_id),'S/C',e.abreviatura) as centro"),
                                        'b.glosa','b.debe','b.haber',
                                        DB::raw("if(isnull(b.tipo_transaccion),'S/N',if(b.tipo_transaccion = 'CHEQUE','CH - ','TR - ')) as tipo_transaccion"),
                                        'b.cheque_nro')
                            ->get();

        $total_debe = $comprobantes->sum('debe');
        $total_haber = $comprobantes->sum('haber');

        return ([
            'proyecto'              =>  $proyecto,
            'plancuenta_inicial'    =>  $plancuenta_inicial,
            'plancuenta_final'      =>  $plancuenta_final,
            'comprobantes'          =>  $comprobantes,
            'fecha_inicial'     =>  $fecha_inicial,
            'fecha_final'       =>  $fecha_final,
            'total_debe'        =>  $total_debe,
            'total_haber'       =>  $total_haber,
            'estado'       =>  $estado
        ]);
    }

    public function seleccionar(Request $request){
        $input = $request->all();
        $id = $input['id'];
        $plancuenta = DB::table('plan_cuentas as a')
                            ->where('a.proyecto_id', $id)
                            ->where('a.cuenta_detalle','1')
                            ->where('a.deleted_at',null)
                            ->select('a.id','a.nombre','a.codigo')
                            ->orderBy('a.codigo', 'asc')
                            ->get()->toJson();
        if($plancuenta){
            return response()->json([
                'plancuenta' => $plancuenta
            ]);
        }
        return response()->json(['error' => 'Algo Salio Mal']);
    }

    public function pdf($proyecto,$fecha_inicial,$fecha_final,$estado,$plancuenta_inicial_id,$plancuenta_final_id){
        
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
            
            $datos = $this->getSearch($proyecto,$fecha_inicial,$fecha_final,$estado,$plancuenta_inicial_id,$plancuenta_final_id);
            
            $proyecto = $datos['proyecto'];
            $plancuenta_inicial = $datos['plancuenta_inicial'];
            $plancuenta_final = $datos['plancuenta_final'];
            $comprobantes = $datos['comprobantes'];
            $fecha_inicial = $datos['fecha_inicial'];
            $fecha_final = $datos['fecha_final'];
            $total_debe = $datos['total_debe'];
            $total_haber = $datos['total_haber'];
            $estado = $datos['estado'];

            $pdf = PDF::loadView('libro-mayor-f.de-1-a-99.pdf',compact(['proyecto','plancuenta_inicial','plancuenta_final','comprobantes','fecha_inicial','fecha_final','total_debe','total_haber','estado']));
            $pdf->setPaper('LETTER', 'portrait');//landscape
            return $pdf->stream();
            
        } catch (\Throwable $th){
            return '[ERROR_500]';
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function excel($proyecto,$fecha_inicial,$fecha_final,$estado,$plancuenta_inicial_id,$plancuenta_final_id){

        set_time_limit(0);ini_set('memory_limit', '1G');
        $datos = $this->getSearch($proyecto,$fecha_inicial,$fecha_final,$estado,$plancuenta_inicial_id,$plancuenta_final_id);
        
        $proyecto = $datos['proyecto'];
        $plancuenta_inicial = $datos['plancuenta_inicial'];
        $plancuenta_final = $datos['plancuenta_final'];
        $comprobantes = $datos['comprobantes'];
        $fecha_inicial = $datos['fecha_inicial'];
        $fecha_final = $datos['fecha_final'];
        $total_debe = $datos['total_debe'];
        $total_haber = $datos['total_haber'];
        $estado = $datos['estado'];
        $file_name = 'LibroMayorDe1a99';

        return Excel::download(new LibroMayorFDe1a99Export($proyecto,$plancuenta_inicial,$plancuenta_final,$comprobantes,$fecha_inicial,$fecha_final,$total_debe,$total_haber,$estado),$file_name . '.xlsx');
    }
}
