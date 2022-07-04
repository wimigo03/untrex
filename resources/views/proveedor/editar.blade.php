@extends('adminlte::page')
@section('title', 'Proveedor')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card-title"><b>MODIFICAR REGISTRO DE PROVEEDOR</b></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{route('proveedor.index')}}" class="text-white">
                            &nbsp;<i class="fas fa-reply" aria-hidden="true"></i>&nbsp;
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card card-custom">
                    <div class="card-body">
                        {!! Form::model(Request::all(),['route'=> ['proveedor.update'],'onsubmit' => "return validacion_form()"]) !!}
                            @include('proveedor.partials.form-editar')
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/style.css">
    {{--<link rel="stylesheet" href="/datepicker/datepicker.min.css"/>--}}
    <link rel="stylesheet" href="/css/select2.min.css" type="text/css">
@stop
@section('js')
    {{--<script src="/datepicker/datepicker.min.js" type="text/javascript"></script>
    <script src="/datepicker/datepicker.es.js" type="text/javascript"></script>--}}
    <script type="text/javascript" src="/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            var ciudad_old = document.getElementById("ciudad_old").value;
            if(ciudad_old != 10 || ciudad_old == ''){
                $('.otra_ciudad').hide();
            }else{
                $('.otra_ciudad').show();
            }

            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });   
        } );

        function validacion_form() {
            var opcion = confirm("Estas por modificar un registro a un proveedor. ¿Estas seguro que desea continuar?");
            if (opcion == true) {
                return true;
                } else {
                    return false;
                    }
            return true;
        }

        function valideKey(evt){
            var code = (evt.which) ? evt.which : evt.keyCode;
            if(code==8){
                return true;
            }else if(code==46 || (code>=48 && code<=57)){
                return true;
            }else{
                return false;
            }
        }

        function copiarRazonSocial(dato){
            var razon_social = document.getElementById("razon_social").value;
            document.getElementById("nombre_comercial").value = razon_social;
        }

        $('#ciudades').change(function() {
            var ciudad_id =  $(this).val();
            if(ciudad_id == 10){
                $('.otra_ciudad').show();
            }else{
                $('.otra_ciudad').hide();
                document.getElementById("nueva_ciudad").value = "";
                document.getElementById("abreviatura").value = "";
            }
        });
    </script>
@stop