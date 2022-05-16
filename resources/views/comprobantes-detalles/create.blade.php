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
                {!! Form::model(Request::all(),['route'=> ['comprobantesdetalles.insertar']]) !!}
                    {{Form::hidden('comprobante_id',$comprobante->id)}}
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
                            {{Form::text('nombre',strtoupper($user->name),['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nombre'])}}
                            {!! $errors->first('nombre','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-1">
                            {{ Form::label('moneda','Moneda',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::text('moneda', $comprobante->moneda, ['readonly'=>true,'class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('moneda') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('moneda','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{ Form::label('empresa','Empresa',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::text('empresa', $empresa->nombre, ['readonly'=>true,'class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('empresa') ? ' is-invalid' : '' )]) !!}
                            {!! $errors->first('empresa','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::label('Fecha','Fecha Comprobante',['class' => 'd-inline'])}}
                            {{Form::text('fecha',\Carbon\Carbon::parse($comprobante->fecha)->format('d/m/Y'),['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countChars(this);'])}}
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
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-3">
                            {{Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('proyecto',$proyectos,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('proyectos') ? ' is-invalid' : '' ),'id'=>'proyectos']) !!}
                            {!! $errors->first('proyecto','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-3">
                            {{Form::label('centro','Centro',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('centro',$centros,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('centros') ? ' is-invalid' : '' ),'id'=>'centros']) !!}
                            {!! $errors->first('centro','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-6">
                            {{Form::label('plan_cuenta','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('plan_cuenta',$plan_cuentas,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuentas') ? ' is-invalid' : '' ),'id'=>'plan_cuentas']) !!}
                            {!! $errors->first('plan_cuenta','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            {{Form::label('plan_cuenta_auxiliar','Auxiliar',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('plan_cuenta_auxiliar',$plan_cuentas_auxiliares,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuentas_auxiliares') ? ' is-invalid' : '' ),'id'=>'plan_cuentas_auxiliares']) !!}
                            {!! $errors->first('plan_cuenta_auxiliar','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('tipo_transaccion','Tipo',['class' => 'd-inline font-verdana-bg'])}}
                            {!! Form::select('tipo_transaccion', array('CHEQUE'=>'CHEQUE','TRANSFERENCIA'=>'TRANSFERENCIA'), null, ['class' => 'form-control form-control-sm ', 'placeholder' => '--Seleccionar--', 'id' => 'tipo_transaccion']) !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('cheque_nro','N° Cheque',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('cheque_nro',null,['class'=>'text-uppercase form-control form-control-sm'. ( $errors->has('cheque_nro') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
                            {!! $errors->first('cheque_nro','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-4">
                            {{Form::label('cheque_orden','A la Orden',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('cheque_orden',null,['class'=>'text-uppercase form-control form-control-sm'. ( $errors->has('cheque_orden') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
                            {!! $errors->first('cheque_orden','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            {{Form::label('glosa','Glosa',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('glosa',null,['class'=>'form-control form-control-sm'. ( $errors->has('glosa') ? ' is-invalid' : '' ),'autocomplete'=>'off','id'=>'glosa'])}}
                            {!! $errors->first('glosa','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2 vista-debe-bs">
                            {{Form::label('debe_bs','Debe (Bs.)',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('debe_bs',null,['class'=>'form-control form-control-sm'. ( $errors->has('debe_bs') ? ' is-invalid' : '' ),'id'=>'debe_bs','autocomplete'=>'off'])}}
                            {!! $errors->first('debe_bs','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2 vista-haber-bs">
                            {{Form::label('haber_bs','Haber (Bs.)',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('haber_bs',null,['class'=>'form-control form-control-sm'. ( $errors->has('haber_bs') ? ' is-invalid' : '' ),'id'=>'haber_bs','autocomplete'=>'off'])}}
                            {!! $errors->first('haber_bs','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2 vista-debe-sus">
                            {{Form::label('debe_sus','Debe ($u$)',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('debe_sus',null,['class'=>'form-control form-control-sm'. ( $errors->has('debe_sus') ? ' is-invalid' : '' ),'id'=>'debe_sus','autocomplete'=>'off', 'readonly'=>'readonly'])}}
                            {!! $errors->first('debe','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2 vista-haber-sus">
                            {{Form::label('haber_sus','Haber ($u$)',['class' => 'd-inline font-verdana-bg'])}}
                            {{Form::text('haber_sus',null,['class'=>'form-control form-control-sm'. ( $errors->has('haber_sus') ? ' is-invalid' : '' ),'id'=>'haber_sus','autocomplete'=>'off', 'readonly'=>'readonly'])}}
                            {!! $errors->first('haber_sus','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary font-verdana-bg" type="submit" id="insertar">
                                <i class="fa fa-arrow-down"></i>&nbsp;Insertar</button>
                        </div>
                    </div>
                    {{--<div class="form-group row font-verdana-bg">
                        <div class="col-md-12 text-right">
                            <br>
                            <a href="{{route('comprobantes.index')}}" class="btn btn-danger font-verdana-bg">
                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
                            </a>
                            <button type="submit" class="btn btn-primary font-verdana-bg">
                                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Registrar&nbsp;
                            </button>
                        </div>
                    </div>--}}
                {!! Form::close()!!}

                <div class="form-group row">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-header font-verdana">
                                    <td class="text-center p-1"><b>NRO</b></td>
                                    <td class="text-center p-1"><b>CUENTA</b></td>
                                    <td class="text-center p-1"><b>PROYECTO</b></td>
                                    <td class="text-center p-1"><b>CENTRO</b></td>
                                    <td class="text-center p-1"><b>AUXILIAR</b></td>
                                    <td class="text-center p-1"><b>GLOSA</b></td>
                                    <td class="text-center p-1"><b>DEBE</b></td>
                                    <td class="text-center p-1"><b>HABER</b></td>
                                    <td colspan="2" class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
    <link rel="stylesheet" href="/css/select2.min.css" type="text/css">
@stop

@section('js')
    <script src="/datepicker/datepicker.min.js" type="text/javascript"></script>
    <script src="/datepicker/datepicker.es.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/select2.min.js"></script>
    {{--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
    <script>
        $(document).ready(function() {
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