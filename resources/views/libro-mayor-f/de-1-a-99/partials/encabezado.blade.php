<div class="form-group row font-verdana">
    <div class="col-md-3">
        <strong>FECHA INICIAL: </strong>
        {{\Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y')}}
    </div>
    <div class="col-md-6">
        <strong>CUENTA INICIAL: </strong>
        {{$plancuenta_inicial->codigo . ' : ' . $plancuenta_inicial->nombre}}
    </div>
    <div class="col-md-3">
        <strong>TOTAL DEBE: </strong>
        Bs. {{number_format($total_debe,2,'.',',')}}
    </div>
</div>
<div class="form-group row font-verdana">
    <div class="col-md-3">
        <strong>FECHA FINAL: </strong>
        {{\Carbon\Carbon::parse($fecha_final)->format('d/m/Y')}}
    </div>
    <div class="col-md-6">
        <strong>CUENTA FINAL: </strong>
        {{$plancuenta_final->codigo . ' : ' . $plancuenta_final->nombre}}
    </div>
    <div class="col-md-3">
        <strong>TOTAL HABER: </strong>
        Bs. {{number_format($total_haber,2,'.',',')}}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-12 text-right">
        <span class="tts:left tts-slideIn tts-custom/ font-verdana" aria-label="Retroceder">
            <a href="{{route('libromayorf.de1a99.index')}}" class="btn btn-sm btn-primary font-verdana-bg" onclick="hide();">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </span>
        <span class="tts:left tts-slideIn tts-custom/ font-verdana" aria-label="Exportar a Excel">
            <a href="{{ route('libromayorf.de1a99.excel',['dat1' => $proyecto->id,'dat2' => $fecha_inicial,'dat3' => $fecha_final,'dat4' => $estado,'dat5' => $plancuenta_inicial->id,'dat6' => $plancuenta_final->id]) }}" class="btn btn-sm btn-success font-verdana-bg" onclick="hide();">
                <i class="fas fa-file-excel"></i>
            </a>
        </span>
        <span class="tts:left tts-slideIn tts-custom/ font-verdana" aria-label="Exportar a Pdf">
            <a href="{{ route('libromayorf.de1a99.pdf',['dat1' => $proyecto->id,'dat2' => $fecha_inicial,'dat3' => $fecha_final,'dat4' => $estado,'dat5' => $plancuenta_inicial->id,'dat6' => $plancuenta_final->id]) }}" class="btn btn-sm btn-danger font-verdana-bg" target="blank" onclick="hide();">
                <i class="fas fa-file-pdf"></i>
            </a>
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-2x fa-fw spinner-btn-hide" style="display: none;"></i>
    </div>
</div>