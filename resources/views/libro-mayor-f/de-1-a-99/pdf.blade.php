<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte de Impresión</title>
    <style>
        html {margin: 30px 50px 50px 55px;font-size: 10px;font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";font-size: 0.75rem;background-color: #ffffff;}
        .border-bottom {border-bottom: 1px solid #000000;border-collapse: collapse;}
        .page-break {page-break-after: always;}
    </style> 
</head>
<body>
    <table border="0" width="100%">
        <tr>
            <td width="20%" align="center">
                {{--<img src={{ "img/logo_" . $empresa->id .".png" }} alt="LOGO" width="150" height="60" /><br>
                <font size="7px">
                    {{ $empresa->razon_social }}<br>
                    {{ $empresa->direccion }}<br>
                    NIT: {{ $empresa->nit }}</font>--}}
            </td>
            <td align="center">
                <h4>LIBRO MAYOR DEL 1 AL 99 - GENERAL</h4>
				<h4>{{strtoupper($proyecto->nombre)}}</h4>
            </td>
            <td width="20%" align="center">&nbsp;</td>
        </tr>
    </table><br>
    <table border="2" cellpadding="0px" cellspacing="0px" width="100%">
        <tr>
			<td>
				<table border="0" cellpadding="0px" cellspacing="10px" width="100%">
					<tr>
						<td align="left">
							<font size="9px"><b>FECHA INICIAL:&nbsp;</b></font>
						</td>
						<td align="left">
							<font size="9px">{{ \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') }}</font>
						</td>
						<td align="right">
							<font size="9px"><b>CUENTA INICIAL:</b></font>
						</td>
						<td align="left">
							<font size="9px">{{$plancuenta_inicial->codigo . ' : ' . $plancuenta_inicial->nombre}}</font>
						</td>
						<td align="right">
							<font size="9px"><b>TOTAL DEBE:</b></font>
						</td>
						<td align="left">
							<font size="9px">Bs.&nbsp;{{ number_format($total_debe,2,'.',',') }}</font>
						</td>
					</tr>
					<tr>
						<td align="left">
							<font size="9px"><b>FECHA FINAL:&nbsp;</b></font>
						</td>
						<td align="left">
							<font size="9px">{{ \Carbon\Carbon::parse($fecha_final)->format('d/m/Y') }}</font>
						</td>
						<td align="right">
							<font size="9px"><b>CUENTA FINAL:</b></font>
						</td>
						<td align="left">
							<font size="9px">{{$plancuenta_final->codigo . ' : ' . $plancuenta_final->nombre}}</font>
						</td>
						<td align="right">
							<font size="9px"><b>TOTAL HABER:</b></font>
						</td>
						<td align="left">
							<font size="9px">Bs.&nbsp;{{number_format($total_haber,2,'.',',')}}</font>
						</td>
					</tr>
				</table>
            </td>
		</tr>
    </table><br>
    <table width="100%" cellpadding="5px" cellspacing="0" border="0">
        <thead class="border-bottom">
            <tr>
				<th align="center"><font size="9px">FECHA</font></th>
				{{--<th align="center"><font size="9px">FACTURA</font></th>--}}
				<th align="center"><font size="9px">COMPROBANTE</font></th>
				<th align="center"><font size="9px">CODIGO</font></th>
				<th align="center"><font size="9px">CUENTA</font></th>
				<th align="center"><font size="9px">AUXILIAR</font></th>
				<th align="center"><font size="9px">CENTRO</font></th>
				<th align="center"><font size="9px">CHEQUE</font></th>
				<th align="center"><font size="9px">GLOSA</font></th>
				<th align="center"><font size="9px">DEBE</font></th>
				<th align="center"><font size="9px">HABER</font></th>
            </tr>
        </thead>
		<tbody>
			@foreach ($comprobantes as $datos)
				<tr>
					<td align="center" valign="top">
						<font size="8px">{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</font>
					</td>
					{{--@php
						$facturas = DB::table('comprobante_facturas as a')
										->join('facturas as b','b.id','a.factura_id')
										->where('a.comprobante_id',$datos->comprobante_id)
										->where('a.estado',1)
										->where('a.deleted_at',null)
										->get();
					@endphp
					<td align="left" valign="top">
						@foreach ( $facturas as $factura)
							<font size="8px">{{'- ' . $factura->numero}}</font><br>
						@endforeach
					</td>--}}
					@php
						if($datos->status == 0){
							$estado = "B";
						}else{
							$estado = "A";
						}
					@endphp
					<td width="15%" align="center" valign="top">
						<span class="tts:down tts-slideIn tts-custom/ font-verdana" aria-label="Ir a comprobante">
							<a href="{{ route('comprobantes.fiscales.show',$datos->comprobante_id) }}" target="_blank" class="font-verdana-sm">
								<font size="8px">{{$datos->nro_comprobante}}</font>
							</a>
							<font size="8px"><b>{{$estado}}</b></font>
						</span>
					</td>
					<td align="center" valign="top">
						<font size="8px">{{$datos->codigo}}</font>
					</td>
					<td align="left" valign="top">
						<font size="8px">{{$datos->cuenta}}</font>
					</td>
					<td align="left" valign="top">
						<font size="8px">{{$datos->auxiliar}}</font>
					</td>
					<td align="center" valign="top">
						<font size="8px">{{$datos->centro}}</font>
					</td>
					<td align="center" valign="top">
						<font size="8px">{{$datos->tipo_transaccion . $datos->cheque_nro}}</font>
					</td>
					<td align="left" valign="top">
						<font size="8px">{{strtoupper($datos->glosa)}}</font>
					</td>
					<td align="right" valign="top">
						<font size="8px">{{number_format($datos->debe,2,'.',',')}}</font>
					</td>
					<td align="right" valign="top">
						<font size="8px">{{number_format($datos->haber,2,'.',',')}}</font>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
<script type="text/php">
    if(isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica Neue, sans-serif", "normal");
            $pdf->text(40, 765, "{{ date('d-m-Y H:i') }} / {{ Auth()->user()->username }}", $font, 7); 
            $pdf->text(530, 765, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 7); 
        ');
    }
</script>