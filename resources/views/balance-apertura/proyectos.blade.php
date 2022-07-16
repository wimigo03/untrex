@extends('adminlte::page')
@section('title', 'Proveedor')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-title"><b>BALANCE DE APERTURA</b></div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('proyecto_id',$proyectos,null, ['placeholder'=>'--Seleccionar--','class' => 'form-control form-control-sm  bg-gray text-center', 'id' => 'proyecto_id']) !!}
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

        /*$("#fecha").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });*/

        /*function countChars(obj){
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
        }*/
    </script>
@stop