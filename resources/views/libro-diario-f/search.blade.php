@extends('adminlte::page')
@section('title', 'Plan de cuenta')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="card-title"><b>LIBRO DIARIO - {{strtoupper($proyecto->nombre)}}</b></div>
            </div>
            <div class="card-body">
                <form action="#" method="get" id="form">
                    <input type="hidden" name="proyecto" value="{{$proyecto->id}}">
                    <input type="hidden" name="fecha_i" value="{{$fecha_inicial}}">
                    <input type="hidden" name="fecha_f" value="{{$fecha_final}}">
                    <input type="hidden" name="tipo_comp" value="{{$tipo_comprobante}}">
                    <input type="hidden" name="estado" value="{{$estado}}">
                </form>
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-danger font-verdana text-white" type="button" onclick="limpiar();">
                            &nbsp;<i class="fa fa-eraser"></i>&nbsp;Limpiar
                        </button>
                        <button class="btn btn-success font-verdana" type="button" onclick="excel();">
                            <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar a Excel
                        </button>
                        <i class="fa fa-spinner custom-spinner fa-spin fa-2x fa-fw spinner-btn" style="display: none;"></i>
                    </div>
                </div>
                @include('libro-diario-f.partials.encabezado')
                @include('libro-diario-f.partials.table')
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
        function limpiar(){
            $(".btn").hide();
            $(".btn-importar").hide();
            $(".spinner-btn").show();
            window.location.href = "{{ route('librodiariof.index') }}";
        }

        function excel(){
            var url = "{{ route('librodiariof.excel') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }   
    </script>
@stop