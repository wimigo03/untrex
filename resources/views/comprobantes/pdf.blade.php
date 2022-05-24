<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>L&Z - UNTRES ::: Reporte de Impresión</title>
    <style>
        html {
			margin: 30px 50px 50px 55px;font-size: 10px;
			font-family: "verdana,arial,helvetica", -apple-system, BlinkMacSystemFont;
			font-size: 0.75rem;
			background-color: #ffffff;
		}
    </style>
</head>
	<body>
		<table border="0" width="100%">
			<tr>
				<td rowspan="2" width="25%" align="center" valign="top">
					<font size="13px"><b>A S O C I A C I O N</b></font><br> 
					<font size="10px"><b>L O P E Z - U N T R E S</b></font>
				</td>
				<td rowspan="2" align="center">
					@if ($i_f == 1)
						<font size="16px"><b>_*COMPROBANTE DE {{ $comprobante->tipo_comprobante }}*_</b></font><br>
					@else
						<font size="16px"><b>COMPROBANTE DE {{ $comprobante->tipo_comprobante }}</b></font><br>
					@endif
					<font size="13px"><b>{{ $estado }}</b></font>
				</td>
				<td width="25%" valing="top">
					<table border="1" width="100%">
						<tr>
							<td><font size="10px"><b>Nro:</b></font></td>
							<td align="right"><font size="10px">{{ $comprobante->nro_comprobante}}</font></td>
						</tr>
					</table>
					<table border="1" width="100%">
						<tr>
							<td><font size="10px"><b>Fecha:</b><font></td>
							<td align="right"><font size="10px">{{ date('d/m/Y', strtotime($comprobante->fecha))}}</font></td>
						</tr>
					</table>
					<table border="1" width="100%">
						<tr>
							<td><font size="10px"><b>T.C:&nbsp;</b>{{ $comprobante->tipo_cambio}}</font></td>
							<td align="right"><font size="10px"><b>UFV:&nbsp;</b>{{ $comprobante->ufv}}</font></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="25%" align="center">
					<table width="100%" border="1">
						<tr>
							<td width="15%" align="center"><font size="10px"><b>G.</b></font></td>
							<td width="15%" align="center"><font size="10px">{{ date("Y", strtotime($comprobante->fecha)) }}</font></td>
							<td width="15%" align="center"><font size="10px"><b>M.</b><font></td>
							<td width="15%" align="center"><font size="10px">{{ date("m", strtotime($comprobante->fecha))}}</font></td>
							<td width="15%" align="center"><font size="10px"><b>P.</b></font></td>
							<td width="15%" align="center" id="pagina"><font size="10px"><span class="page">1</span></font></td>
						</tr>
				</table>
				</td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="2">
						@if($comprobante->tipo_comprobante != 'TRASPASO')
							<tr>
								<td valign="top" colspan="3">
									<font size="10px">
										<b>{{$comprobante->tipo_comprobante}}:</b>&nbsp;{{$comprobante->entregado_recibido}}
									</font>
								</td>
							</tr>
						@endif
							<tr>
								<td valign="top" colspan="3">
									<font size="10px">
										<b>Por Concepto de:</b>&nbsp;{{strtoupper($comprobante->concepto)}}
									</font>
								</td>
							</tr>
							<tr>
								<td width="5%" valign="top">
									<font size="10px">
										<b>Tipo de Moneda:</b>&nbsp;{{ $comprobante->moneda }}.
									</font>
								</td>
								<td width="5%" valign="top">
									<font size="10px"><b>Cheques:</b>&nbsp;
										@foreach ($detalles_comprobantes_cheques as $cheque)
											+ CH-{{$cheque->cheque_nro}}
										@endforeach
									</font>
								</td>
								<td width="10%" valign="top">
									<font size="10px"><b>Bancos:</b>&nbsp;
										@foreach ($detalles_comprobantes_cheques as $cheque)
											+ {{--$cheque->plancuenta->nombre--}}
										@endforeach
									</font>
								</td>
							</tr>
					</table>	
				</td>
			</tr>
		</table><br>
		<table width="100%" cellpadding="5px" cellspacing="0" border="0">
			<thead style="border-bottom: 1px solid #000000;">
				<tr bgcolor="#6c757d">
					<td width="7%" align="left"><font color="#ffffff" size="9px"><b>CODIGO</b></font></td>
					<td width="40%" align="center"><font color="#ffffff" size="9px"><b>DESCRIPCION / GLOSA</b></font></td>
					<td align="center"><font color="#ffffff" size="9px"><b>PROY./CEN.</b></font></td>
					<td align="center"><font color="#ffffff" size="9px"><b>DEBE(BS.)</b></font></td>
					<td align="center"><font color="#ffffff" size="9px"><b>HABER(BS.)</b></font></td>
					<td align="center"><font color="#ffffff" size="9px"><b>DEBE($US)</b></font></td>
					<td align="center"><font color="#ffffff" size="9px"><b>HABER($US)</b></font></td>
				</tr>
			</thead>
				@php
					$total_debe_bs = 0;
					$total_haber_bs = 0;
					$total_debe_dolares = 0;
					$total_haber_dolares = 0;
					$contColor = 1;
				@endphp
				@foreach ($comprobante_detalle as $datos_detalle)
					<?php
						if($contColor % 2 == 0){
							$color = "#ffffff";
						}else{
							$color = "#f8f9fa";
						}
						if($comprobante->moneda == "BS"){
							$debe_bs = $datos_detalle->debe;
							$haber_bs = $datos_detalle->haber;
							$debe_dolares = $datos_detalle->debe / $comprobante->tipo_cambio;
							$haber_dolares = $datos_detalle->haber / $comprobante->tipo_cambio;
						}else{
							$debe_bs = $datos_detalle->debe * $comprobante->tipo_cambio;
							$haber_bs = $datos_detalle->haber * $comprobante->tipo_cambio;
							$debe_dolares = $datos_detalle->debe;
							$haber_dolares = $datos_detalle->haber;
						}
						$total_debe_bs = $total_debe_bs + $debe_bs;
						$total_haber_bs = $total_haber_bs + $haber_bs;
						$total_debe_dolares = $total_debe_dolares + $debe_dolares;
						$total_haber_dolares = $total_haber_dolares + $haber_dolares;
					?>
					<tr bgcolor="{{ $color }}">
						<td align="left" valign="top"><font size="8px">{{$datos_detalle->codigo}}</font></td>
						<td align="left" valign="top"><font size="8px">
							@if($datos_detalle->haber != 0)
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="10%">&nbsp;</td>
										<td>
											<u>{{$datos_detalle->plancuenta}}
												@if(!is_null($datos_detalle->plancuentaauxiliar_id) && $datos_detalle->plancuentaauxiliar_id)
													@if (!isset($datos_detalle->auxiliar))
														- [ERROR 2]
													@else
														- {{ $datos_detalle->auxiliar }}
													@endif
												@else
													&nbsp;
												@endif
											</u><br>
											@if($datos_detalle->cheque_nro)
												CH-{{$datos_detalle->cheque_nro}}
											@endif
											{{ $datos_detalle->glosa }}
										</td>
									</tr>
								</table>
							@else
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="90%">
											<u>{{ strtoupper($datos_detalle->plancuenta) }}
												@if(!is_null($datos_detalle->plancuentaauxiliar_id) && $datos_detalle->plancuentaauxiliar_id)
													@if (!isset($datos_detalle->auxiliar))
														- [ERROR 2]
													@else
														- {{ $datos_detalle->auxiliar }}
													@endif
												@else
													&nbsp;
												@endif
											</u><br>
											@if($datos_detalle->cheque_nro)
												CH-{{$datos_detalle->cheque_nro}}
											@endif
											{{ $datos_detalle->glosa }}
										</td>
										<td>&nbsp;</td>
									</tr>
								</table>
							@endif
						</font></td>
						<td align="center" valign="top"><font size="9px">
							@if($datos_detalle->proyecto)
								{{$datos_detalle->ab_proyecto}}
							@endif
							@if($datos_detalle->centro)
							/ {{ $datos_detalle->ab_centro }}
							@endif
						</font></td>
						<td align="right" valign="top"><font size="9px">{{ number_format($debe_bs,2,'.',',')  }}</font></td>
						<td align="right" valign="top"><font size="9px">{{ number_format($haber_bs,2,'.',',')  }}</font></td>
						<td align="right" valign="top"><font size="9px">{{ number_format($debe_dolares,2,'.',',')  }}</font></td>
						<td align="right" valign="top"><font size="9px">{{ number_format($haber_dolares,2,'.',',')  }}</font></td>
					</tr>
					@php
						$contColor++;
					@endphp
				@endforeach
					<tr>
						<td style="border-top: 1px solid #000000;" align="right" valign="top" colspan="3"><font size="9px"><b>TOTALES:</b></font></td>
						<td style="border-top: 1px solid #000000;" align="right" valign="top"><font size="9px">{{ number_format($total_debe_bs,2,'.',',') }}</font></td>
						<td style="border-top: 1px solid #000000;" align="right" valign="top"><font size="9px">{{ number_format($total_haber_bs,2,'.',',') }}</font></td>
						<td style="border-top: 1px solid #000000;" align="right" valign="top"><font size="9px">{{ number_format($total_debe_dolares,2,'.',',')}}</font></td>
						<td style="border-top: 1px solid #000000;" align="right" valign="top"><font size="9px">{{ number_format($total_haber_dolares,2,'.',',') }}</font></td>
					</tr>
		</table>

		<table>
			<tr>
				<td>
					<font size="9px"><b>SON: {{$monto_total}} ({{$monto_total_letras}})</b></font>
				</td>
			</tr>
		</table>
		<table border="0" align="center" width="100%">
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr align="center">
				<td><b>_________________________</b></td>
				<td>&nbsp;</td>
				<td><b>_________________________</b></td>
				<td>&nbsp;</td>
				<td><b>_________________________</b></td>
			</tr>
			<tr align="center">
				<td><font size="10px"><b>Elaborado por</b></font></td>
				<td>&nbsp;</td>
				<td><font size="10px"><b>Revisado por</b></font></td>
				<td>&nbsp;</td>
				<td><font size="10px"><b>Aprobado por</b></font></td>
			</tr>
		</table>
	</body>
</html>
@section('js')
	<script type="text/php">
		if(isset($pdf)) {
			$pdf->page_script('
				$font = $fontMetrics->get_font("verdana");
				$pdf->text(40, 765, "ww", $font, 7); 
				$pdf->text(530, 765, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 7); 
			');
		}
	</script>
@stop