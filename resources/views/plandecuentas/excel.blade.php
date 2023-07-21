<table>
	<tr>
		<td colspan="5" align="center">
			<h4><b>_*PLAN DE CUENTAS*_</b></h4>
		</td>
	</tr>
</table>
<table>
    <tr>
        <td>
            <b>CODIGO</b>   
        </td>
        <td>
            <b>CUENTA</b>   
        </td>
        <td align="center">
            <b>¿TIENE AUXILIAR?</b>   
        </td>
        <td align="center">
            <b>¿ES DETALLE?</b>   
        </td>
        <td align="center">
            <b>ESTADO</b>   
        </td>
    </tr>
    @foreach ($plancuentas as $datos)
        <tr>
            <td align="left">
                {{$datos->codigo}}
            </td>
            <td align="left">
                {{$datos->nombre}}
            </td>
            <td align="center">
                {{$datos->auxiliar=='1'?'SI':'NO'}}
            </td>
            <td align="center">
                {{$datos->cuenta_detalle=='1'?'SI':'NO'}}
            </td>
            <td align="center">
                {{$datos->estado=='1'?'ACTIVO':'NO ACTIVO'}}
            </td>
        </tr>
    @endforeach
</table>