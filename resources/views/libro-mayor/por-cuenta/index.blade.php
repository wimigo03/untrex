@extends('adminlte::page')
@section('title', 'Plan de cuenta')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>LIBRO MAYOR POR CUENTA</b></div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['libromayor.porcuenta.search']]) !!}
                    @include('libro-mayor.por-cuenta.partials.form')
                {!! Form::close()!!}
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
            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
        } );

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
                url: '/libromayor/porcuenta/seleccionar',
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
                            if (json.cuenta!=null || json.cuenta!="") {
                                select.append($("<option></option>").attr("value", json.id).text(json.cuenta)); 
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
    </script>
@stop