<table>
    <tr>
        <td colspan="2">
            <b>CUENTA: </b>
        </td>
        <td colspan="3">
            {{$plancuenta->nombre}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>TIPO: </b>
        </td>
        <td colspan="3">
            @if ($tipo == 'Todo')
                TRANSFERENCIA / CHEQUE 
            @else
                @if ($tipo == 'Transferencia')
                    TRANSFERENCIA 
                @else
                    CHEQUE 
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>DESDE EL: </b>
        </td>
        <td colspan="3">
            {{\Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y')}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>SALDO INICIAL: </b>
        </td>
        <td colspan="3">
            Bs. {{$saldo}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>TOTAL ABONO: </b>
        </td>
        <td colspan="3">
            Bs. {{$total_debe}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>HASTA EL: </b>
        </td>
        <td colspan="3">
            {{\Carbon\Carbon::parse($fecha_final)->format('d/m/Y')}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>SALDO FINAL: </b>
        </td>
        <td colspan="3">
            Bs. {{$saldo_final}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>TOTAL DEBITO: </b>
        </td>
        <td colspan="3">
            Bs. {{$total_haber}}
        </td>
    </tr>
</table>

@if ($comprobantes != null)
    <table>
        <tr>
            <td><b>FECHA</b></td>
            <td><b>COMPROBANTE</b></td>
            <td><b>ESTADO</b></td>
            <td><b>CHEQ/TRANF</b></td>
            <td><b>A LA ORDEN</b></td>
            <td><b>GLOSA</b></td>
            <td><b>ABONO</b></td>
            <td><b>DEBITO</b></td>
            <td><b>SALDO</b></td>
        </tr>
        @foreach ($comprobantes as $datos)
            <tr>
                <td>{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</td>
                @php
                    if($datos->status == 0){
                        $estado = "P";
                    }else{
                        $estado = "A";
                    }
                @endphp
                <td>{{$datos->nro_comprobante}}</td>
                <td><b>{{$estado}}</b></td>
                <td>
                    @if($datos->tipo_transaccion == 'TRANSFERENCIA')
                        TF-{{strtoupper($datos->cheque_nro)}}
                    @else
                        @if($datos->tipo_transaccion == 'CHEQUE')
                            CH-{{strtoupper($datos->cheque_nro)}}
                        @else
                            STF/SCH
                        @endif
                    @endif
                </td>
                <td>{{strtoupper($datos->cheque_orden)}}</td>
                <td>{{strtoupper($datos->glosa)}}</td>
                <td>{{$datos->debe}}</td>
                <td>{{$datos->haber}}</td>
                @php
                    if($datos->debe > 0){
                        $saldo += $datos->debe;
                    }else{
                        $saldo -= $datos->haber;
                    }
                @endphp
                <td>{{$saldo}}</td>
            </tr>
        @endforeach
    </table>
@endif