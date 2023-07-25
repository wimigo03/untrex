<div class="form-group row font-verdana-bg">
    <div class="col-md-2">
        <strong>DESDE: </strong>{{$fecha_inicial}}
    </div>
    <div class="col-md-2">
        <strong>HASTA: </strong>{{$fecha_final}}
    </div>
    <div class="col-md-4">
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
        <strong>TIPO: </strong>{{$tipo_comp}}
    </div>
    <div class="col-md-4">
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
        <strong>TIPO: </strong>{{$estado_comp}}
    </div>
</div>