@extends('adminlte::page')
@section('title', 'Estado de resultados')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-warning text-white">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-title"><b>ESTADO DE RESULTADOS</b></div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('proyecto_id',$proyectos,$proyecto_id, ['class' => 'form-control form-control-sm bg-warning text-center', 'id' => 'proyecto_id']) !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['estadoresultadof.search']]) !!}
                    @include('estado-resultado.partials.search')
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
        $(document).ready(function() {
            $("#proyecto_id").change(function () {
				if($("#proyecto_id option:selected").val() != null){
					var url = '{{ route("balanceapertura.index", ":id") }}';
					var id = $("#proyecto_id option:selected").val();
					url = url.replace(':id', id);
					window.location.href=url;
				}
			});
            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
        });

        $("#finicial").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        $("#ffinal").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        function countChars_i(obj){
            var cont = obj.value.length;
                if(cont > 9){
                    var fecha = document.getElementById("finicial").value;
                    var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                    if ((fecha.match(RegExPattern)) && (fecha!='')) {
                        
                    } else {
                        document.getElementById("message_i").innerHTML = "(Error)";
                        document.getElementById("finicial").value = "";
                    }
                }
            }

        function countChars_f(obj){
            var cont = obj.value.length;
                if(cont > 9){
                    var fecha = document.getElementById("ffinal").value;
                    var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                    if ((fecha.match(RegExPattern)) && (fecha!='')) {
                        
                    } else {
                        document.getElementById("message_f").innerHTML = "(Error)";
                        document.getElementById("ffinal").value = "";
                    }
                }
            }
    </script>
@stop