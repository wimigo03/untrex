@extends('adminlte::page')
@section('title', 'Plan de cuentas')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('/') }}"><i class="fas fa-home fa-xs"></i> <font size="2px">Inicio</font></a> /
        <a href="#"><i class="fas fa-sitemap fa-xs"></i><font size="2px"> Estructura</font></a>
    </div>
</div>
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="card-title"><b>PLAN DE CUENTAS</b>
                    {{--<a href="{{ route('personal.index') }}" class="btn btn-sm pull-right text-white"><i class="fa fa-reply" aria-hidden="true"></i></a>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row font-verdana-bg">
                    <div class="col-md-2">
                        {{Form::label('Codigo_padre','Codigo Cuenta',['class' => 'd-inline'])}}
                        {{Form::text('codigo_padre',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_codigo_padre'])}}
                    </div>
                    <div class="col-md-4">
                        {{Form::label('Nombre','Cuenta',['class' => 'd-inline'])}}
                        {{Form::text('nombre',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_nombre'])}}
                    </div>
                    <div class="col-md-6">
                        {{Form::label('Descripcion','Descripcion',['class' => 'd-inline'])}}
                        {{Form::text('descripcion',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_descripcion'])}}
                    </div>
                </div>
                <div class="form-group row font-verdana-bg">
                    <div class="col-md-2">
                        {{Form::label('Cuenta_detalle','¿Es Cuenta detalle?',['class' => 'd-inline'])}}
                        {{Form::text('cuenta_detalle',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_cuenta_detalle'])}}
                    </div>
                    <div class="col-md-2">
                        {{Form::label('Cheque','Cheque',['class' => 'd-inline'])}}
                        {{Form::text('cheque',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_cheque'])}}
                    </div>
                    <div class="col-md-8 text-right">
                        <br>
                        <a href="{{route('editar_dependiente','editar-dependiente')}}" id="editar-dependiente" data-url='{{route('editar_dependiente','editar-dependiente')}}' class="btn btn-warning font-verdana-bg">
                            <i class="fas fa-edit"></i>&nbsp;Modificar
                        </a>
                        <a href="{{route('create_dependiente','create-dependiente')}}" id="create-dependiente" data-url='{{route('create_dependiente','create-dependiente')}}' class="btn btn-success font-verdana-bg">
                            <i class="fas fa-plus"></i>&nbsp;Dependiente
                        </a>
                    </div>
                </div>
                <div class="form-group row mt-2 mb-2 font-verdana-bg">
                    <div class="col-md-12" id="treeview">
                        {!! $html !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/treeview.css">
    <link rel="stylesheet" href="/css/style.css">
@stop

@section('js')
    {{--<script type="text/javascript" src="/js/plan_cuentas/index.js"></script>--}}
    <script type="text/javascript" src="/js/plan_cuentas/treeview.js"></script>
    <script type="text/javascript" src="/js/plan_cuentas/jquery.min.js"></script>
    <script type="text/javascript" src="/js/plan_cuentas/bootstrap.bundle.min.js"></script>
    <script>
        /*$(document).ready(function() {
            $(':text,:hidden').val('');
        } );*/
    </script>
@stop