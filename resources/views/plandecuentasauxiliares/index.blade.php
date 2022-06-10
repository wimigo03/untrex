@extends('adminlte::page')
@section('title', 'Plan de cuentas auxiliares')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>PLAN DE CUENTAS AUXILIARES - {{$proyecto->nombre}}</b></div>
            </div>
            <div class="card-body">
                {{--{!! Form::model(Request::all(),['route'=> ['cotizaciones.search'],'onsubmit' => "return validacion_form()"]) !!}
                    @include('cotizaciones.partials.search')
                {!! Form::close()!!}--}}
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <a href="{{route('plandecuentasauxiliares.create',$proyecto->id)}}" class="btn btn-success font-verdana-bg">
                            <i class="fas fa-plus"></i>&nbsp;Crear Nuevo auxiliar
                        </a>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="table-responsive table-striped table-bordered">
                            <table id="dataTable" class="display responsive" style="width:100%">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>ID</b></td>
                                        <td class="text-center p-1"><b>TIPO</b></td>
                                        <td class="text-center p-1"><b>CREACION</b></td>
                                        <td class="text-center p-1"><b>NOMBRE</b></td>
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

                {{--<div class="form-group row">
                    <div class="col-md-12 text-center">
                        <div class="table-responsive table-striped">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>ID</b></td>
                                        <td class="text-center p-1"><b>TIPO</b></td>
                                        <td class="text-center p-1"><b>CREACION</b></td>
                                        <td class="text-center p-1"><b>NOMBRE</b></td>
                                        <td class="text-center p-1"><b>ESTADO</b></td>
                                        <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                                    </tr>
                                    <tbody>
                                </thead>
                                    @foreach ($plancuentasauxiliares as $datos)
                                        <tr class="font-verdana">
                                            <td class="text-center p-1">{{$datos->id}}</td>
                                            <td class="text-center p-1">
                                                @if ($datos->tipo == 1)
                                                    <span class="badge bg-secondary font-verdana-sm">PROVEEDOR</span>
                                                @else
                                                    @if ($datos->tipo == 2)
                                                        <span class="badge bg-secondary font-verdana-sm">TRABAJADOR</span>
                                                    @else
                                                        @if ($datos->tipo == 3)
                                                            <span class="badge bg-secondary font-verdana-sm">CLIENTE</span>
                                                        @else
                                                            <span class="badge bg-secondary font-verdana-sm">OTRO</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center p-1">{{\Carbon\Carbon::parse($datos->created_at)->format('d/m/Y')}}</td>
                                            <td class="text-left p-1">{{$datos->nombre}}</td>
                                            <td class="text-center p-1">
                                                @if ($datos->estado == 1)
                                                    <span class="badge badge-success font-verdana-sm">ACTIVO</span>
                                                @else
                                                    <span class="badge bg-danger font-verdana">NO ACTIVO</span>
                                                @endif
                                            </td>
                                            <td class="text-center p-1">
                                                <a href="" class="btn btn-xs btn-warning">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>--}}
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
        $('#dataTable').DataTable({
            "processing":true,
            "serverSide":true,
            "ajax":"{{ url('admin/plandecuentasauxiliares/indexAjax/' . $proyecto->id) }}",
            "columns": [
                {data: 'plancuentaauxiliar_id', name:'a.id', class:'text-center p-1 font-verdana'},
                {data: 'tipo_auxiliar', name:'tipo_auxiliar', class:'text-center p-1 font-verdana'},
                {data: 'fecha_auxiliar', name:'fecha_auxiliar', class:'text-center p-1 font-verdana'},
                {data: 'nombre', name:'a.nombre', class:'text-left p-1 font-verdana'},
                {data: 'estado_auxiliar', name:'estado_auxiliar', class:'text-center p-1 font-verdana'},
                /*{data: 'status_search', name:'status_search', class:'text-center p-1 font-verdana',render: function(data, type, row){
                    if(row.status_search === 'ACTIVO'){
                        return '<span class="btn btn-xs btn-success font-verdana-sm"><b>ACTIVO</b></span>';
                    }else{
                        return '<span class="btn btn-xs btn-danger font-verdana-sm"><b>ELIMINADO</b></span>';       
                    }
                }
            },*/
                //{data: 'btnActions', class:'text-center p-1'}
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
@stop