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
                <div class="card-title"><b>EDITAR COMPROBANTE</b></div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['comprobantes.update'],'onsubmit' => "return validacion_form()"]) !!}
                    {{Form::hidden('comprobante_id',$comprobante->id)}}
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::label('Nro','Nro',['class' => 'd-inline'])}}
                            {{Form::text('nro_comprobante',$comprobante->nro_comprobante,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nro_comprobante'])}}
                            {!! $errors->first('nro_comprobante','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('Tc','TC',['class' => 'd-inline'])}}
                            {{Form::text('taza_cambio',$tipo_cambio->dolar_oficial,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'taza_cambio'])}}
                            {!! $errors->first('taza_cambio','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('Ufv','UFV',['class' => 'd-inline'])}}
                            {{Form::text('ufv',$tipo_cambio->ufv,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'ufv'])}}
                            {!! $errors->first('ufv','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-6">
                            {{Form::label('nombre','Nombre',['class' => 'd-inline'])}}
                            {{Form::hidden('user_id',$user_id)}}
                            {{Form::text('nombre',strtoupper($nombre),['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nombre'])}}
                            {!! $errors->first('nombre','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            {{ Form::label('moneda','Moneda',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('moneda', array('BS'=>'Bs.','SUS'=>'$us'), $comprobante->moneda, ['disabled' => true,'placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm ' . ( $errors->has('moneda') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('moneda','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-3">
                            {{ Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('proyecto', $proyectos, $comprobante->proyecto_id, ['disabled' => true,'placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm' . ( $errors->has('proyecto') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('proyecto','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-6">
                                    {{Form::label('Fecha','Fecha',['class' => 'd-inline font-verdana-bg'])}}
                                </div>
                                <div class="col-md-6 text-right">
                                    <em><span id="message" class="text-danger font-verdana-sm"></span></em>
                                </div>
                            </div>
                            {{Form::hidden('fecha_1',$comprobante->fecha)}}
                            {{Form::text('fecha_2',\Carbon\Carbon::parse($comprobante->fecha)->format('d/m/Y'),['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countChars(this);'])}}
                            {!! $errors->first('fecha','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('tipo','Tipo',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::hidden('tipo_1',$comprobante->tipo)}}
                            {!! Form::select('tipo_2', array('1'=>'INGRESO','2'=>'EGRESO','3'=>'TRASPASO'), $comprobante->tipo, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm ' . ( $errors->has('tipo') ? ' is-invalid' : '' ),'id' => 'tipo']) !!}
                            {!! $errors->first('tipo','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('copia','¿Con copia?',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('copia', array('1'=>'Si','0'=>'No'), $comprobante->copia, ['disabled' => true,'placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm ' . ( $errors->has('tipo') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('copia','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-6">
                            {{Form::label('entregado_recibido','Hemos recibido de:',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('entregado_recibido',$comprobante->entregado_recibido,['class'=>'text-uppercase form-control form-control-sm'. ( $errors->has('entregado_recibido') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
                            {!! $errors->first('entregado_recibido','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-6">
                            {{Form::label('concepto','Concepto:',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('concepto',$comprobante->concepto,['class'=>'form-control form-control-sm'. ( $errors->has('concepto') ? ' is-invalid' : '' )])}}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-12 text-right">
                            <br>
                            <a href="{{route('comprobantesdetalles.create',$comprobante->id)}}" class="btn btn-danger font-verdana-bg">
                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
                            </a>
                            <button type="submit" class="btn btn-primary font-verdana-bg">
                                <i class="fas fa-sync-alt"></i>&nbsp;Actualizar&nbsp;
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
        $(document).ready(function() {
            tipo = document.getElementById("tipo").value;
            if(tipo!=null)
            {
                if(tipo==3){
                    $('label[for="entregado_recibido"]').hide();
                    $('input[name="entregado_recibido"]').val('');
                    $('input[name="entregado_recibido"]').hide();
                } else{
                    $('label[for="entregado_recibido"]').show();
                    $('input[name="entregado_recibido"]').show();
                }
                if(tipo==1) $('label[for="entregado_recibido"]').empty().html('Hemos recibido de:');
                if(tipo==2) $('label[for="entregado_recibido"]').empty().html('Hemos entregado a:');
                if(tipo==3) $('label[for="entregado_recibido"]').empty().html('');
            }
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
            var opcion = confirm("Estas por modificar un comprobante. ¿Estas seguro que desea continuar?");
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