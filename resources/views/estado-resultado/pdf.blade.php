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
                <h2>_*ESTADO DE RESULTADO*_</h2>
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
            <tr>
                <th align="left"><font size="10px">CODIGO</font></th>
                <th align="left"><font size="10px">CUENTA</font></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody {{--class="border-bottom"--}}>
            @foreach ($ingresos as $ing)
                @php
                    $nroPuntos = 1;
                    for ($i=0; $i < strlen($ing->codigo); $i++) { 
                        if($ing->codigo[$i] == '.'){
                            $nroPuntos++;
                        }
                    }
                    $nroColumna = $nroMaxColumna - $nroPuntos;
                @endphp
                <tr>
                    <td align="left"><font size="9px">{{ $ing->codigo }}</font></td>
                    <td align="left"><font size="9px">{{ $ing->nombre  }}</font></td>
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="9px">&nbsp;</font></td>
                    @endfor
                    <td align="right">
                        <font size="9px">
                            @if (isset($totales[$ing->id]))
                                {{number_format($totales[$ing->id],2,'.',',') }}
                            @endif
                        </font>
                    </td>
                    @php
                        $nroColumna = $nroMaxColumna - $nroColumna -1;
                    @endphp
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="9px">&nbsp;</font></td>
                    @endfor
                </tr>
            @endforeach
            @foreach ($costos as $costo)
                @php
                    $nroPuntos = 1;
                    for ($i=0; $i < strlen($costo->codigo); $i++) { 
                        if($costo->codigo[$i] == '.'){
                            $nroPuntos++;
                        }
                    }
                    $nroColumna = $nroMaxColumna - $nroPuntos;
                @endphp
                <tr>
                    <td align="left"><font size="9px">{{ $costo->codigo }}</font></td>
                    <td align="left"><font size="9px">{{ $costo->nombre  }}</font></td>
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="8px">&nbsp;</font></td>
                    @endfor
                        <td align="right">
                            <font size="9px">
                                @if (isset($totales[$costo->id]))
                                    {{number_format($totales[$costo->id],2,'.',',') }}
                                @endif
                            </font>
                        </td>
                    @php
                        $nroColumna = $nroMaxColumna - $nroColumna - 1;
                    @endphp
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td><font size="9px">&nbsp;</font></td>
                    @endfor
                </tr>
            @endforeach
            @foreach ($gastos as $gasto)
                @php
                    $nroPuntos = 1;
                    for ($i=0; $i < strlen($gasto->codigo); $i++) { 
                        if($gasto->codigo[$i] == '.'){
                            $nroPuntos++;
                        }
                    }
                    $nroColumna = $nroMaxColumna - $nroPuntos;
                @endphp
                <tr>
                    <td align="left"><font size="9px">{{ $gasto->codigo }}</font></td>
                    <td align="left"><font size="9px">{{ $gasto->nombre  }}</font></td>
                    @for ($i = 0; $i < $nroColumna; $i++)
                    <td><font size="9px">&nbsp;</font></td>
                    @endfor
                    <td align="right">
                        <font size="9px">
                            @if (isset($totales[$gasto->id]))
                                {{number_format($totales[$gasto->id],2,'.',',') }}
                            @endif
                        </font>
                    </td>
                    @php
                        $nroColumna = $nroMaxColumna - $nroColumna - 1;
                    @endphp
                    @for ($i = 0; $i < $nroColumna; $i++)
                    <td><font size="9px">&nbsp;</font></td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <td><strong><font size="9px">TOTAL:</font></strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right"><font size="9px">{{number_format($total,2,'.',',')}}</font></td>
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