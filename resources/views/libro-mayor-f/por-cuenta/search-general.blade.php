@extends('adminlte::page')
@section('title', 'Plan de cuenta')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="card-title"><b>LIBRO MAYOR POR CUENTA - {{strtoupper($tipo)}} - {{strtoupper($proyecto->nombre)}}</b></div>
            </div>
            <div class="card-body">
                @include('libro-mayor-f.por-cuenta.partials.encabezado-general')
                @if ($comprobantes != null)
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        <td width="15%" colspan="2" class="text-center p-1"><b>COMPROBANTE</b></td>
                                        <td class="text-center p-1"><b>CENTRO</b></td>
                                        <td class="text-center p-1"><b>AUXILIAR</b></td>
                                        <td class="text-center p-1"><b>CHEQUE</b></td>
                                        <td class="text-center p-1"><b>GLOSA</b></td>
                                        <td class="text-center p-1"><b>DEBE</b></td>
                                        <td class="text-center p-1"><b>HABER</b></td>
                                        <td class="text-center p-1"><b>SALDO</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comprobantes as $datos)
                                        <tr class="font-verdana">
                                            <td class="text-center p-1">{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</td>
                                            @php
                                                if($datos->status == 0){
                                                    $estado = "B";
                                                    $color = "text-secondary";
                                                }else{
                                                    $estado = "A";
                                                    $color = "text-success";
                                                }
                                            @endphp
                                            <td class="text-center p-1">
                                                <a href="{{ route('comprobantes.fiscales.show',$datos->comprobante_id) }}" target="_blank">
                                                    {{$datos->nro_comprobante}}
                                                </a>
                                            </td>
                                            <td class="text-center p-1 {{$color}}"><b>{{$estado}}</b></td>
                                            <td class="text-center p-1">{{$datos->centro}}</td>
                                            <td class="text-center p-1">{{$datos->auxiliar}}</td>
                                            <td class="text-center p-1">{{strtoupper($datos->cheque_nro)}}</td>
                                            <td class="text-justify p-1">{{strtoupper($datos->glosa)}}</td>
                                            <td class="text-right p-1">{{number_format($datos->debe,2,'.',',')}}</td>
                                            <td class="text-right p-1">{{number_format($datos->haber,2,'.',',')}}</td>
                                            @php
                                                if($datos->debe > 0){
                                                    $saldo += $datos->debe;
                                                }else{
                                                    $saldo -= $datos->haber;
                                                }
                                            @endphp
                                            <td class="text-right p-1">{{number_format($saldo,2,'.',',')}}</td>
                                        </tr>
                                    @endforeach
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
     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })   
    </script>
@stop