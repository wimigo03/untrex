<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class PlanCuentasExcel implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($plancuentas)
    {
        $this->plancuentas = $plancuentas;
    }
    
    public function view(): view{
        $plancuentas = $this->plancuentas;
        return view('plandecuentas.excel',compact('plancuentas'));
    }
}
