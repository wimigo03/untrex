@extends('adminlte::page')
@section('title', 'Plan de cuenta')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="card-title"><b>LIBRO DIARIO</b></div>
            </div>
            <div class="card-body">
                <form action="#" method="get" id="form">
                    @include('libro-diario-f.partials.form')
                </form>
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary font-verdana" type="button" onclick="procesar();">
                            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Procesar
                        </button>
                        <button class="btn btn-success font-verdana" type="button" onclick="excel();">
                            <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar a Excel
                        </button>
                        <i class="fa fa-spinner custom-spinner fa-spin fa-2x fa-fw spinner-btn" style="display: none;"></i>
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
            $('#proyecto').select2({
                placeholder: "--Proyecto--"
            });

            $('#tipo_comp').select2({
                placeholder: "--Tipo Comprobante--"
            });

            $('#estado').select2({
                placeholder: "--Estado--"
            });
        } );

        $("#fecha_i").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        $("#fecha_f").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

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
        
        function countCharsInicial(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha_i").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_i").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_i").value = "";
                }
            }
        }

        function countCharsFinal(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha_f").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_f").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_f").value = "";
                }
            }
        }

        function procesar(){
            var url = "{{ route('librodiariof.search') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".btn-importar").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function excel(){
            var url = "{{ route('librodiariof.excel') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }
    </script>
@stop