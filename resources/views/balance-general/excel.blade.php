<table>
	<tr>
		<td colspan="7" align="center">
			<h4><b>_*BALANCE GENERAL*_</b></h4>
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
            <td align="left">{{ $activo->codigo }}</td>
            <td align="left">{{ $activo->nombre  }}</td>
            @for ($i = 0; $i < $nroColumna; $i++)
                <td></td>
            @endfor
            <td align="right">
                @if (isset($totales[$activo->id]))
                    {{number_format($totales[$activo->id],2,'.',',')}}
                @endif
            </td>
            @php
                $nroColumna = $nroMaxColumna - $nroColumna -1;
            @endphp
            @for ($i = 0; $i < $nroColumna; $i++)
                <td></td>
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
            <td align="left">{{ $pasivo->codigo }}</td>
            <td align="left">{{ $pasivo->nombre  }}</td>
            @for ($i = 0; $i < $nroColumna; $i++)
                <td></td>
            @endfor
            <td align="right">
                @if (isset($totales[$pasivo->id]))
                    {{number_format($totales[$pasivo->id],2,'.',',')}}
                @endif
            </td>
            @php
                $nroColumna = $nroMaxColumna - $nroColumna - 1;
            @endphp
            @for ($i = 0; $i < $nroColumna; $i++)
                <td></td>
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
            <td align="left">{{ $patrimonio->codigo }}</td>
            <td align="left">{{ $patrimonio->nombre  }}</td>
            @for ($i = 0; $i < $nroColumna; $i++)
                <td></td>
            @endfor
            <td align="right">
                @if (isset($totales[$patrimonio->id]))
                    {{number_format($totales[$patrimonio->id],2,'.',',')}}
                @endif
            </td>
            @php
                $nroColumna = $nroMaxColumna - $nroColumna - 1;
            @endphp
            @for ($i = 0; $i < $nroColumna; $i++)
                <td></td>
            @endfor
        </tr>
    @endforeach
    <tr>
        <td align="left"><strong>TOTAL:</strong></td>
        <td></td>
        <td></td>
        <td align="right"><strong>ACTIVO&nbsp;</strong></td>
        <td align="right"><strong>{{number_format($total_activo,2,'.',',')}}</strong></td>
        <td align="left"><strong>{{number_format($total_capital_pasivo,2,'.',',')}}</strong></td>
        <td align="left"><strong>&nbsp;CAPITAL + PASIVO</strong></td>
    </tr>
</table>