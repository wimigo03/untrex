@extends('adminlte::page')
@section('title', 'Cotizaciones')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>COMPROBANTES</b></div>
            </div>
            <div class="card-body">
                {{--{!! Form::model(Request::all(),['route'=> ['cotizaciones.search'],'onsubmit' => "return validacion_form()"]) !!}
                    @include('cotizaciones.partials.search')
                {!! Form::close()!!}--}}
                <div class="form-group row">
                    <div class="col-md-12 text-left">
                        <a href="{{route('comprobantes.create')}}" class="btn btn-success font-verdana-bg">
                            <i class="fas fa-plus"></i>&nbsp;Crear Comprobante
                        </a>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <div class="table-responsive table-striped">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana-bg">
                                        <td class="text-center p-1"><b>ID</b></td>
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        <td class="text-center p-1"><b>COMPROBANTE</b></td>
                                        <td class="text-center p-1"><b>CONCEPTO</b></td>
                                        <td class="text-center p-1"><b>MONTO</b></td>
                                        <td class="text-center p-1"><b>ESTADO</b></td>
                                        <td class="text-center p-1"><b>COPIA</b></td>
                                    </tr>
                                    <tbody>
                                </thead>
                                    {{--@foreach ($cotizaciones as $datos)
                                        <tr class="font-verdana">
                                            <td class="text-center p-1">{{$num++}}</td>
                                            <td class="text-center p-1">{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</td>
                                            <td class="text-center p-1">{{number_format($datos->dolar_oficial,2,'.',',')}}</td>
                                            <td class="text-center p-1">{{number_format($datos->dolar_compra,2,'.',',')}}</td>
                                            <td class="text-center p-1">{{number_format($datos->dolar_venta,2,'.',',')}}</td>
                                            <td class="text-center p-1">{{number_format($datos->ufv,2,'.',',')}}</td>
                                            <td class="text-center p-1">
                                                @if ($datos->status == 1)
                                                    <span class="badge bg-success font-verdana">ACTIVO</span>
                                                @else
                                                    <span class="badge bg-danger font-verdana">ELIMINADO</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="card-footer clearfix font-verdana-bg">
                {{ $cotizaciones->appends(Request::all())->links() }}
                <p class="text-muted">Mostrando <strong>{{ $cotizaciones->count() }}</strong> registros de <strong>{{$cotizaciones->total() }}</strong> totales</p>
            </div>--}}
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