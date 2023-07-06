@extends('adminlte::page')
@section('title', 'Comprobantes')
@section('content')
@include('components.flash_alerts')
<br>
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card-title"><b>DETALLE DE COMPROBANTE</b></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{route('comprobantes.index')}}" class="font-verdana-bg text-white">
                            <i class="fas fa-reply fa-lg" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['comprobantesdetalles.insertar']]) !!}
                    @include('comprobantes-detalles.partials.form1')
                {!! Form::close()!!}
                {!! Form::model(Request::all(),['route'=> ['comprobantesdetalles.finalizar']]) !!}
                    @include('comprobantes-detalles.partials.form2')
                {!! Form::close()!!}
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
        $(document).ready(function() {
            $('.cheque').hide();
            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
        } );

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

        function countChars(obj,dato){
        var cont = obj.value.length;
        console.log(cont,dato);
        if(dato === 1){
            if(cont > 0){
                $('#haber_bs').attr('disabled',true);
            }else{
                $('#haber_bs').attr('disabled',false);
            }
        }else{
            if(cont > 0){
                $('#debe_bs').attr('disabled',true);
            }else{
                $('#debe_bs').attr('disabled',false);
            }
        }
            /*if(cont > 1){
                $('#debe_bs').attr('disabled',true);
            }else{
                $('#haber_bs').attr('disabled',true);
            }
            */
        }

        $('#tipo').change(function() {
            if($(this).val()==3){
                $('label[for="entregado_recibido"]').hide();
                $('input[name="entregado_recibido"]').val('');
                $('input[name="entregado_recibido"]').hide();
            } else{
                $('label[for="entregado_recibido"]').show();
                $('input[name="entregado_recibido"]').show();
            }
            if($(this).val()==1) $('label[for="entregado_recibido"]').empty().html('Hemos recibido de:');
            if($(this).val()==2) $('label[for="entregado_recibido"]').empty().html('Hemos entregado a:');
            if($(this).val()==3) $('label[for="entregado_recibido"]').empty().html('');
        });

        $('#plan_cuentas').change(function() {
                var idplancuenta = $(this).val();
                if(idplancuenta!=null)
                {
                    if(idplancuenta.length!=0){
                        $.ajax({
                            type: 'GET',
                            url: '/comprobantesdetalles/get_plancuenta/'+idplancuenta,
                            dataType: 'json',
                            data: {
                                id: idplancuenta
                            },
                            success: function(json){
                                if(json.cheque=='1'){                                    
                                    $('.cheque').show();
                                    //$('.cheque_id').val(1);
                                } else {
                                    //$('.cheque_id').val(0);
                                    $('.cheque').hide();
                                }
                            },
                            error: function(xhr){
                                //console.log(xhr.responseText);
                            }
                        });
                    }
                }
            });
    </script>
@stop