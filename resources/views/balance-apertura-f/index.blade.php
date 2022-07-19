@extends('adminlte::page')
@section('title', 'Balance de aperturas')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-title"><b>BALANCE DE APERTURA</b></div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('proyecto_id',$proyectos,$proyecto_id, ['class' => 'form-control form-control-sm bg-warning text-center', 'id' => 'proyecto_id']) !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{route('balanceaperturaf.create',$proyecto_id)}}" class="btn btn-success font-verdana-bg">
                            <i class="fas fa-plus" aria-hidden="true"></i>&nbsp;Nuevo Balance
                        </a>
                    </div>
                </div>
                @if (count($balance_apertura) > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <center>
                                <div class="table-responsive table-striped">
                                    <table id="tablaAjax" class="display responsive" style="width:80%">
                                        <thead>
                                            <tr class="font-verdana">
                                                <td class="text-center p-1"><b>COMPROBANTE</b></td>
                                                <td class="text-center p-1"><b>CREACION</b></td>
                                                <td class="text-center p-1"><b>GESTION</b></td>
                                                <td class="text-center p-1"><b>MONEDA</b></td>
                                                <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($balance_apertura as $datos)
                                                <tr class="font-verdana">
                                                    <td class="text-center p-1">{{$datos->nro_comprobante}}</td>
                                                    <td class="text-center p-1">{{$datos->fecha_creacion}}</td>
                                                    <td class="text-center p-1">{{$datos->gestion}}</td>
                                                    <td class="text-center p-1">{{$datos->moneda}}</td>
                                                    <td class="text-center p-1">
                                                        <table style="border-collapse:collapse; border: none;" align="center">
                                                            <tr>
                                                                <td style="padding: 0;">
                                                                    <a href="{{route('balanceaperturaf.editar', $datos->balance_apertura_id)}}" class="btn btn-xs btn-warning font-verdana-bg">
                                                                        &nbsp;<i class="fas fa-info-circle"></i>&nbsp;
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
                @endif
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
            $("#proyecto_id").change(function () {
				if($("#proyecto_id option:selected").val() != null){
					var url = '{{ route("balanceapertura.index", ":id") }}';
					var id = $("#proyecto_id option:selected").val();
					url = url.replace(':id', id);
					window.location.href=url;
				}
			});

            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
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