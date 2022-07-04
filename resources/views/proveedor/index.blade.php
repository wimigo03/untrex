@extends('adminlte::page')
@section('title', 'Proveedor')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card-title"><b>PROVEEDORES</b></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{route('proveedor.create')}}" class="text-white">
                            &nbsp;<i class="fas fa-plus-circle" aria-hidden="true"></i>&nbsp;
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card card-custom">
                    <div class="card-body">
                        {{--{!! Form::model(Request::all(),['route'=> ['proveedor.search']]) !!}
                            @include('proveedor.partials.search')
                        {!! Form::close()!!}--}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="card card-custom">
                            <div class="card-body">
                                <div class="table-responsive table-striped">
                                    <table id="dataTable" class="display responsive" style="width:100%">
                                        <thead>
                                            <tr class="font-verdana">
                                                <td class="text-center p-1"><b>ID</b></td>
                                                <td class="text-center p-1"><b>RAZON SOCIAL</b></td>
                                                <td class="text-center p-1"><b>NOMBRE COMERCIAL</b></td>
                                                <td class="text-center p-1"><b>NIT</b></td>
                                                <td class="text-center p-1"><b>CIUDAD</b></td>
                                                <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
            $('#dataTable').DataTable({
                "processing":true,
                "serverSide":true,
                "ajax":"{{ route('proveedor.indexAjax') }}",
                "columns": [
                    {data: 'proveedor_id', name:'a.id', class:'text-center p-1 font-verdana'},
                    {data: 'razon_social', name:'a.razon_social', class:'text-justify p-1 font-verdana'},
                    {data: 'nombre_comercial', name:'a.nombre_comercial', class:'text-justify p-1 font-verdana'},
                    {data: 'nit', name:'a.nit', class:'text-center p-1 font-verdana'},
                    {data: 'nombre', name:'b.nombre', class:'text-center p-1 font-verdana'},
                    {data: 'btnActions', class:'text-center p-1'}
                ],
                "iDisplayLength": 10,
                "order": [[ 0, "desc" ]],
                "language":{
                    "info": "Mostrando _START_ al _END_ de _TOTAL_","search": '',"searchPlaceholder": "Buscar",
                    "paginate": {"next": "<small><b>Siguiente</b></small>","previous": "<small><b>Anterior</b></small>",},
                    "lengthMenu": '<small>Mostrar</small> <select class="form form-control-sm">'+'<option value="10">10</option>'+'<option value="25">25</option>'+'<option value="50">50</option>'+'<option value="100">100</option>'+'<option value="-1">Todos</option>'+'</select> <small>registros</small>',
                    "loadingRecords": "...Cargando...","processing": "...Procesando...","emptyTable": "No hay datos","zeroRecords": "No hay resultados para mostrar","infoEmpty": "Ningun registro encontrado","infoFiltered": "(filtrados de un total de _MAX_ registros)"
                }
            });
        } );
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "--Seleccionar--"
            });
        } );

        $("#fecha").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        function countChars(obj){
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
        }
    </script>
@stop