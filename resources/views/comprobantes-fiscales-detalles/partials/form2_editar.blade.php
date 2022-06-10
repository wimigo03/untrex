<div class="form-group row">
    <div class="col-md-12">
        {{Form::hidden('comprobante_id',$comprobante->id)}}
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="font-verdana">
                    <td class="text-center p-1"><b>NRO</b></td>
                    <td class="text-center p-1"><b>CUENTA</b></td>
                    <td class="text-center p-1"><b>CENTRO</b></td>
                    <td class="text-center p-1"><b>AUXILIAR</b></td>
                    <td class="text-center p-1"><b>GLOSA</b></td>
                    <td class="text-center p-1"><b>DEBE</b></td>
                    <td class="text-center p-1"><b>HABER</b></td>
                    <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $num = 1;
                ?>
                @foreach ($comprobante_detalles as $datos)
                    @php
                        if($datos->comprobante_detalle_id == $comprobante_detalle->id){
                            $editando = 'bg-warning';
                        }else{
                            $editando = null;
                        }
                    @endphp
                    <tr class="font-verdana {{$editando}}">
                        <td width="1%" class="text-left p-1">{{ $num++ }}</td>
                        <td class="text-left p-1">{{ $datos->codigo . ' - ' . $datos->plancuenta }}</td>
                        <td class="text-center p-1">{{ $datos->centro }}</td>
                        <td class="text-left p-1">{{ $datos->auxiliar }}</td>
                        <td class="text-left p-1">{{ strtoupper($datos->glosa) }}</td>
                        <td class="text-right p-1">{{number_format($datos->debe,2,'.',',')}}</td>
                        <td class="text-right p-1">{{number_format($datos->haber,2,'.',',')}}</td>
                        <td class="text-center p-1">
                            <table style="border-collapse:collapse; border: none;">
                                <tr>
                                    <td style="padding: 0;">
                                        <a href="{{route('comprobantesfiscalesdetalles.editar', $datos->comprobante_detalle_id)}}" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endforeach
                    <tr class="font-verdana">
                        <td colspan="5" class="text-center p-1"><b>TOTAL</b></td>
                        <td class="text-right p-1">
                            {{number_format($total_debe,2,'.',',')}}
                            {{Form::hidden('total_debe',$total_debe)}}
                        </td>
                        <td class="text-right p-1">
                            {{number_format($total_haber,2,'.',',')}}
                            {{Form::hidden('total_haber',$total_haber)}}
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>