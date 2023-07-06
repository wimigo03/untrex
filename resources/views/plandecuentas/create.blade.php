@extends('adminlte::page')
@section('title', 'Untrex')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('/admin') }}"><i class="fas fa-home fa-xs"></i> <font size="2px">Inicio</font></a> /
        <a href="{{ url('/plandecuentas') }}"><i class="fas fa-sitemap fa-xs"></i> <font size="2px">Estructura</font></a> /
        <a href="#"><i class="fas fa-plus-square fa-xs"></i><font size="2px"> Crear</font></a>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>CREAR PLAN DE CUENTAS</b>
                    {{--<a href="{{ route('personal.index') }}" class="btn btn-sm pull-right text-white"><i class="fa fa-reply" aria-hidden="true"></i></a>--}}
                </div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['store_dependiente'],'onsubmit' => "return validacion_form()"]) !!}
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::hidden('proyecto_id',$proyecto_id)}}
                            {{Form::hidden('parent_id',$parent->id,['readonly'=> true, 'id' => 'id_plancuenta'])}}
                            {{Form::label('Codigo_padre','Codigo Cuenta',['class' => 'd-inline'])}}
                            {{Form::text('codigo_padre',$parent->codigo,['readonly'=> true, 'class'=>'form-control form-control-sm', 'id' => 'codigo_padre'])}}
                        </div>
                        <div class="col-md-4">
                            {{Form::label('Nombre_padre','Cuenta',['class' => 'd-inline'])}}
                            {{Form::text('nombre_padre',$parent->nombre,['readonly'=> true, 'class'=>'form-control form-control-sm', 'id' => 'nombre_padre'])}}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-4">
                            {{Form::label('Nombre_dependiente','Sub Cuenta',['class' => 'd-inline'])}}
                            {{Form::text('nombre_dependiente',null,['class'=>'form-control form-control-sm'. ( $errors->has('nombre_dependiente') ? ' is-invalid' : '' ), 'id' => 'nombre_dependiente'])}}
                            {!! $errors->first('nombre_dependiente','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-8">
                            {{Form::label('Descripcion','descripcion',['class' => 'd-inline'])}}
                            {{Form::text('descripcion',null,['class'=>'form-control form-control-sm'. ( $errors->has('descripcion') ? ' is-invalid' : '' ), 'id' => 'descripcion'])}}
                            {!! $errors->first('descripcion','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::label('Cuenta_detalle','¿Es Cuenta detalle?',['class' => 'd-inline'])}}
                            {!! Form::select('cuenta_detalle', array('1'=>'Si','0'=>'No'), null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('cuenta_detalle') ? ' is-invalid' : '' ), 'id' => 'cuenta_detalle']) !!}
                            {!! $errors->first('cuenta_detalle','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('Cheque','Cheque',['class' => 'd-inline'])}}
                            {!! Form::select('cheque', array('1'=>'Si','0'=>'No'), null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('cheque') ? ' is-invalid' : '' ), 'id' => 'cheque']) !!}
                            {!! $errors->first('cheque','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('Moneda','Moneda',['class' => 'd-inline'])}}
                            {!! Form::select('moneda', array('BOLIVIANOS'=>'BOLIVIANOS','DOLARES'=>'DOLARES'), null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('moneda') ? ' is-invalid' : '' ), 'id' => 'moneda']) !!}
                            {!! $errors->first('moneda','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-6 text-right">
                            <br>
                            <a href="{{--route('fondos_a_rendir.index')--}}" class="btn btn-danger font-verdana-bg">
                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
                            </a>
                            <button type="submit" class="btn btn-success font-verdana-bg">
                                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Agregar&nbsp;
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
@stop

@section('js')
    <script>
        function validacion_form() {
            var opcion = confirm("¿Esta seguro que desea continuar...");
            if (opcion == true) {
                return true;
                } else {
                    return false;
                    }
            return true;
        }
    </script>
@stop