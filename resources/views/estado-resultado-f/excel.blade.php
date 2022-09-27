<table>
	<tr>
		<td colspan="7" align="center">
			<h4><b>ESTADO DE RESULTADOS</b></h4>
		</td>
	</tr>
    <tr>
		<td colspan="7" align="center">
			<h4><b>{{strtoupper($proyecto->proyecto)}}</b></h4>
		</td>
	</tr>
    <tr>
		<td colspan="7" align="center">
			<h4><b>{{strtoupper($proyecto->consorcio)}}</b></h4>
		</td>
	</tr>
    <tr>
		<td colspan="7" align="center">
			<h4><b><strong>DEL&nbsp;</strong>{{\Carbon\Carbon::parse($start_date)->format('d/m/Y')}}&nbsp;<strong>&nbsp;AL&nbsp;</strong>{{\Carbon\Carbon::parse($end_date)->format('d/m/Y')}}</b></h4>
		</td>
	</tr>
</table>
<table>
    <tr>
        <td><b>CODIGO</b></td>
        <td><b>CUENTA</b></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
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
        @if (isset($totales[$ing->id]) && $totales[$ing->id] != 0)
            <tr>
                <td align="left">{{ $ing->codigo }}</td>
                <td align="left">{{ $ing->nombre  }}</td>
                @for ($i = 0; $i < $nroColumna; $i++)
                    <td></td>
                @endfor
                <td align="right">
                    {{--@if (isset($totales[$ing->id]))--}}
                        {{number_format($totales[$ing->id],2,'.',',')}}
                    {{--@endif--}}
                </td>
                @php
                    $nroColumna = $nroMaxColumna - $nroColumna -1;
                @endphp
                @for ($i = 0; $i < $nroColumna; $i++)
                    <td></td>
                @endfor
            </tr>
        @endif
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
        @if (isset($totales[$costo->id]) && $totales[$costo->id] != 0)
            <tr>
                <td align="left">{{ $costo->codigo }}</td>
                <td align="left">{{ $costo->nombre  }}</td>
                @for ($i = 0; $i < $nroColumna; $i++)
                    <td></td>
                @endfor
                <td align="right">
                    {{--@if (isset($totales[$costo->id]))--}}
                        {{number_format($totales[$costo->id],2,'.',',')}}
                    {{--@endif--}}
                </td>
                @php
                    $nroColumna = $nroMaxColumna - $nroColumna - 1;
                @endphp
                @for ($i = 0; $i < $nroColumna; $i++)
                    <td></td>
                @endfor
            </tr>
        @endif
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
        @if (isset($totales[$gasto->id]) && $totales[$gasto->id] != 0)
            <tr>
                <td align="left">{{ $gasto->codigo }}</td>
                <td align="left">{{ $gasto->nombre  }}</td>
                @for ($i = 0; $i < $nroColumna; $i++)
                    <td></td>
                @endfor
                <td align="right">
                    {{--@if (isset($totales[$gasto->id]))--}}
                        {{number_format($totales[$gasto->id],2,'.',',')}}
                    {{--@endif--}}
                </td>
                @php
                    $nroColumna = $nroMaxColumna - $nroColumna - 1;
                @endphp
                @for ($i = 0; $i < $nroColumna; $i++)
                    <td></td>
                @endfor
            </tr>
        @endif
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>TOTAL:</strong></td>
        <td align="right"><strong>{{number_format($total,2,'.',',')}}</strong></td>
    </tr>
</table>