<table>
    <tr>
        <td colspan="4">
            <b>DESDE:&nbsp;</b>{{$fecha_inicial}}
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <b>HASTA:&nbsp;</b>{{$fecha_final}}
        </td>
    </tr>
    <tr>
        <td colspan="4">
            @php
                if($tipo_comprobante == 1){
                    $tipo_comp = "INGRESO";
                }
                if($tipo_comprobante == 2){
                    $tipo_comp = "EGRESO";
                }
                if($tipo_comprobante == 3){
                    $tipo_comp = "TRASPASO";
                }
                if($tipo_comprobante == 4){
                    $tipo_comp = "INGRESO - EGRESO - TRASPASO";
                }
            @endphp
            <b>TIPO:&nbsp;</b>{{$tipo_comp}}
        </td>
    </tr>
    <tr>
        <td colspan="4">
            @php
                if($estado == 0){
                    $estado_comp = "BORRADOR";
                }
                if($estado == 1){
                    $estado_comp = "APROBADO";
                }
                if($estado == 2){
                    $estado_comp = "ANULADO";
                }
                if($estado == 3){
                    $estado_comp = "BORRADOR - APROBADO - ANULADO";
                }
            @endphp
            <b>TIPO:&nbsp;</b>{{$estado_comp}}    
        </td>
    </tr>
</table>
@if (isset($consulta_comprobantes) && (count($consulta_comprobantes) > 0))
    <table>
        <tr>
            <td><b>FECHA</b></td>
            <td><b>COMPROBANTE</b></td>
            <td><b>ESTADO</b></td>
            <td><b>CUENTA</b></td>
            <td><b>DEBE</b></td>
            <td><b>HABER</b></td>
            <td><b>GLOSA</b></td>
        </tr>
        @foreach ($consulta_comprobantes as $datos)
            <tr>
                <td>
                    {{$datos->comprobante != null ? \Carbon\Carbon::parse($datos->comprobante->fecha)->format('d/m/Y') : '#'}}
                </td>
                <td>
                    {{$datos->comprobante != null ? $datos->comprobante->nro_comprobante : '#'}}
                </td>
                <td>
                    {{$datos->comprobante != null ? $datos->comprobante->estado_comp : '#'}}
                </td>
                <td>
                    {{$datos->cuenta != null ? $datos->cuenta->codigo . ' - ' . $datos->cuenta->nombre : '#'}}
                </td>
                <td>
                    {{number_format($datos->debe,2,'.',',')}}
                </td>
                <td>
                    {{number_format($datos->haber,2,'.',',')}}
                </td>
                <td>
                    {{strtoupper($datos->glosa)}}
                </td>
            </tr>
        @endforeach
    </table>
@endif