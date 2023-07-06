@extends('adminlte::page')
@section('title', 'Untrex')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')
<br>
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>FORMULARIO DE FACTURAS</b></div>
            </div>
            <div class="card-body">
                {!! Form::open(['route'=>'facturas.store','id'=>'form-factura']) !!}
                    @include('facturas.partials.form1')
                {!! Form::close()!!}
                    @include('facturas.partials.form2')
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
    {{--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        $(document).ready(function() {
            //$('.cheque').hide();
            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });

            $('#insertar').show();
            $('#insertar').click(function(e){
                $(this).hide();
                $('#loading').show();
                $('#form-factura').submit();
            });
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

        function validacion_form() {
            var opcion = confirm("Estas por crear un comprobante. ¿Estas seguro que desea continuar?");
            if (opcion == true) {
                return true;
                } else {
                    return false;
                    }
            return true;
        }

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
                    document.getElementById("message").innerHTML = "(No valido)";
                    document.getElementById("fecha").value = "";
                }
            }
        }

        function countCharsMonto(obj){
            var cont = obj.value.length;
            if(cont <= 0){
                document.getElementById("monto").value = 0;
            }
        }
        function countCharsExcento(obj){
            var cont = obj.value.length;
            if(cont <= 0){
                document.getElementById("excento").value = 0;
            }
        }
        function countCharsDescuento(obj){
            var cont = obj.value.length;
            if(cont <= 0){
                document.getElementById("descuento").value = 0;
            }
        }

        function limitDecimalPlaces(e, count){
            if (e.target.value.indexOf('.') == -1) { return; }
            if ((e.target.value.length - e.target.value.indexOf('.')) > count) {
                e.target.value = parseFloat(e.target.value).toFixed(count);
                }
            }

        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
            }

        $('#tipo').change(function() {
                var tipo =  $(this).val();                
                if(tipo == 'VENTA')
                    $('#estado').show();
                else
                    $('#estado').hide();
            });

        $('#proveedores').change(function() {
            var proveedor_id = $(this).val();
            if(proveedor_id != null)
            {
                if(proveedor_id.length != 0)
                {
                    if(proveedor_id!=202)
                    {
                        $.ajax({
                            type: 'GET',
                            url: '/facturas/get_proveedor/'+proveedor_id,
                            dataType: 'json',
                            data: {
                                id: proveedor_id
                            },
                            success: function(json){
                                console.log(json);
                                $('#nit').empty().val(json.nit);
                                $('#razon_social').empty().val(json.razon_social);
                            },
                            error: function(xhr){
                                console.log(xhr.responseText);
                            }
                        });
                    }
                }
            }
        });
    </script>
@stop