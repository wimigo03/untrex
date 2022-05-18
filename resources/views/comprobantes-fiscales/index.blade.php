@extends('adminlte::page')
@section('title', 'Comprobantes')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="card-title"><b>COMPROBANTES</b></div>
            </div>
            <div class="card-body">
                {{--{!! Form::model(Request::all(),['route'=> ['cotizaciones.search'],'onsubmit' => "return validacion_form()"]) !!}
                    @include('cotizaciones.partials.search')
                {!! Form::close()!!}--}}
                {{--<div class="form-group row">
                    <div class="col-md-12 text-left">
                        <a href="{{route('comprobantes.create')}}" class="btn btn-success font-verdana-bg">
                            <i class="fas fa-plus"></i>&nbsp;Crear Comprobante
                        </a>
                    </div>
                </div>--}}
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <div class="table-responsive table-striped">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>ID</b></td>
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        <td class="text-center p-1"><b>COMPROBANTE</b></td>
                                        <td class="text-center p-1"><b>CONCEPTO</b></td>
                                        <td class="text-center p-1"><b>EMPRESA</b></td>
                                        <td class="text-center p-1"><b>MONTO</b></td>
                                        <td class="text-center p-1"><b>ESTADO</b></td>
                                        <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                    </tr>
                                    <tbody>
                                </thead>
                                    @foreach ($comprobantes_fiscales as $datos)
                                        <tr class="font-verdana">
                                            <td class="text-center p-1">{{$datos->comprobante_fiscal_id}}</td>
                                            <td class="text-center p-1">{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</td>
                                            <td class="text-center p-1">{{$datos->nro_comprobante}}</td>
                                            <td class="text-center p-1">{{$datos->concepto}}</td>
                                            <td class="text-center p-1">{{$datos->empresa}}</td>
                                            <td class="text-center p-1">{{number_format($datos->monto,2,'.',',')}}</td>
                                            <td class="text-center p-1">
                                                @if ($datos->status == 0)
                                                    <span class="btn btn-xs btn-secondary font-verdana-sm"><b>BORRADOR</b></span>
                                                @else
                                                    @if ($datos->status == 1)
                                                        <span class="btn btn-xs btn-success font-verdana-sm"><b>APROBADO</b></span>
                                                    @else
                                                        <span class="btn btn-xs btn-danger font-verdana-sm"><b>ANULADO</b></span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center p-1">
                                                <a href="{{route('comprobantes.fiscales.show', $datos->comprobante_fiscal_id)}}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye fa-xs" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{--url('comprobantes.pdf', $comprobante_id )--}}" class="btn btn-xs btn-warning" target="_blank">
                                                    <i class="fas fa-print" aria-hidden="true"></i>
                                                </a>
                                                {{--<button class="btn btn-xs btn-danger" type="submit">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>--}}
                                            </td>
                                        </tr>
                                    @endforeach
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