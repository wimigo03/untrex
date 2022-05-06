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
                <div class="card-title"><b>DETALLE DE COMPROBANTE</b></div>
            </div>
            <div class="card-body">
                {{--{!! Form::model(Request::all(),['route'=> ['comprobantes.store'],'onsubmit' => "return validacion_form()"]) !!}--}}
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::label('Nro','Nro',['class' => 'd-inline'])}}
                            {{Form::text('nro_comprobante',$comprobante->nro_comprobante,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nro_comprobante'])}}
                            {!! $errors->first('nro_comprobante','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('Tc','TC',['class' => 'd-inline'])}}
                            {{Form::text('taza_cambio',$comprobante->tipo_cambio,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'taza_cambio'])}}
                            {!! $errors->first('taza_cambio','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('Ufv','UFV',['class' => 'd-inline'])}}
                            {{Form::text('ufv',$comprobante->ufv,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'ufv'])}}
                            {!! $errors->first('ufv','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-3">
                            {{Form::label('nombre','Nombre',['class' => 'd-inline'])}}
                            {{--{{Form::hidden('user_id',$user_id)}}--}}
                            {{Form::text('nombre',strtoupper($user->name),['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nombre'])}}
                            {!! $errors->first('nombre','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-1">
                            {{ Form::label('moneda','Moneda',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::text('moneda', $comprobante->moneda, ['readonly'=>true,'class' => 'form-control form-control-sm ' . ( $errors->has('moneda') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('moneda','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{ Form::label('centro','Centro',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::text('centro', $comprobante->centro_id, ['readonly'=>true,'class' => 'form-control form-control-sm ' . ( $errors->has('centro') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('centro','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::label('Fecha','Fecha',['class' => 'd-inline'])}}
                            {{Form::text('fecha',$comprobante->fecha,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countChars(this);'])}}
                            {!! $errors->first('fecha','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('tipo','Tipo',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('tipo', array('1'=>'INGRESO','2'=>'EGRESO','3'=>'TRASPASO'), $comprobante->tipo, ['readonly'=>true,'class' => 'form-control form-control-sm ' . ( $errors->has('tipo') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('tipo','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-8">
                            {{Form::label('concepto','Concepto',['class' => 'd-inline'])}}
                            {{Form::text('concepto',$comprobante->concepto,['readonly'=>true,'class'=>'form-control form-control-sm'. ( $errors->has('concepto') ? ' is-invalid' : '' )])}}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-12 text-right">
                            <br>
                            <a href="{{route('comprobantes.index')}}" class="btn btn-danger font-verdana-bg">
                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
                            </a>
                            <button type="submit" class="btn btn-primary font-verdana-bg">
                                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Registrar&nbsp;
                            </button>
                        </div>
                    </div>
                {{--{!! Form::close()!!}--}}
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
                    document.getElementById("message").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha").value = "";
                }
            }
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
    </script>
@stop