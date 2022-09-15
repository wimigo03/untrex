<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class LibroMayorDe1a99Export implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($proyecto,$plancuenta_inicial,$plancuenta_final,$comprobantes,$fecha_inicial,$fecha_final,$total_debe,$total_haber,$estado)
    {
        $this->proyecto = $proyecto;
        $this->plancuenta_inicial = $plancuenta_inicial;
        $this->plancuenta_final = $plancuenta_final;
        $this->comprobantes = $comprobantes;
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->total_debe = $total_debe;
        $this->total_haber = $total_haber;
        $this->estado = $estado;
    }
    
    public function view(): view{
        $proyecto = $this->proyecto;
        $plancuenta_inicial = $this->plancuenta_inicial;
        $plancuenta_final = $this->plancuenta_final;
        $comprobantes = $this->comprobantes;
        $fecha_inicial = $this->fecha_inicial;
        $fecha_final = $this->fecha_final;
        $total_debe = $this->total_debe;
        $total_haber = $this->total_haber;
        $estado = $this->estado;
        return view('libro-mayor.de-1-a-99.excel',compact('proyecto','plancuenta_inicial','plancuenta_final','comprobantes','fecha_inicial','fecha_final','total_debe','total_haber','estado'));
    }
}
