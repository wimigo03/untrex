@extends('adminlte::page')
@section('title', 'Usuarios')
@section('content')
@include('components.flash_alerts')
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card-title"><b>USUARIO - CREAR</b></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <span class="tts:down tts-slideIn tts-custom" aria-label="Retroceder">
                            <a href="{{ route('usuario.index')}} " class="btn btn-sm text-white">
                                <i class="fa fa-reply" aria-hidden="true"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('usuario.store') }}" method="post" id="form">
                    @csrf
                    @include('usuarios.partials.form_create')
                </form>
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary font-verdana-bg" type="button" onclick="procesar();">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            &nbsp;Procesar
                        </button>
                        <button class="btn btn-danger font-verdana-bg" type="button" onclick="cancelar();">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            &nbsp;Cancelar
                        </button>
                        <i class="fa fa-spinner custom-spinner fa-spin fa-2x fa-fw spinner-btn-send" style="display: none;"></i>
                    </div>
                </div>
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
        $(document).ready(function() {
            $('#consorcio').select2({
                placeholder: "--Seleccionar--"
            });

            $('#socio').select2({
                placeholder: "--Seleccionar--"
            });
        });

        function procesar(){
            $(".btn").hide();
            $(".spinner-btn-send").show();
            $("#form").submit();    
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn-send").show();
            window.location.href = "{{ route('usuario.index') }}";
        }
    </script>
@stop