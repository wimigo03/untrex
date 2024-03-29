@extends('adminlte::page')
@section('title', 'Plan de cuenta')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>LIBRO BANCO</b></div>
            </div>
            <div class="card-body">
                <form action="#" method="get" id="form">
                    @include('libro-banco.partials.form')
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
            $('.cheque').hide();
            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
        } );

        $('#tipo').change(function() {
            var tipo = $(this).val();
            if(tipo!=null)
            {
                if(tipo.length!=0){
                    if(tipo=='Todo'){
                        $('.cheque').hide();
                    }else{
                        $('.cheque').show();
                    }
                }
            }
        });

        $("#fecha_inicial").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        $("#fecha_final").datepicker({
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
                var fecha = document.getElementById("fecha_inicial").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_inicial").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_inicial").value = "";
                }
            }
        }

        function countCharsFinal(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha_final").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_final").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_final").value = "";
                }
            }
        }

        $('#proyecto_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'GET',
                url: '/librobanco/seleccionar',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){ 
                    if(data.plancuenta){
                        var arr = Object.values($.parseJSON(data.plancuenta));
                        $("#plancuenta_id").empty();
                        $('#plancuenta_id').prop('disabled', false);                   
                        var select = $('#plancuenta_id');
                        select.append($("<option></option>").attr("value", '').text('--Plan cuenta--'));
                        select.append($("<option></option>").attr("value", '').text('Ninguno'));
                        $.each(arr,function(index,json){
                            if (json.nombre!=null || json.nombre!="") {
                                select.append($("<option></option>").attr("value", json.id).text(json.nombre)); 
                            } else {
                                
                            }
                        });                     
                    }              
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        });

        function procesar(){
            var url = "{{ route('librobanco.search') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".btn-importar").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function excel(){
            var url = "{{ route('librobanco.excel') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }
    </script>
@stop