@extends('adminlte::page')
@section('title', 'Usuarios')
@section('content')
@include('components.flash_alerts')
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>USUARIOS - PROYECTO - {{$proyecto->nombre}}</b></div>
            </div>
            <div class="card-body">
                <input type="hidden" name="proyecto_id" value="{{$proyecto->id}}" id="proyecto_id">
                <form action="#" method="get" id="form">
                    @include('usuarios.partials.proyectos-search')
                </form>
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary font-verdana" type="button" onclick="search();">
                            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar
                        </button>
                        <button class="btn btn-danger font-verdana text-white" type="button" onclick="limpiar();">
                            &nbsp;<i class="fa fa-eraser"></i>&nbsp;Limpiar
                        </button>
                        <button class="btn btn-success font-verdana text-white" type="button" onclick="agregar();">
                            &nbsp;<i class="fa fa-plus"></i>&nbsp;Añadir usuario a proyecto
                        </button>
                        <button class="btn btn-danger font-verdana text-white" type="button" onclick="volver();">
                            &nbsp;<i class="fa fa-eraser"></i>&nbsp;Volver
                        </button>
                        <i class="fa fa-spinner custom-spinner fa-spin fa-2x fa-fw spinner-btn" style="display: none;"></i>
                    </div>
                </div>
                @if (isset($users_proyectos) && (count($users_proyectos) > 0))
                    @include('usuarios.partials.proyectos-table')
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
    <link rel="stylesheet" href="/css/style.css">
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#usuarios').select2({
                placeholder: "--Usuario--"
            });
            $('#estados').select2({
                placeholder: "--Estado--"
            });
        } );

        function search(){
            var id = $("#proyecto_id").val();
            var url = "{{ route('usuario.proyecto.search',':id') }}";
            url = url.replace(':id',id);
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
            var id = $("#proyecto_id").val();
            var url = "{{ route('usuario.proyecto.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function volver(){
            $(".btn").hide();
            $(".btn-importar").hide();
            $(".spinner-btn").show();
            var url = "{{ route('consorcios.index') }}";
            window.location.href = url;
        }

        function agregar(){
            $(".btn").hide();
            $(".btn-importar").hide();
            $(".spinner-btn").show();
            var id = $("#proyecto_id").val();
            var url = "{{ route('usuario.proyecto.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop