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
            <td width="20%" align="center">
                {{--<img src={{ "img/logo_" . $empresa->id .".png" }} alt="LOGO" width="150" height="60" /><br>
                <font size="7px">
                    {{ $empresa->razon_social }}<br>
                    {{ $empresa->direccion }}<br>
                    NIT: {{ $empresa->nit }}</font>--}}
            </td>
            <td align="center">
                <h3>_*LIBRO BANCO*_</h3>
				<h3>{{strtoupper($proyecto->nombre)}}</h3>
            </td>
            <td width="20%" align="center">&nbsp;</td>
        </tr>
    </table><br>
    <table border="2" cellpadding="0px" cellspacing="0px" width="100%">
        <tr>
			<td>
				<table border="0" cellpadding="0px" cellspacing="10px" width="100%">
					<tr>
						<td colspan="6" align="left">
							<font size="10px"><b>CUENTA:&nbsp;</b>{{$plancuenta->nombre}}</font>
						</td>
					</tr>
					<tr>
						<td align="left">
							<font size="10px"><b>DESDE:&nbsp;</b></font>
						</td>
						<td align="left">
							<font size="10px">{{ \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') }}</font>
						</td>
						<td align="right">
							<font size="10px"><b>SALDO INICIAL:</b></font>
						</td>
						<td align="left">
							<font size="10px">Bs.&nbsp;{{ number_format($saldo,2,'.',',') }}</font>
						</td>
						<td align="right">
							<font size="10px"><b>TOTAL ABONO:</b></font>
						</td>
						<td align="left">
							<font size="10px">Bs.&nbsp;{{ number_format($total_debe,2,'.',',') }}</font>
						</td>
					</tr>
					<tr>
						<td align="left">
							<font size="10px"><b>HASTA:&nbsp;</b></font>
						</td>
						<td align="left">
							<font size="10px">{{ \Carbon\Carbon::parse($fecha_final)->format('d/m/Y') }}</font>
						</td>
						<td align="right">
							<font size="10px"><b>SALDO FINAL:</b></font>
						</td>
						<td align="left">
							<font size="10px">Bs.&nbsp;{{number_format($saldo_final,2,'.',',')}}</font>
						</td>
						<td align="right">
							<font size="10px"><b>TOTAL DEBITO:</b></font>
						</td>
						<td align="left">
							<font size="10px">Bs.&nbsp;{{number_format($total_haber,2,'.',',')}}</font>
						</td>
					</tr>
				</table>
            </td>
		</tr>
    </table><br>
    <table width="100%" cellpadding="5px" cellspacing="0" border="0">
        <thead class="border-bottom">
            <tr>
                <th align="center"><font size="10px">FECHA</font></th>
                <th colspan="2" align="center"><font size="10px">COMPROBANTE</font></th>
                <th align="center"><font size="10px">NRO CHEQUE</font></th>
				<th align="center"><font size="10px">CHEQUE ORDEN</font></th>
                <th align="center"><font size="10px">GLOSA</font></th>
                <th align="center"><font size="10px">DEBE</font></th>
                <th align="center"><font size="10px">HABER</font></th>
                <th align="center"><font size="10px">SALDO</font></th>
            </tr>
        </thead>
		<tbody>
			@foreach ($comprobantes as $datos)
				<tr>
					<td align="center" valign="top">
						<font size="9px">{{ \Carbon\Carbon::parse($datos->fecha)->format('d/m/Y') }}</font>
					</td>
					@php
						if($datos->status == 0){
							$estado = "P";
						}else{
							$estado = "A";
						}
					@endphp
					<td width="50" align="center" valign="top">
						<font size="9px">{{$datos->nro_comprobante}}</font>
					</td>
					<td align="center" valign="top">
						<font size="9px"><b>{{$estado}}</b></font>
					</td>
					<td align="center" valign="top">
						<font size="9px">{{strtoupper($datos->cheque_nro)}}</font>
					</td>
					<td align="center" valign="top">
						<font size="9px">{{strtoupper($datos->cheque_orden)}}</font>
					</td>
					@php
						$glosa = $datos->glosa; if(strlen($glosa) > 90){ $glosa = substr($glosa,0,90) . '...';}
					@endphp
					<td align="justify" valign="top">
						<font size="9px">{{strtoupper($glosa)}}</font>
					</td>
					<td align="right" valign="top">
						<font size="9px">{{ number_format($datos->debe,2,'.',',') }}</font>
					</td>
					<td align="right" valign="top">
						<font size="9px">{{ number_format($datos->haber,2,'.',',') }}</font>
					</td>
					@php
						if($datos->debe > 0){
							$saldo += $datos->debe;
						}else{
							$saldo -= $datos->haber;
						}
					@endphp
					<td align="right" valign="top">
						<font size="9px">{{ number_format($saldo,2,'.',',') }}</font>
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