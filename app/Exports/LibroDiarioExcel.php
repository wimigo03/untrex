<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class LibroDiarioExcel implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($proyecto,$fecha_inicial,$fecha_final,$tipo_comprobante,$estado,$consulta_comprobantes)
    {
        $this->proyecto = $proyecto;
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->tipo_comprobante = $tipo_comprobante;
        $this->estado = $estado;
        $this->consulta_comprobantes = $consulta_comprobantes;
    }
    
    public function view(): view{
        $proyecto = $this->proyecto;
        $fecha_inicial = $this->fecha_inicial;
        $fecha_final = $this->fecha_final;
        $tipo_comprobante = $this->tipo_comprobante;
        $estado = $this->estado;
        $consulta_comprobantes = $this->consulta_comprobantes;
        
        return view('libro-diario.excel',compact('proyecto','fecha_inicial','fecha_final','tipo_comprobante','estado','consulta_comprobantes'));
    }
}
