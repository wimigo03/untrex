<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class EstadoResultadoFExport implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($ingresos,$costos,$gastos,$totales,$cuentas,$proyecto,$start_date,$end_date,$status_text,$nroMaxColumna,$total)
    {
        $this->ingresos = $ingresos;
        $this->costos = $costos;
        $this->gastos = $gastos;
        $this->totales = $totales;
        $this->cuentas = $cuentas;
        $this->proyecto = $proyecto;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->status_text = $status_text;
        $this->nroMaxColumna = $nroMaxColumna;
        $this->total = $total;
    }
    
    public function view(): view{
        $ingresos = $this->ingresos;
        $costos = $this->costos;
        $gastos = $this->gastos;
        $totales = $this->totales;
        $cuentas = $this->cuentas;
        $proyecto = $this->proyecto;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $status_text = $this->status_text;
        $nroMaxColumna = $this->nroMaxColumna;
        $total = $this->total;
        return view('estado-resultado-f.excel',compact('ingresos','costos','gastos','totales','cuentas','proyecto','start_date','end_date','status_text','nroMaxColumna','total'));
    }
}
