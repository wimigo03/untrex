<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class LibroMayorPorCuentaGeneralExport implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($proyecto,$tipo,$plancuenta,$fecha_inicial,$fecha_final,$comprobantes,$saldo,$saldo_final,$total_debe,$total_haber)
    {
        $this->proyecto = $proyecto;
        $this->tipo = $tipo;
        $this->plancuenta = $plancuenta;
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->comprobantes = $comprobantes;
        $this->saldo = $saldo;
        $this->saldo_final = $saldo_final;
        $this->total_debe = $total_debe;
        $this->total_haber = $total_haber;
    }
    
    public function view(): view{
        $proyecto = $this->proyecto;
        $tipo = $this->tipo;
        $plancuenta = $this->plancuenta;
        $fecha_inicial = $this->fecha_inicial;
        $fecha_final = $this->fecha_final;
        $comprobantes = $this->comprobantes;
        $saldo = $this->saldo;
        $saldo_final = $this->saldo_final;
        $total_debe = $this->total_debe;
        $total_haber = $this->total_haber;
        return view('libro-mayor.por-cuenta.excel.GeneralExcel',compact('proyecto','tipo','plancuenta','fecha_inicial','fecha_final','comprobantes','saldo','saldo_final','total_debe','total_haber'));
    }
}
