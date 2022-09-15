<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class LibroMayorFPorCuentaAuxiliar1Export implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($fecha_saldo_inicial,$proyecto,$tipo,$plancuenta,$fecha_inicial,$fecha_final,$auxiliares)
    {
        $this->fecha_saldo_inicial = $fecha_saldo_inicial;
        $this->proyecto = $proyecto;
        $this->tipo = $tipo;
        $this->plancuenta = $plancuenta;
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->auxiliares = $auxiliares;
    }
    
    public function view(): view{
        $fecha_saldo_inicial = $this->fecha_saldo_inicial;
        $proyecto = $this->proyecto;
        $tipo = $this->tipo;
        $plancuenta = $this->plancuenta;
        $fecha_inicial = $this->fecha_inicial;
        $fecha_final = $this->fecha_final;
        $auxiliares = $this->auxiliares;
        
        return view('libro-mayor-f.por-cuenta.excel.auxiliarExcel',compact('fecha_saldo_inicial','proyecto','tipo','plancuenta','fecha_inicial','fecha_final','auxiliares'));
    }
}
