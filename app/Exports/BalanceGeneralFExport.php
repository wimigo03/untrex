<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class BalanceGeneralFExport implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($activos,$pasivos,$patrimonios,$totales,$cuentas,$proyecto,$start_date,$end_date,$status_text,$nroMaxColumna,$total_activo,$total_capital_pasivo)
    {
        $this->activos = $activos;
        $this->pasivos = $pasivos;
        $this->patrimonios = $patrimonios;
        $this->totales = $totales;
        $this->cuentas = $cuentas;
        $this->proyecto = $proyecto;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->status_text = $status_text;
        $this->nroMaxColumna = $nroMaxColumna;
        $this->total_activo = $total_activo;
        $this->total_capital_pasivo = $total_capital_pasivo;
    }
    
    public function view(): view{
        $activos = $this->activos;
        $pasivos = $this->pasivos;
        $patrimonios = $this->patrimonios;
        $cuentas = $this->cuentas;
        $totales = $this->totales;
        $proyecto = $this->proyecto;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $status_text = $this->status_text;
        $nroMaxColumna = $this->nroMaxColumna;
        $total_activo = $this->total_activo;
        $total_capital_pasivo = $this->total_capital_pasivo;
        return view('balance-general-f.excel',compact('activos','pasivos','patrimonios','cuentas','totales','proyecto','start_date','end_date','status_text','nroMaxColumna','total_activo','total_capital_pasivo'));
    }
}
