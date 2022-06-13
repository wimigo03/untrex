<div class="form-group row font-verdana-bg">
    <div class="col-md-12">
        <strong>CUENTA: </strong>{{$plancuenta->nombre}}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-1">
        <strong>DESDE EL: </strong>
    </div>
    <div class="col-md-3">
        {{\Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y')}}
    </div>
    <div class="col-md-2">
        <strong>SALDO INICIAL: </strong>
    </div>
    <div class="col-md-2">
        Bs. {{number_format($saldo,2,'.',',')}}
    </div>
    <div class="col-md-2">
        <strong>TOTAL ABONO: </strong>
    </div>
    <div class="col-md-2">
        Bs. {{number_format($total_debe,2,'.',',')}}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-1">
        <strong>HASTA EL: </strong>
    </div>
    <div class="col-md-3">
        {{\Carbon\Carbon::parse($fecha_final)->format('d/m/Y')}}
    </div>
    <div class="col-md-2">
        <strong>SALDO FINAL: </strong>
    </div>
    <div class="col-md-2">
        Bs. {{number_format($saldo_final,2,'.',',')}}
    </div>
    <div class="col-md-2">
        <strong>TOTAL DEBITO: </strong>
    </div>
    <div class="col-md-2">
        Bs. {{number_format($total_haber,2,'.',',')}}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-12 text-right">
        <a href="{{route('librobanco.index')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Regresar" class="btn btn-sm btn-primary font-verdana-bg">
            <i class="fas fa-angle-double-left"></i>
        </a>
        {{--<a href="{{ route('libromayor.porcuenta.excel',['dat1' => $proyecto,'dat2' => $tipo,'dat3' => $fecha_inicial,'dat4' => $fecha_final,'dat5' => $plancuenta->id]) }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Exportar a Excel" class="btn btn-sm btn-success font-verdana-bg">
            <i class="fas fa-file-excel"></i>
        </a>--}}
        <a href="{{ route('librobanco.pdf',['dat1' => $proyecto,'dat2' => $fecha_inicial,'dat3' => $fecha_final,'dat4' => $nro_cheque_inicial,'dat5' => $nro_cheque_final,'dat6' => $plancuenta->id]) }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Exportar a Pdf" class="btn btn-sm btn-danger font-verdana-bg" target="_blank">
            <i class="fas fa-file-pdf"></i>
        </a>
    </div>
</div>