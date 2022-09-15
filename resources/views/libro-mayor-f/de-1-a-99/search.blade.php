@extends('adminlte::page')
@section('title', 'Plan de cuenta')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="card-title"><b>LIBRO MAYOR DEL 1 AL 99 - {{strtoupper($proyecto->nombre)}}</b></div>
            </div>
            <div class="card-body">
                @include('libro-mayor-f.de-1-a-99.partials.encabezado')
                @if ($comprobantes != null)
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table-responsive table-striped table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana-sm">
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        {{--<td class="text-center p-1"><b>FACTURA</b></td>--}}
                                        <td colspan="2" class="text-center p-1"><b>COMPROBANTE</b></td>
                                        <td class="text-center p-1"><b>CODIGO</b></td>
                                        <td class="text-center p-1"><b>CUENTA</b></td>
                                        <td class="text-center p-1"><b>AUXILIAR</b></td>
                                        <td class="text-center p-1"><b>CENTRO</b></td>
                                        <td class="text-center p-1"><b>CHEQUE</b></td>
                                        <td class="text-center p-1"><b>GLOSA</b></td>
                                        <td class="text-center p-1"><b>DEBE</b></td>
                                        <td class="text-center p-1"><b>HABER</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comprobantes as $datos)
                                        <tr class="font-verdana-sm">
                                            <td class="text-center p-1">{{\Carbon\Carbon::parse($datos->fecha)->format('d/m/Y')}}</td>
                                            {{--@php
                                                $facturas = DB::table('comprobante_facturas as a')
                                                                ->join('facturas as b','b.id','a.factura_id')
                                                                ->where('a.comprobante_id',$datos->comprobante_id)
                                                                ->where('a.estado',1)
                                                                ->where('a.deleted_at',null)
                                                                ->get();
                                            @endphp
                                            <td class="text-justify p-1">
                                                @foreach ( $facturas as $factura)
                                                    {{'- ' . $factura->numero}}<br>
                                                @endforeach
                                            </td>--}}
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
                                                <span class="tts:down tts-slideIn tts-custom/ font-verdana" aria-label="Ir a comprobante">
                                                    <a href="{{ route('comprobantes.fiscales.show',$datos->comprobante_id) }}" target="_blank" class="font-verdana-sm">
                                                        {{$datos->nro_comprobante}}
                                                    </a>
                                                </span>
                                            </td>
                                            <td class="text-center p-1 {{$color}}"><b>{{$estado}}</b></td>
                                            <td class="text-center p-1">{{$datos->codigo}}</td>
                                            <td class="text-justify p-1">{{$datos->cuenta}}</td>
                                            <td class="text-justify p-1">{{$datos->auxiliar}}</td>
                                            <td class="text-center p-1">{{$datos->centro}}</td>
                                            <td class="text-center p-1">{{$datos->tipo_transaccion . $datos->cheque_nro}}</td>
                                            <td class="text-justify p-1">{{strtoupper($datos->glosa)}}</td>
                                            <td class="text-right p-1">{{number_format($datos->debe,2,'.',',')}}</td>
                                            <td class="text-right p-1">{{number_format($datos->haber,2,'.',',')}}</td>
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
        function hide(){
            $(".btn").hide();
            $(".spinner-btn-hide").show();
        }
    </script>
@stop