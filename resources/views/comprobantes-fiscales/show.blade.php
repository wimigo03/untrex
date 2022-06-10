@extends('adminlte::page')
@section('title', 'Cotizaciones')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')<br>
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="card-title"><b>DETALLE DE COMPROBANTE</b></div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-2">
                        {{ Form::label('Nro','Nro',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Nro',$comprobante_fiscal->nro_comprobante, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-2">
                        {{ Form::label('Moneda','Moneda',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Moneda',$comprobante_fiscal->moneda, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('Creado por','Creado por',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Creado por',strtoupper($comprobante_fiscal->creador), ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-2">
                        {{ Form::label('Tipo','Tipo',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Tipo',$comprobante_fiscal->tipo_comprobante, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-2">
                        {{ Form::label('Estado','Estado',['class' => 'd-inline font-verdana-bg'])}}
                        @if($comprobante_fiscal->status == 0)
                            {!! Form::text('Estado','PENDIENTE', ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg bg-secondary']) !!}
                        @else
                            @if($comprobante_fiscal->status == 1)
                                {!! Form::text('Estado','APROBADO', ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg bg-success']) !!}
                            @else
                                {!! Form::text('Estado','ANULADO', ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg bg-danger']) !!}
                            @endif
                        @endif
                    </div>
                    <div class="col-md-9">
                        {{ Form::label('Concepto','Concepto',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Concepto',$comprobante_fiscal->concepto, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-3">
                        {{ Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Proyecto',$comprobante_fiscal->nombre, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-2">
                        {{ Form::label('Fecha','Fecha',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Fecha',\Carbon\Carbon::parse($comprobante_fiscal->fecha)->format('d/m/Y'), ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('Aprobado por','Aprobado por',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Aprobado por',strtoupper($comprobante_fiscal->autorizado), ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-2">
                        {{ Form::label('Monto','Monto Total',['class' => 'd-inline font-verdana-bg'])}}
                        {!! Form::text('Monto',$comprobante_fiscal->monto, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
                    </div>
                    <div class="col-md-4 text-right">
                        <br>
                        <a href="{{route('comprobantes.fiscales.index')}}" class="btn btn-sm btn-primary font-verdana-bg">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        @if ($comprobante_fiscal->status == 0 )
                            <a href="{{route('comprobantes.fiscales.aprobar',$comprobante_fiscal->comprobante_id)}}" class="btn btn-sm btn-success font-verdana-bg">
                                <i class="fas fa-check" aria-hidden="true"></i>
                            </a>
                            <a href="{{route('comprobantes.fiscales.rechazar',$comprobante_fiscal->comprobante_id)}}" class="btn btn-sm btn-danger font-verdana-bg">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                        <a href="{{route('comprobantes.fiscales.pdf',$comprobante_fiscal->comprobante_id)}}" class="btn btn-sm btn-warning font-verdana-bg" target="_blank">
                            <i class="fas fa-print" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                @if ($comprobante_fiscal_detalle != null)
                    <div class="form-group row">
                        <div class="col-md-12">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $num = 1;
                                    ?>
                                    @foreach ($comprobante_fiscal_detalle as $datos)
                                        <tr class="font-verdana">
                                            <td width="1%" class="text-left p-1">{{ $num++ }}</td>
                                            <td class="text-left p-1">{{ $datos->codigo . ' - ' . $datos->plancuenta }}</td>
                                            <td class="text-center p-1">{{ $datos->centro }}</td>
                                            <td class="text-left p-1">{{ $datos->auxiliar }}</td>
                                            <td class="text-left p-1">{{ strtoupper($datos->glosa) }}</td>
                                            <td class="text-right p-1">{{number_format($datos->debe,2,'.',',')}}</td>
                                            <td class="text-right p-1">{{number_format($datos->haber,2,'.',',')}}</td>
                                        </tr>
                                    @endforeach
                                        <tr class="font-verdana">
                                            <td colspan="5" class="text-center p-1"><b>TOTAL</b></td>
                                            <td class="text-right p-1"><b>{{number_format($total_debe,2,'.',',')}}</b></td>
                                            <td class="text-right p-1"><b>{{number_format($total_haber,2,'.',',')}}</b></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/style.css">
@stop

@section('js')
    <script>
    </script>
@stop