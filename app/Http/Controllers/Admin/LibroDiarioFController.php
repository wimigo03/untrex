<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use App\Proyectos;
use App\ComprobantesFiscales;
use App\ComprobantesFiscalesDetalle;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibroDiarioExcel;
use DB;
use PDF;

class LibroDiarioFController extends Controller
{
    public function index(){
        $proyectos = Proyectos::pluck('nombre','id');
        return view('libro-diario-f.index',compact('proyectos'));
    }

    public function search(Request $request){
        $request->validate([
            'proyecto'=> 'required',
            'fecha_i'=> 'required',
            'fecha_f'=> 'required',
            'tipo_comp' => 'required',
            'estado' => 'required'
        ]);
        
        $proyecto = Proyectos::find($request->proyecto);
        $fecha_inicial = $request->fecha_i;
        $fecha_final = $request->fecha_f;
        $tipo_comprobante = $request->tipo_comp;
        $estado = $request->estado;
        $consulta_comprobantes = $this->consultaLibroDiarioPaginate($request->proyecto,$request->fecha_i,$request->fecha_f,$request->tipo_comp,$request->estado);
        
        return view('libro-diario-f.search',compact('proyecto','fecha_inicial','fecha_final','tipo_comprobante','estado','consulta_comprobantes'));
    }

    public function consultaLibroDiarioPaginate($proyecto,$fecha_i,$fecha_f,$tipo_comp,$estado){
        $comprobante_detalles = ComprobantesFiscalesDetalle::query()
                                                            ->byProyecto($proyecto)
                                                            ->byFechas($fecha_i,$fecha_f)
                                                            ->byTipoComprobante($tipo_comp)
                                                            ->byEstado($estado)
                                                            ->paginate(30);
        return $comprobante_detalles;
    }

    public function consultaLibroDiarioGet($proyecto,$fecha_i,$fecha_f,$tipo_comp,$estado){
        $comprobante_detalles = ComprobantesFiscalesDetalle::query()
                                                            ->byProyecto($proyecto)
                                                            ->byFechas($fecha_i,$fecha_f)
                                                            ->byTipoComprobante($tipo_comp)
                                                            ->byEstado($estado)
                                                            ->get();
        return $comprobante_detalles;
    }

    public function excel(Request $request){
        $request->validate([
            'proyecto'=> 'required',
            'fecha_i'=> 'required',
            'fecha_f'=> 'required',
            'tipo_comp' => 'required',
            'estado' => 'required'
        ]);
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');

            $proyecto = Proyectos::find($request->proyecto);
            $fecha_inicial = $request->fecha_i;
            $fecha_final = $request->fecha_f;
            $tipo_comprobante = $request->tipo_comp;
            $estado = $request->estado;
            $consulta_comprobantes = $this->consultaLibroDiarioGet($request->proyecto,$request->fecha_i,$request->fecha_f,$request->tipo_comp,$request->estado);

            $file_name = 'libro_diario';
            return Excel::download(new LibroDiarioExcel($proyecto,$fecha_inicial,$fecha_final,$tipo_comprobante,$estado,$consulta_comprobantes),$file_name . '.xlsx');
        } catch (\Throwable $th){
            return '[ERROR_500]';
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}
