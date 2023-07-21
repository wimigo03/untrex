<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class LibroBancoExcel implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($proyecto,$plancuenta,$fecha_inicial,$fecha_final,$nro_inicial,$nro_final,$comprobantes,$saldo,$saldo_final,$total_debe,$total_haber,$tipo)
    {
        $this->proyecto = $proyecto;
        $this->plancuenta = $plancuenta;
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->nro_inicial = $nro_inicial;
        $this->nro_final = $nro_final;
        $this->comprobantes = $comprobantes;
        $this->saldo = $saldo;
        $this->saldo_final = $saldo_final;
        $this->total_debe = $total_debe;
        $this->total_haber = $total_haber;
        $this->tipo = $tipo;
    }
    
    public function view(): view{
        $proyecto = $this->proyecto;
        $plancuenta = $this->plancuenta;
        $fecha_inicial = $this->fecha_inicial;
        $fecha_final = $this->fecha_final;
        $nro_inicial = $this->nro_inicial;
        $nro_final = $this->nro_final;
        $comprobantes = $this->comprobantes;
        $saldo = $this->saldo;
        $saldo_final = $this->saldo_final;
        $total_debe = $this->total_debe;
        $total_haber = $this->total_haber;
        $tipo = $this->tipo;
        
        return view('libro-banco.excel',compact('proyecto','plancuenta','fecha_inicial','fecha_final','nro_inicial','nro_final','comprobantes','saldo','saldo_final','total_debe','total_haber','tipo'));
    }
}
