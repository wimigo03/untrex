@extends('adminlte::page')
@section('title', 'Usuarios')
@section('content')
@include('components.flash_alerts')
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>USUARIOS</b></div>
            </div>
            <div class="card-body">
                <form action="#" method="get" id="form">
                    @include('usuarios.partials.search')
                </form>
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary font-verdana" type="button" onclick="search();">
                            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar
                        </button>
                        <span class="tts:left tts-slideIn tts-custom" aria-label="Limpiar datos de la Busqueda">
                            <button class="btn btn-danger font-verdana text-white" type="button" onclick="limpiar();">
                                &nbsp;<i class="fa fa-eraser"></i>&nbsp;Limpiar
                            </button>
                        </span>
                        <i class="fa fa-spinner custom-spinner fa-spin fa-2x fa-fw spinner-btn" style="display: none;"></i>
                    </div>
                </div>
                @if (isset($usuarios) && (count($usuarios) > 0))
                    @include('usuarios.partials.table')
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
    <link rel="stylesheet" href="/css/style.css">
    {{--<link rel="stylesheet" href="/css/select2.min.css" type="text/css">--}}
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#socios').select2({
                placeholder: "--Seleccionar--"
            });
        } );

        function search(){
            var url = "{{ route('usuario.search') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".btn-importar").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function limpiar(){
            $(".btn").hide();
            $(".btn-importar").hide();
            $(".spinner-btn").show();
            window.location.href = "{{ route('usuario.index') }}";
        }
    </script>
@stop