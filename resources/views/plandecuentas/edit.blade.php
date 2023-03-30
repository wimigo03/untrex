@extends('adminlte::page')
@section('title', 'Untrex')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('/admin') }}"><i class="fas fa-home fa-xs"></i> <font size="2px">Inicio</font></a> /
        <a href="{{ url('/admin/plandecuentas') }}"><i class="fas fa-sitemap fa-xs"></i> <font size="2px">Estructura</font></a> /
        <a href="#"><i class="fas fa-plus-square fa-xs"></i><font size="2px"> Crear</font></a>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>MODIFICAR PLAN DE CUENTAS</b></div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['store_editar_dependiente'],'onsubmit' => "return validacion_form()"]) !!}
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::hidden('plancuenta_id',$datos->id,['readonly'=> true, 'id' => 'plancuenta_id'])}}
                            {{Form::label('Codigo_padre','Codigo Padre',['class' => 'd-inline'])}}
                            {{Form::text('codigo_padre',$parent->codigo,['readonly'=> true, 'class'=>'form-control form-control-sm', 'id' => 'codigo_padre'])}}
                        </div>
                        <div class="col-md-4">
                            {{Form::label('Nombre_padre','Nombre Padre',['class' => 'd-inline'])}}
                            {{Form::text('nombre_padre',$parent->nombre,['readonly'=> true, 'class'=>'form-control form-control-sm', 'id' => 'nombre_padre'])}}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-4">
                            {{Form::label('Nombre_dependiente','Nombre Dependiente',['class' => 'd-inline'])}}
                            {{Form::text('nombre_dependiente',$datos->nombre,['class'=>'form-control form-control-sm'. ( $errors->has('nombre_dependiente') ? ' is-invalid' : '' ), 'id' => 'nombre_dependiente'])}}
                            {!! $errors->first('nombre_dependiente','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-8">
                            {{Form::label('Descripcion','descripcion',['class' => 'd-inline'])}}
                            {{Form::text('descripcion',$datos->descripcion,['class'=>'form-control form-control-sm'. ( $errors->has('descripcion') ? ' is-invalid' : '' ), 'id' => 'descripcion'])}}
                            {!! $errors->first('descripcion','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-2">
                            {{Form::label('Cuenta_detalle','Cuenta detalle',['class' => 'd-inline'])}}
                            {{--{!! Form::select('cuenta_detalle', array('1'=>'Si','0'=>'No'), $datos->cuenta_detalle, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('cuenta_detalle') ? ' is-invalid' : '' ), 'id' => 'cuenta_detalle']) !!}--}}
                            {{Form::text('cuenta_detalle',$cuenta_detalle,['readonly' => true,'class'=>'form-control form-control-sm'. ( $errors->has('cuenta_detalle') ? ' is-invalid' : '' ), 'id' => 'cuenta_detalle'])}}
                            {!! $errors->first('cuenta_detalle','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-2">
                            {{Form::label('Cheque','Cheque',['class' => 'd-inline'])}}
                            {{Form::text('cheque',$cheque,['readonly' =>true, 'class'=>'form-control form-control-sm'. ( $errors->has('cheque') ? ' is-invalid' : '' ), 'id' => 'cheque'])}}
                            {{--{!! Form::select('cheque', array('1'=>'Si','0'=>'No'), 0, ['class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('cheque') ? ' is-invalid' : '' ), 'id' => 'cheque']) !!}--}}
                            {!! $errors->first('cheque','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-8 text-right">
                            <br>
                            <a href="{{route('plandecuentas.index')}}" class="btn btn-danger font-verdana-bg">
                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
                            </a>
                            <button type="submit" class="btn btn-primary font-verdana-bg">
                                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Actualizar&nbsp;
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
            var opcion = confirm("¿Esta seguro que desea continuar, el proceso actual no puede deshacerse...");
            if (opcion == true) {
                return true;
                } else {
                    return false;
                    }
            return true;
        }
    </script>
@stop