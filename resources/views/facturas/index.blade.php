@extends('adminlte::page')
@section('title', 'Comprobantes')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card-title"><b>FACTURAS</b></div>
                    </div>
                    @if (isset($facturas))
                        <div class="col-md-2 text-right">
                            <a href="{{route('facturas.index')}}" class="text-white">
                                &nbsp;<i class="fas fa-reply" aria-hidden="true"></i>&nbsp;
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        {!! Form::model(Request::all(),['route'=> ['facturas.search']]) !!}
                            @include('facturas.partials.search')
                        {!! Form::close()!!}
                    </div>
                </div>
                @if (isset($facturas))
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="table-responsive table-striped">
                                    <table id="table" class="display responsive" style="width:100%">
                                        <thead>
                                            <tr class="font-verdana">
                                                <td class="text-center p-1"><b>ID</b></td>
                                                <td class="text-center p-1"><b>SOCIO</b></td>
                                                <td class="text-center p-1"><b>PROVEEDOR</b></td>
                                                <td class="text-center p-1"><b>NRO. FACTURA</b></td>
                                                <td class="text-center p-1"><b>FECHA</b></td>
                                                <td class="text-center p-1"><b>GLOSA</b></td>
                                                <td class="text-center p-1"><b>MONTO</b></td>
                                                <td class="text-center p-1"><b>ESTADO</b></td>
                                                {{--<td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>--}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($facturas as $datos)
                                                <tr class="font-verdana">
                                                    <td class="text-center p-1">
                                                        {{$datos->factura_id}}
                                                    </td>
                                                    <td class="text-center p-1">
                                                        {{$datos->socio}}
                                                    </td>
                                                    <td class="text-justify p-1">
                                                        {{$datos->razon_social}}
                                                    </td>
                                                    <td class="text-center p-1">
                                                        {{$datos->numero}}
                                                    </td>
                                                    <td class="text-center p-1">
                                                        {{$datos->fecha}}
                                                    </td>
                                                    <td class="text-justify p-1">
                                                        {{$datos->glosa}}
                                                    </td>
                                                    <td class="text-center p-1">
                                                        {{$datos->monto}}
                                                    </td>
                                                    <td class="text-center p-1">
                                                        @if ($datos->estado_search == 'VALIDO')
                                                            <span class="btn btn-xs btn-success font-verdana-sm"><b>VALIDO</b></span>
                                                        @else
                                                            <span class="btn btn-xs btn-danger font-verdana-sm"><b>ANULADO</b></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="table-responsive table-striped">
                                    <table id="dataTable" class="display responsive" style="width:100%">
                                        <thead>
                                            <tr class="font-verdana">
                                                <td class="text-center p-1"><b>ID</b></td>
                                                <td class="text-center p-1"><b>SOCIO</b></td>
                                                <td class="text-center p-1"><b>PROVEEDOR</b></td>
                                                <td class="text-center p-1"><b>NRO. FACTURA</b></td>
                                                <td class="text-center p-1"><b>FECHA</b></td>
                                                <td class="text-center p-1"><b>GLOSA</b></td>
                                                <td class="text-center p-1"><b>MONTO</b></td>
                                                <td class="text-center p-1"><b>ESTADO</b></td>
                                                {{--<td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>--}}
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
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/style.css">
    {{--<link rel="stylesheet" href="/css/pagination.css">--}}
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
                "ajax":"{{ route('facturas.indexAjax') }}",
                "columns": [
                    {data: 'factura_id', name:'a.id', class:'text-center p-1 font-verdana'},
                    {data: 'socio', name:'d.abreviatura', class:'text-center p-1 font-verdana'},
                    {data: 'razon_social', name:'b.razon_social', class:'text-justify p-1 font-verdana'},
                    {data: 'numero', name:'a.numero', class:'text-center p-1 font-verdana'},
                    {data: 'fecha', name:'a.fecha', class:'text-center p-1 font-verdana'},
                    {data: 'glosa', name:'glosa', class:'text-justify p-1 font-verdana'},
                    {data: 'monto', name:'monto', class:'text-right p-1 font-verdana'},
                    {data: 'estado_search', name:'estado_search', class:'text-center p-1 font-verdana',render: function(data, type, row){
                        if(row.estado_search === 'VALIDO'){
                            return '<span class="btn btn-xs btn-success font-verdana-sm"><b>VALIDO</b></span>';
                        }else{
                            return '<span class="btn btn-xs btn-danger font-verdana-sm"><b>ANULADO</b></span>';       
                        }
                    }
                },
                    //{data: 'btnActions', class:'text-justify p-1'}
                ],
                "iDisplayLength": 10,
                "order": [[ 4, "desc" ]],
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

        function valideKey(evt){
            var code = (evt.which) ? evt.which : evt.keyCode;
            if(code==8){
                return true;
            }else if(code==46 || (code>=48 && code<=57)){
                return true;
            }else{
                return false;
            }
        }
    </script>
@stop