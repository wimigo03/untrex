@extends('adminlte::page')
@section('title', 'Untrex')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')
<br>
{{--<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('/admin') }}"><i class="fas fa-home fa-xs"></i> <font size="2px">Inicio</font></a> /
        <a href="#"><i class="fas fa-plus-square fa-xs"></i><font size="2px"> Crear</font></a>
    </div>
</div>--}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>CREAR COTIZACION</b></div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['cotizaciones.store'],'onsubmit' => "return validacion_form()"]) !!}
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-6">
                                    {{Form::label('Fecha','Fecha',['class' => 'd-inline'])}}
                                </div>
                                <div class="col-md-6 text-right">
                                    <em><span id="message" class="text-danger font-verdana-sm"></span></em>
                                </div>
                            </div>
                            {{Form::text('fecha',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countChars(this);'])}}
                            {!! $errors->first('fecha','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-3">
                            {{Form::label('Dolar_oficial','Dolar oficial',['class' => 'd-inline'])}}
                            {{Form::text('dolar_oficial',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'dolar_oficial', 'onkeypress' => 'return valideKey(event);'])}}
                            {!! $errors->first('dolar_oficial','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-3">
                            {{Form::label('Dolar_compra','Dolar Compra',['class' => 'd-inline'])}}
                            {{Form::text('dolar_compra',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'dolar_compra', 'onkeypress' => 'return valideKey(event);'])}}
                            {!! $errors->first('dolar_compra','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-3">
                            {{Form::label('Dolar_venta','Dolar Venta',['class' => 'd-inline'])}}
                            {{Form::text('dolar_venta',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'dolar_venta', 'onkeypress' => 'return valideKey(event);'])}}
                            {!! $errors->first('dolar_venta','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-3">
                            {{Form::label('Ufv','Ufv',['class' => 'd-inline'])}}
                            {{Form::text('ufv',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'ufv', 'onkeypress' => 'return valideKey(event);'])}}
                            {!! $errors->first('ufv','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-9 text-right">
                            <br>
                            <a href="{{route('cotizaciones.index')}}" class="btn btn-danger font-verdana-bg">
                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
                            </a>
                            <button type="submit" class="btn btn-primary font-verdana-bg">
                                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Salvar&nbsp;
                            </button>
                        </div>
                    </div>
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
        /*$(document).ready(function() {
            $(':text,:hidden').val(''); //Limpia los input al refrescar la pagina
        } );*/

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
            var opcion = confirm("¿Esta seguro que desea continuar...");
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
                    document.getElementById("message").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha").value = "";
                }
            }
        }
    </script>
@stop