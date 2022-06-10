@extends('adminlte::page')
@section('title', 'Cotizaciones')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>COTIZACIONES</b></div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['tipo_cambio.search'],'onsubmit' => "return validacion_form()"]) !!}
                    @include('tipo-cambio.partials.search')
                {!! Form::close()!!}
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="table-responsive table-striped table-bordered">
                            <table id="dataTable" class="display responsive" style="width:100%">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>ID</b></td>
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        <td class="text-center p-1"><b>OFICIAL</b></td>
                                        <td class="text-center p-1"><b>COMPRA</b></td>
                                        <td class="text-center p-1"><b>VENTA</b></td>
                                        <td class="text-center p-1"><b>UFV</b></td>
                                        <td class="text-center p-1"><b>ESTADO</b></td>
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
                "ajax":"{{ route('tipo_cambio.indexAjax') }}",
                "columns": [
                    {data: 'tipo_cambio_id', name:'a.id', class:'text-center p-1 font-verdana'},
                    {data: 'fecha_cambio', name:'fecha_cambio', class:'text-center p-1 font-verdana'},
                    {data: 'dolar_oficial', name:'a.dolar_oficial', class:'text-center p-1 font-verdana'},
                    {data: 'dolar_compra', name:'a.dolar_compra', class:'text-center p-1 font-verdana'},
                    {data: 'dolar_venta', name:'a.dolar_venta', class:'text-center p-1 font-verdana'},
                    {data: 'ufv', name:'a.ufv', class:'text-center p-1 font-verdana'},
                    {data: 'status_search', name:'status_search', class:'text-center p-1 font-verdana',render: function(data, type, row){
                        if(row.status_search === 'ACTIVO'){
                            return '<span class="btn btn-xs btn-success font-verdana-sm"><b>ACTIVO</b></span>';
                        }else{
                            return '<span class="btn btn-xs btn-danger font-verdana-sm"><b>ELIMINADO</b></span>';       
                        }
                    }
                },
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
        $("#fecha_desde").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        $("#fecha_hasta").datepicker({
            inline: false,
            dateFormat: "dd/mm/yyyy",
            autoClose: true
        });

        function countCharsDesde(obj){
        var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha_desde").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_desde").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_desde").value = "";
                }
            }
        }

        function countCharsHasta(obj){
        var cont = obj.value.length;
            if(cont > 9){
                var fecha = document.getElementById("fecha_hasta").value;
                var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                if ((fecha.match(RegExPattern)) && (fecha!='')) {
                    
                } else {
                    document.getElementById("message_hasta").innerHTML = "(Formato no valido)";
                    document.getElementById("fecha_hasta").value = "";
                }
            }
        }
    </script>
@stop