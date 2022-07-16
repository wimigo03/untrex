@extends('adminlte::page')
@section('title', 'Balance de apertura')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card-title"><b>ACTUALIZAR BALANCE DE APERTURA</b></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{route('balanceapertura.index',$balance_apertura->proyecto_id)}}" class="text-white">
                            &nbsp;<i class="fas fa-reply" aria-hidden="true"></i>&nbsp;
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['balanceapertura.update']]) !!}
                    @include('balance-apertura.partials.form-editar')
                {!! Form::close()!!}
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/style.css">
    {{--<link rel="stylesheet" href="/datepicker/datepicker.min.css"/>--}}
    <link rel="stylesheet" href="/css/select2.min.css" type="text/css">
@stop
@section('js')
    {{--<script src="/datepicker/datepicker.min.js" type="text/javascript"></script>
    <script src="/datepicker/datepicker.es.js" type="text/javascript"></script>--}}
    <script type="text/javascript" src="/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaAjax').DataTable({
                "processing":true,
                "paginate":false,
                "lengthMenu":false,
                "info":false,
                "iDisplayLength": -1,
                "order": [[ 0, "asc" ]],
                "language":{"info": "Mostrando _START_ al _END_ de _TOTAL_",
                "search": '',
                "searchPlaceholder": "Buscar",
                "paginate": {"next": "<b>Siguiente</b>","previous": "<b>Anterior</b>"},
                "lengthMenu": 'Mostrar <select class="form form-control-sm"><option value="-1">Todos</option></select> registros',
                /*"lengthMenu": 'Mostrar <select class="form form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="-1">Todos</option></select> registros',*/
                "loadingRecords": "...Cargando...",
                "processing": "...Procesando...",
                "emptyTable": "No hay datos",
                "zeroRecords": "No hay resultados para mostrar",
                "infoEmpty": "Ningun registro encontrado",
                "infoFiltered": "(filtrados de un total de _MAX_ registros)"
                }
            });

            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
        } );

        function valideKey(evt){
            var code = (evt.which) ? evt.which : evt.keyCode;
            if(code==46 || (code>=48 && code<=57)){
                return true;
            }else{
                return false;
            }
        }

        /*function copiarRazonSocial(dato){
            var razon_social = document.getElementById("razon_social").value;
            document.getElementById("nombre_comercial").value = razon_social;
        }*/

        /*$('#ciudades').change(function() {
            var ciudad_id =  $(this).val();
            if(ciudad_id == 10){
                $('.otra_ciudad').show();
            }else{
                $('.otra_ciudad').hide();
                document.getElementById("nueva_ciudad").value = "";
                document.getElementById("abreviatura").value = "";
            }
        });*/
    </script>
@stop