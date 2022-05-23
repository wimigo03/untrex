<div class="form-group row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="font-verdana">
                    <td class="text-center p-1"><b>ID</b></td>
                    <td class="text-center p-1"><b>FECHA</b></td>
                    <td class="text-center p-1"><b>NIT</b></td>
                    <td class="text-center p-1"><b>RAZON SOCIAL</b></td>
                    <td class="text-center p-1"><b>NRO. FACT.</b></td>
                    <td class="text-center p-1"><b>NRO. AUT.</b></td>
                    <td class="text-center p-1"><b>COD. CONT.</b></td>
                    <td class="text-center p-1"><b>IMP. <br>TOTAL <br>COMPRA</b></td>
                    <td class="text-center p-1"><b>IMP. <br>NO SUJETO <br>IVA CF</b></td>
                    <td class="text-center p-1"><b>SUB <br>TOTAL</b></td>
                    <td class="text-center p-1"><b>DESC.</b></td>
                    <td class="text-center p-1"><b>IMP. <br>BASE <br>CREDITO<br>FISCAL</b></td>
                    <td class="text-center p-1"><b>CRED. <br>FISCAL</b></td>
                    <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_credito_fiscal = 0;
                @endphp
                @foreach ($facturas as $datos)
                    <tr class="font-verdana">
                        <td width="1%" class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-center p-1">{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</td>
                        <td class="text-left p-1">
                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="N° dui.  {{$datos->nro_dui}}">
                                {{ $datos->nit }}
                            </a>
                        </td>
                        <td class="text-left p-1">{{ strtoupper($datos->razon_social) }}</td>
                        <td class="text-left p-1">{{ $datos->numero }}</td>
                        <td class="text-left p-1">{{ $datos->nro_autorizacion }}</td>
                        <td class="text-left p-1">{{ $datos->cod_control }}</td>
                        <td class="text-right p-1">{{ number_format($datos->monto,2,'.',',') }}</td>
                        <td class="text-right p-1">{{ number_format($datos->excento,2,'.',',') }}</td>
                        @php
                            $subtotal = $datos->monto - $datos->excento;
                            $importe_base_c_f = $subtotal - $datos->descuento;
                            $credito_fiscal = $importe_base_c_f * 0.13;
                            $total_credito_fiscal = $total_credito_fiscal + $credito_fiscal;
                        @endphp
                        <td class="text-right p-1">{{ number_format($subtotal,2,'.',',') }}</td>
                        <td class="text-right p-1">{{ number_format($datos->descuento,2,'.',',') }}</td>
                        <td class="text-right p-1">{{ number_format($importe_base_c_f,2,'.',',') }}</td>
                        <td class="text-right p-1">{{ number_format($credito_fiscal,2,'.',',') }}</td>
                        <td class="text-center p-1">
                            <a href="{{route('facturas.comprobante.delete', $datos->id )}}" class="btn btn-xs btn-danger">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>    
        </table>
    </div>
</div>
{!! Form::open(['route'=>'facturas.comprobante.store.factura','onsubmit'=>"return confirm('Esta seguro de procesar esta solicitud?');"]) !!}
    <div class="form-group row">
        {{Form::hidden('comprobante_id',$comprobante->id)}}
        {{Form::hidden('glosa',$comprobante->concepto)}}
        <div class="col-md-4">
            {{Form::label('plan_cuenta_iva','Cuenta IVA',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::select('plan_cuenta_iva',$plan_cuentas_iva,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuenta_iva') ? ' is-invalid' : '' ),'id'=>'plan_cuenta_iva']) !!}
            {!! $errors->first('plan_cuenta_iva','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-2">
            {{Form::label('credito','IVA:',['class'=>'d-inline font-verdana-bg'])}} 
            <div class="input-group input-group-sm mb-1">            
                {{Form::text('credito',$total_credito_fiscal,['class'=>'form-control form-control-sm bg-warning','readonly'=>'readonly'])}}
                <div class="input-group-append">
                    <span class="input-group-text">BS</span>
                </div>
            </div>  
        </div>
        <div class="col-md-4">
            {{Form::label('plan_cuenta_haber','Cuenta Haber',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::select('plan_cuenta_haber',$plan_cuentas_2,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuenta_haber') ? ' is-invalid' : '' ),'id'=>'plan_cuenta_haber']) !!}
            {!! $errors->first('plan_cuenta_haber','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-2 text-right">
            <br>
            <button type="submit" class="btn btn-primary font-verdana-bg">
                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Finalizar&nbsp;
            </button>
        </div>
    </div>
{!! Form::close()!!}
{{--<div class="form-group row font-verdana-bg">
    @if ($comprobante->status_validate == 1)
        <div class="col-md-12 text-right">
            <a href="{{route('comprobantes.index')}}" class="btn btn-secondary font-verdana-bg">
                <i class="fas fa-reply" aria-hidden="true"></i>&nbsp;Volver&nbsp;
            </a>
        </div>
    @else
        <div class="col-md-12 text-right">
            <a href="{{route('comprobantes.index')}}" class="btn btn-danger font-verdana-bg">
                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
            </a>
            <button type="submit" class="btn btn-primary font-verdana-bg">
                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Finalizar&nbsp;
            </button>
        </div>
    @endif
</div>--}}