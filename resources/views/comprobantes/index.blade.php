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
                {!! Form::model(Request::all(),['route'=> ['comprobantes.search']]) !!}
                    @include('comprobantes.partials.search')
                {!! Form::close()!!}
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
                                        <td class="text-center p-1"><b>COPIA</b></td>
                                        <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comprobantes as $datos)
                                        <tr class="font-verdana">
                                            <td class="text-center p-1">{{$datos->comprobante_id}}</td>
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
                                                @if ($datos->copia == 1)
                                                    <i class="fas fa-check" aria-hidden="true"></i>
                                                @else
                                                    <i class="fas fa-times" aria-hidden="true"></i>   
                                                @endif
                                            </td>
                                            <td class="text-center p-1">
                                                <a href="{{route('comprobantes.show', $datos->comprobante_id )}}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                                                @if($datos->status_validate == 1)
                                                    <a href="#" class="btn btn-xs btn-secondary">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a href="{{route('comprobantesdetalles.create', $datos->comprobante_id)}}" class="btn btn-xs btn-warning">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                                <a href="{{route('comprobantes.pdf', $datos->comprobante_id )}}" class="btn btn-xs btn-danger" target="_blank">
                                                    <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                                </a>
                                                {{--<button class="btn btn-xs btn-danger" type="submit">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer clearfix font-verdana-bg">
                                {{ $comprobantes->appends(Request::all())->links() }}
                                <p class="text-muted">Mostrando <strong>{{ $comprobantes->count() }}</strong> registros de <strong>{{$comprobantes->total() }}</strong> totales</p>
                            </div>
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
    <link rel="stylesheet" href="/css/select2.min.css" type="text/css">
@stop

@section('js')
    <script src="/datepicker/datepicker.min.js" type="text/javascript"></script>
    <script src="/datepicker/datepicker.es.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
        } );

        $("#fecha").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        function countChars(obj){
        var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha").value = "";
                }
            }
        }
    </script>
@stop