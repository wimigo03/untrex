@extends('adminlte::page')
@section('title', 'Plan de cuentas auxiliares')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>PLAN DE CUENTAS AUXILIARES</b></div>
            </div>
            <div class="card-body">
                {{--{!! Form::model(Request::all(),['route'=> ['cotizaciones.search'],'onsubmit' => "return validacion_form()"]) !!}
                    @include('cotizaciones.partials.search')
                {!! Form::close()!!}--}}
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <a href="{{route('plandecuentasauxiliares.create')}}" class="btn btn-success font-verdana-bg">
                            <i class="fas fa-plus"></i>&nbsp;Crear Auxiliar
                        </a>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <div class="table-responsive table-striped">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>ID</b></td>
                                        <td class="text-center p-1"><b>TIPO</b></td>
                                        <td class="text-center p-1"><b>CREACION</b></td>
                                        <td class="text-center p-1"><b>NOMBRE</b></td>
                                        <td class="text-center p-1"><b>ESTADO</b></td>
                                        <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                    </tr>
                                    <tbody>
                                </thead>
                                    @foreach ($plancuentasauxiliares as $datos)
                                        <tr class="font-verdana">
                                            <td class="text-center p-1">{{$datos->id}}</td>
                                            <td class="text-center p-1">
                                                @if ($datos->tipo == 1)
                                                    <span class="badge bg-secondary font-verdana-sm">PROVEEDOR</span>
                                                @else
                                                    @if ($datos->tipo == 2)
                                                        <span class="badge bg-secondary font-verdana-sm">TRABAJADOR</span>
                                                    @else
                                                        @if ($datos->tipo == 3)
                                                            <span class="badge bg-secondary font-verdana-sm">CLIENTE</span>
                                                        @else
                                                            <span class="badge bg-secondary font-verdana-sm">OTRO</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center p-1">{{\Carbon\Carbon::parse($datos->created_at)->format('d/m/Y')}}</td>
                                            <td class="text-left p-1">{{$datos->nombre}}</td>
                                            <td class="text-center p-1">
                                                @if ($datos->estado == 1)
                                                    <span class="badge badge-success font-verdana-sm">ACTIVO</span>
                                                @else
                                                    <span class="badge bg-danger font-verdana">NO ACTIVO</span>
                                                @endif
                                            </td>
                                            <td class="text-center p-1">
                                                <a href="{{--route('comprobantes.show', $comprobante_id )--}}" class="btn btn-xs btn-warning">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
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