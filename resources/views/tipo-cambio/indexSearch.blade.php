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
                <div class="card-title"><b>COTIZACIONES</b></div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['tipo_cambio.search'],'onsubmit' => "return validacion_form()"]) !!}
                    @include('tipo-cambio.partials.search')
                {!! Form::close()!!}
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <div class="table-responsive table-striped">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana-bg">
                                        <td class="text-center p-1"><b>N°</b></td>
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        <td class="text-center p-1"><b>OFICIAL</b></td>
                                        <td class="text-center p-1"><b>COMPRA</b></td>
                                        <td class="text-center p-1"><b>VENTA</b></td>
                                        <td class="text-center p-1"><b>UFV</b></td>
                                        <td class="text-center p-1"><b>ESTADO</b></td>
                                        <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                    </tr>
                                    <tbody>
                                </thead>
                                    @php
                                        $num = 1;
                                    @endphp
                                    @foreach ($tipos_cambios as $datos)
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
                                            <td class="text-center p-1">
                                                <center>
                                                    <table style="border-collapse:collapse; border: none;">
                                                        <tr>
                                                            <td style="padding: 0;">
                                                                <a href="{{route('tipo_cambio.editar', $datos->id)}}" class="btn btn-xs btn-warning">
                                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </center>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/datepicker/datepicker.min.css"/>
@stop

@section('js')
    <script src="/datepicker/datepicker.min.js" type="text/javascript"></script>
    <script src="/datepicker/datepicker.es.js" type="text/javascript"></script>
    <script>
        $("#fecha_desde").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        $("#fecha_hasta").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        function countCharsDesde(obj){
        var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha_desde").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_desde").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_desde").value = "";
                }
            }
        }

        function countCharsHasta(obj){
        var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha_hasta").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_hasta").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_hasta").value = "";
                }
            }
        }
    </script>
@stop