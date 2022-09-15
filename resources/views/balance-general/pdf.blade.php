<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>L&Z ::: Reporte de Impresión</title>
    <style>
        html {margin: 30px 50px 50px 55px;font-size: 10px;font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";font-size: 0.75rem;background-color: #ffffff;}
        .border-bottom {border-bottom: 1px solid #000000;border-collapse: collapse;}
        .page-break {page-break-after: always;}
    </style> 
</head>
<body>
    <table border="0" width="100%">
        <tr>
            <td align="center">
                <h2>_*{{$proyecto->consorcio . ' - ' . $proyecto->proyecto}}*_</h2>
                <h2>BALANCE GENERAL</h2>
                <font size="10px">
                    DEL {{Carbon\Carbon::parse($start_date)->format('d-m-Y')}}
                    AL {{Carbon\Carbon::parse($end_date)->format('d-m-Y')}}<br>
                    (Expresado en Bolivianos)
                </font>
            </td>
        </tr>
    </table><br>
    <table width="100%" cellpadding="1px" cellspacing="0" border="1">
        <thead {{--class="border-bottom"--}}>
            <tr bgcolor="#adb5bd">
                <th align="left"><font size="9px">CODIGO</font></th>
                <th align="left"><font size="9px">CUENTA</font></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody {{--class="border-bottom"--}}>
            @foreach ($activos as $activo)
                @php
                    $nroPuntos = 1;
                    for ($i=0; $i < strlen($activo->codigo); $i++) { 
                        if($activo->codigo[$i] == '.'){
                            $nroPuntos++;
                        }
                    }
                    $nroColumna = $nroMaxColumna - $nroPuntos;
                @endphp
                <tr>
                    @if ($activo->id == 4)
                        <td align="left"><font size="8px"><b>{{ $activo->codigo }}</b></font></td>
                        <td align="left"><font size="8px"><b>{{ $activo->nombre  }}</b></font></td>
                    @else
                        <td align="left"><font size="8px">{{ $activo->codigo }}</font></td>
                        <td align="left"><font size="8px">{{ $activo->nombre  }}</font></td>
                    @endif
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="8px">&nbsp;</font></td>
                    @endfor
                    <td align="right">
                        <font size="8px">
                            @if (isset($totales[$activo->id]))
                                @if ($activo->id == 4)
                                    <b>{{number_format($totales[$activo->id],2,'.',',')}}</b>
                                @else
                                    {{number_format($totales[$activo->id],2,'.',',')}}
                                @endif
                            @endif
                            
                        </font>
                    </td>
                    @php
                        $nroColumna = $nroMaxColumna - $nroColumna -1;
                    @endphp
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="8px">&nbsp;</font></td>
                    @endfor
                </tr>
            @endforeach
            @foreach ($pasivos as $pasivo)
                @php
                    $nroPuntos = 1;
                    for ($i=0; $i < strlen($pasivo->codigo); $i++) { 
                        if($pasivo->codigo[$i] == '.'){
                            $nroPuntos++;
                        }
                    }
                    $nroColumna = $nroMaxColumna - $nroPuntos;
                @endphp
                <tr>
                    @if ($pasivo->id == 5)
                        <td align="left"><font size="8px"><b>{{ $pasivo->codigo }}</b></font></td>
                        <td align="left"><font size="8px"><b>{{ $pasivo->nombre }}</b></font></td>
                    @else
                        <td align="left"><font size="8px">{{ $pasivo->codigo }}</font></td>
                        <td align="left"><font size="8px">{{ $pasivo->nombre  }}</font></td>
                    @endif
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="8px">&nbsp;</font></td>
                    @endfor
                        <td align="right">
                            <font size="8px">
                                @if (isset($totales[$pasivo->id]))
                                    @if ($pasivo->id == 5)
                                        <b>{{number_format($totales[$pasivo->id],2,'.',',') }}</b>
                                    @else
                                        {{number_format($totales[$pasivo->id],2,'.',',') }}
                                    @endif
                                @endif
                            </font>
                        </td>
                    @php
                        $nroColumna = $nroMaxColumna - $nroColumna - 1;
                    @endphp
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="8px">&nbsp;</font></td>
                    @endfor
                </tr>
            @endforeach
            @foreach ($patrimonios as $patrimonio)
                @php
                    $nroPuntos = 1;
                    for ($i=0; $i < strlen($patrimonio->codigo); $i++) { 
                        if($patrimonio->codigo[$i] == '.'){
                            $nroPuntos++;
                        }
                    }
                    $nroColumna = $nroMaxColumna - $nroPuntos;
                @endphp
                <tr>
                    @if ($patrimonio->id == 6)
                        <td align="left"><font size="8px"><b>{{ $patrimonio->codigo }}</b></font></td>
                        <td align="left"><font size="8px"><b>{{ $patrimonio->nombre  }}</b></font></td>
                    @else
                        <td align="left"><font size="8px">{{ $patrimonio->codigo }}</font></td>
                        <td align="left"><font size="8px">{{ $patrimonio->nombre  }}</font></td>
                    @endif
                    @for ($i = 0; $i < $nroColumna; $i++)
                    <td><font size="8px">&nbsp;</font></td>
                    @endfor
                    <td align="right">
                        <font size="8px">
                            @if (isset($totales[$patrimonio->id]))
                                @if ($patrimonio->id == 6)
                                    <b>{{number_format($totales[$patrimonio->id],2,'.',',') }}</b>
                                @else
                                    {{number_format($totales[$patrimonio->id],2,'.',',') }}
                                @endif
                            @endif
                        </font>
                    </td>
                    @php
                        $nroColumna = $nroMaxColumna - $nroColumna - 1;
                    @endphp
                    @for ($i = 0; $i < $nroColumna; $i++)
                    <td><font size="8px">&nbsp;</font></td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <td align="left"><strong><font size="8px">TOTAL:</font></strong></td>
            <td></td>
            <td></td>
            <td align="right"><strong><font size="8px">ACTIVO&nbsp;</strong></font></td>
            <td align="right"><strong><font size="8px">{{number_format($total_activo,2,'.',',')}}&nbsp;</strong></font></td>
            <td align="left"><strong><font size="8px">{{number_format($total_capital_pasivo,2,'.',',')}}</strong></font></td>
            <td align="left"><strong><font size="8px">&nbsp;CAPITAL + PASIVO</strong></font></td>
        </tfoot>
    </table>
    {{--<div class="page-break"></div>--}}
</body>
</html>
{{--$pdf->text(270,730) son alineacion horizontal y vertical--}}
<script type="text/php">
    if(isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica Neue, sans-serif", "normal");
            $pdf->text(40, 765, "Fecha: {{ date('d/m/Y H:i') }} / Usuario: {{ Auth()->user()->username }}", $font, 7); 
            $pdf->text(530, 765, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 7); 
        ');
    }
</script>