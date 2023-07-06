@extends('adminlte::page')
@section('title', 'Untrex')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
{{--<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('/admin') }}"><i class="fas fa-home fa-xs"></i> <font size="2px">Inicio</font></a> /
        <a href="{{ url('/plandecuentasauxiliares') }}"><i class="fas fa-sitemap fa-xs"></i> <font size="2px">Estructura</font></a> /
        <a href="#"><i class="fas fa-plus-square fa-xs"></i><font size="2px"> Crear</font></a>
    </div>
</div>--}}
<br>
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>CREAR PLAN DE CUENTA AUXILIAR - {{$proyecto->nombre}}</b>
                    {{--<a href="{{ route('personal.index') }}" class="btn btn-sm pull-right text-white"><i class="fa fa-reply" aria-hidden="true"></i></a>--}}
                </div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['plandecuentasauxiliares.store'],'onsubmit' => "return validacion_form()"]) !!}
                    {!! Form::hidden('proyecto_id',$proyecto->id) !!}
                    <div class="form-group row font-verdana-bg">
                        <div class="col-md-3">
                            {{Form::label('Tipo','Tipo',['class' => 'd-inline'])}}
                            {!! Form::select('tipo', array('1'=>'PROVEEDOR','2'=>'TRABAJADOR','3'=>'CLIENTE','4'=>'OTRO'), null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('tipo') ? ' is-invalid' : '' ), 'id' => 'tipo']) !!}
                            {!! $errors->first('tipo','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-5">
                            {{Form::label('Auxiliar','Auxiliar',['class' => 'd-inline'])}}
                            {{Form::text('auxiliar',null,['class'=>'form-control form-control-sm', 'id' => 'auxiliar'])}}
                            {!! $errors->first('auxiliar','<span class="invalid-feedback d-block">:message</span>') !!}
                        </div>
                        <div class="col-md-4 text-right">
                            <br>
                            <button type="submit" class="btn btn-primary font-verdana-bg">
                                <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Guardar&nbsp;
                            </button>
                            <a href="{{route('plandecuentasauxiliares.index',$proyecto->id)}}" class="btn btn-danger font-verdana-bg">
                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar&nbsp;
                            </a>
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