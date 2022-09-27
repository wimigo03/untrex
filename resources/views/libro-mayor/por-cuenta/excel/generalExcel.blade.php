<table>
	<tr>
		<td colspan="10" align="center">
			<h4>_*LIBRO MAYOR POR CUENTA - GENERAL*_</h4>
			<h4>{{strtoupper($proyecto->nombre)}}</h4>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="10" align="left">
			<b>CUENTA:&nbsp;</b>{{$plancuenta->nombre}}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>DESDE:&nbsp;</b>{{ \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') }}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>SALDO INICIAL:&nbsp;</b>Bs.&nbsp;{{$saldo}}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>TOTAL DEBE:&nbsp;</b>Bs.&nbsp;{{$total_debe}}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>HASTA:&nbsp;</b>{{ \Carbon\Carbon::parse($fecha_final)->format('d/m/Y') }}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>SALDO FINAL:&nbsp;</b>Bs.&nbsp;{{$saldo_final}}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>TOTAL HABER:&nbsp;</b>Bs.&nbsp;{{$total_haber}}
		</td>
	</tr>
</table>
<table>
	<tr>
		<th align="center"><b>FECHA</b></th>
		<th colspan="2" align="center"><b>COMPROBANTE</b></th>
		<th align="center"><b>CENTRO</b></th>
		<th align="center"><b>AUXILIAR</b></th>
		<th align="center"><b>CHEQUE</b></th>
		<th align="center"><b>GLOSA</b></th>
		<th align="center"><b>DEBE</b></th>
		<th align="center"><b>HABER</b></th>
		<th align="center"><b>SALDO</b></th>
	</tr>
	@foreach ($comprobantes as $datos)
		<tr>
			<td align="center">
				{{ \Carbon\Carbon::parse($datos->fecha)->format('d/m/Y') }}
			</td>
			@php
				if($datos->status == 0){
					$estado = "B";
				}else{
					$estado = "A";
				}
			@endphp
			<td align="center">
				{{$datos->nro_comprobante}}
			</td>
			<td align="center">
				<b>{{$estado}}</b>
			</td>
			<td align="center">
				{{$datos->centro}}
			</td>
			<td align="center">
				{{$datos->auxiliar}}
			</td>
			<td align="center">
				{{strtoupper($datos->cheque_nro)}}
			</td>
			<td align="justify">
				{{strtoupper($datos->glosa)}}
			</td>
			<td align="right">
				{{ $datos->debe }}
			</td>
			<td align="right">
				{{ $datos->haber }}
			</td>
			@php
				if($datos->debe > 0){
					$saldo += $datos->debe;
				}else{
					$saldo -= $datos->haber;
				}
			@endphp
			<td align="right">
				{{$saldo}}
			</td>
		</tr>
	@endforeach
</table>