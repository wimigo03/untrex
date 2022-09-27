<table>
	<tr>
		<td colspan="10" align="center">
			<h4>_*LIBRO MAYOR DEL 1 AL 99*_</h4>
			<h4>{{strtoupper($proyecto->nombre)}}</h4>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="10" align="left">
			<b>DESDE:&nbsp;</b>{{ \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') }}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>HASTA:&nbsp;</b>{{ \Carbon\Carbon::parse($fecha_final)->format('d/m/Y') }}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>CUENTA INICIAL:&nbsp;</b>{{$plancuenta_inicial->codigo . ' : ' . $plancuenta_inicial->nombre}}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>CUENTA FINAL:&nbsp;</b>{{$plancuenta_final->codigo . ' : ' . $plancuenta_final->nombre}}
		</td>
	</tr>
	<tr>
		<td colspan="10" align="left">
			<b>TOTAL DEBE:&nbsp;</b>Bs.&nbsp;{{$total_debe}}
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
		{{--<th align="center"><b>FACTURA</b></th>--}}
		<th colspan="2" align="center"><b>COMPROBANTE</b></th>
		<th align="center"><b>CODIGO</b></th>
		<th align="center"><b>CUENTA</b></th>
		<th align="center"><b>AUXILIAR</b></th>
		<th align="center"><b>CENTRO</b></th>
		<th align="center"><b>CHEQUE</b></th>
		<th align="center"><b>GLOSA</b></th>
		<th align="center"><b>DEBE</b></th>
		<th align="center"><b>HABER</b></th>
	</tr>
	@foreach ($comprobantes as $datos)
		<tr>
			<td align="center">{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</td>
			@php
				if($datos->status == 0){
					$estado = "B";
				}else{
					$estado = "A";
				}
			@endphp
			<td align="center">
				<span class="tts:down tts-slideIn tts-custom/ font-verdana" aria-label="Ir a comprobante">
					<a href="{{ route('comprobantes.show',$datos->comprobante_id) }}" target="_blank" class="font-verdana-sm">
						{{$datos->nro_comprobante}}
					</a>
				</span>
			</td>
			<td align="center"><b>{{$estado}}</b></td>
			<td align="center">{{$datos->codigo}}</td>
			<td align="left">{{$datos->cuenta}}</td>
			<td align="left">{{$datos->auxiliar}}</td>
			<td align="center">{{$datos->centro}}</td>
			<td align="center">{{$datos->tipo_transaccion . $datos->cheque_nro}}</td>
			<td align="left">{{strtoupper($datos->glosa)}}</td>
			<td align="right">{{$datos->debe}}</td>
			<td align="right">{{$datos->haber}}</td>
		</tr>
	@endforeach
</table>