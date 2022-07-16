@extends('adminlte::page')
@section('title', 'Consorcios')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card-title"><b>CONSORCIOS</b></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{route('consorcios.create')}}" class="text-white">
                            &nbsp;<i class="fas fa-plus-circle" aria-hidden="true"></i>&nbsp;
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card card-custom">
                    <div class="card-body">
                        {{--{!! Form::model(Request::all(),['route'=> ['proveedor.search']]) !!}
                            @include('proveedor.partials.search')
                        {!! Form::close()!!}--}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="card card-custom">
                            <div class="card-body">
                                <center>
                                    <div class="table-responsive table-striped">
                                        <table id="dataTable" class="display responsive" style="width:80%">
                                            <thead>
                                                <tr class="font-verdana">
                                                    <td class="text-center p-1"><b>NOMBRE</b></td>
                                                    <td class="text-center p-1"><b>TIPO</b></td>
                                                    <td class="text-center p-1"><b>ESTADO</b></td>
                                                    <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($consorcios as $datos)
                                                    <tr class="font-verdana">
                                                        <td class="text-center p-1">{{$datos->nombre}}</td>
                                                        <td class="text-center p-1">{{$datos->tipo == 1 ? 'UNI-EMPRESA' : 'MULTI-EMPRESA'}}</td>
                                                        <td class="text-center p-1">{{$datos->estado == 1 ? 'ACTIVO' : 'NO ACTIVO'}}</td>
                                                        <td class="text-center p-1">
                                                            <table style="border-collapse:collapse; border: none;" align="center">
                                                                <tr>
                                                                    <td style="padding: 0;">
                                                                        <a href="{{route('consorcios.proyectos', $datos->id )}}" class="btn btn-xs btn-primary font-verdana">
                                                                            <i class="fas fa-project-diagram"></i>&nbsp;Proyectos
                                                                        </a>
                                                                        <a href="{{route('consorcios.socios', $datos->id )}}" class="btn btn-xs btn-info font-verdana">
                                                                            <i class="fas fa-user-friends"></i>&nbsp;Socios
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </center>
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
@stop
@section('js')
    <script src="/datepicker/datepicker.min.js" type="text/javascript"></script>
    <script src="/datepicker/datepicker.es.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            /*$('.select2').select2({
                placeholder: "--Seleccionar--"
            });*/
        });

        /*$("#fecha").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });*/

        /*function countChars(obj){
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
        }*/
    </script>
@stop