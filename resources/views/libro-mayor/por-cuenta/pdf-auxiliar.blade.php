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
                <h3>_*LIBRO MAYOR POR CUENTA - AUXILIAR*_</h3>
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
						<td align="left">
							<font size="10px"><b>CUENTA:&nbsp;</b>{{$plancuenta->nombre}}</font>
						</td>
						<td align="right">
							<font size="10px"><b>DESDE:&nbsp;</b></font>
						</td>
						<td align="right">
							<font size="10px">{{ \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') }}</font>
						</td>
						<td align="right">
							<font size="10px"><b>HASTA:&nbsp;</b></font>
						</td>
						<td align="right">
							<font size="10px">{{ \Carbon\Carbon::parse($fecha_final)->format('d/m/Y') }}</font>
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
				<th align="center"><font size="10px">CENTRO</font></th>
                <th align="center"><font size="10px">CHEQUE</font></th>
                <th align="center"><font size="10px">GLOSA</font></th>
                <th align="center"><font size="10px">DEBE</font></th>
                <th align="center"><font size="10px">HABER</font></th>
                <th align="center"><font size="10px">SALDO</font></th>
            </tr>
        </thead>
		<tbody>
			@foreach ($auxiliares as $datos)
				@php
					$sumarRestar = DB::table('comprobantes as a')
										->join('comprobantes_detalles as b','b.comprobante_id','a.id')
										->join('centros as c','c.id','b.centro_id')
										->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
										->where('a.proyecto_id',$proyecto->id)
										->where('b.plancuenta_id',$plancuenta->id)
										->where('b.plancuentaauxiliar_id',$datos->plancuentaauxiliar_id)
										->where('a.status','!=','2')
										->where('a.fecha','>=',$fecha_saldo_inicial)
										->where('a.fecha','<=',$fecha_inicial)
										->where('b.deleted_at',null)
										->select('b.debe','b.haber')
										->orderBy('a.fecha','asc')
										->get();
					$saldo = 0;
					foreach($sumarRestar as $sumaResta){
						$saldo += $sumaResta->debe;
						$saldo -= $sumaResta->haber;
					}
					$comprobantes_detalle = DB::table('comprobantes as a')
												->join('comprobantes_detalles as b','b.comprobante_id','a.id')
												->join('centros as c','c.id','b.centro_id')
												->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
												->where('a.proyecto_id',$proyecto->id)
												->where('b.plancuenta_id',$plancuenta->id)
												->where('b.plancuentaauxiliar_id',$datos->plancuentaauxiliar_id)
												->where('a.status','!=','2')
												->where('a.fecha','>=',$fecha_inicial)
												->where('a.fecha','<=',$fecha_final)
												->where('b.deleted_at',null)
												->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','c.abreviatura as centro',DB::raw("if(isnull(d.nombre),'S/N',d.nombre) as auxiliar"),'b.cheque_nro','b.glosa','b.debe','b.haber')
												->orderBy('a.fecha','asc')
												->get();
					$saldo_final = $saldo;
					$total_debe = 0;
					$total_haber = 0;
					foreach ($comprobantes_detalle as $comp) {
						$saldo_final += $comp->debe;
						$saldo_final -= $comp->haber;
						$total_debe += $comp->debe;
						$total_haber += $comp->haber;
					}
				@endphp
				<tr bgcolor="#f7f7f7">
					<td colspan="9" align="center"><font size="10px"><b>{{$datos->auxiliar == null ? 'SIN AUXILIAR' : $datos->auxiliar}}</b></font></td>
				</tr>
				{{--<tr bgcolor="#d9534f">--}}
				<tr bgcolor="#f7f7f7">
					<td colspan="9" align="center">
						<font size="10px">
							SALDO INICIAL:&nbsp;Bs. {{number_format($saldo,2,'.',',')}}
							&nbsp;|&nbsp;
							SALDO FINAL:&nbsp;Bs. {{number_format($saldo_final,2,'.',',')}}
							&nbsp;|&nbsp;
							TOTAL DEBE:&nbsp;Bs. {{number_format($total_debe,2,'.',',')}}
							&nbsp;|&nbsp;
							TOTAL HABER:&nbsp;Bs. {{number_format($total_haber,2,'.',',')}}
						</font>
					</td>
				</tr>
					@foreach ($comprobantes_detalle as $data)
						<tr>
							<td align="center"><font size="10px">{{\Carbon\Carbon::parse($data->fecha)->format('d/m/Y')}}</font></td>
							@php
								if($data->status == 0){
									$estado = "P";
									$color = "#f7f7f7";
								}else{
									$estado = "A";
									$color = "#5cb85c";
								}
							@endphp
							<td align="center"><font size="10px">{{$data->nro_comprobante}}</font></td>
							<td align="center"><font size="10px" color="{{$color}}"><b>{{$estado}}</b></font></td>
							<td align="center"><font size="10px">{{$data->centro}}</font></td>
							<td align="center"><font size="10px">{{strtoupper($data->cheque_nro)}}</font></td>
							<td align="left"><font size="10px">{{strtoupper($data->glosa)}}</font></td>
							<td align="right"><font size="10px">{{number_format($data->debe,2,'.',',')}}</font></td>
							<td align="right"><font size="10px">{{number_format($data->haber,2,'.',',')}}</font></td>
							@php
								if($data->debe > 0){
									$saldo += $data->debe;
								}else{
									$saldo -= $data->haber;
								}
							@endphp
							<td align="right"><font size="10px">{{number_format($saldo,2,'.',',')}}</font></td>
						</tr>
					@endforeach
			@endforeach
		</tbody>
	</table>
</body>
</html>
@section('js')
<script type="text/php">
    if(isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica Neue, sans-serif", "normal");
            $pdf->text(40, 765, "{{ date('d-m-Y H:i') }} / {{ Auth()->user()->username }}", $font, 7); 
            $pdf->text(530, 765, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 7); 
        ');
    }
</script>
@stop