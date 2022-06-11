@extends('adminlte::page')
@section('title', 'Comprobantes')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>COMPROBANTES</b></div>
            </div>
            <div class="card-body">
                {!! Form::model(Request::all(),['route'=> ['comprobantes.search']]) !!}
                    @include('comprobantes.partials.search')
                {!! Form::close()!!}
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="table-responsive table-striped table-bordered">
                            <table id="dataTable" class="display responsive" style="width:100%">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>ID</b></td>
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        <td class="text-center p-1"><b>COMPROBANTE</b></td>
                                        <td class="text-center p-1"><b>CONCEPTO</b></td>
                                        <td class="text-center p-1"><b>PROYECTO</b></td>
                                        <td class="text-center p-1"><b>MONTO</b></td>
                                        <td class="text-center p-1"><b>ESTADO</b></td>
                                        <td class="text-center p-1"><b>COPIA</b></td>
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
                "ajax":"{{ route('comprobantes.indexAjax') }}",
                "columns": [
                    {data: 'comprobante_id', name:'a.id', class:'text-center p-1 font-verdana'},
                    {data: 'fecha_comprobante', name:'fecha_comprobante', class:'text-center p-1 font-verdana'},
                    {data: 'nro_comprobante', name:'a.nro_comprobante', class:'text-center p-1 font-verdana'},
                    {data: 'concepto', name:'a.concepto', class:'text-left p-1 font-verdana'},
                    {data: 'abreviatura', name:'b.abreviatura', class:'text-center p-1 font-verdana'},
                    {data: 'monto', name:'monto', class:'text-right p-1 font-verdana'},
                    {data: 'status_search', name:'status_search', class:'text-center p-1 font-verdana',render: function(data, type, row){
                        if(row.status_search === 'BORRADOR'){
                            return '<span class="btn btn-xs btn-secondary font-verdana-sm"><b>BORRADOR</b></span>';
                        }else if(row.status_search == 'APROBADO'){
                            return '<span class="btn btn-xs btn-success font-verdana-sm"><b>APROBADO</b></span>';
                        }else{
                            return '<span class="btn btn-xs btn-danger font-verdana-sm"><b>ANULADO</b></span>';       
                        }
                    }
                },
                    {data: 'copia', name:'a.copia', class:'text-center p-1 font-verdana',render: function(data, type, row){
                        if(row.copia === '1'){
                            return '<i class="fas fa-check"></i>';
                        }else{
                            return '<i class="fas fa-close"></i>';
                        }
                    }
                },
                    {data: 'btnActions', class:'text-justify p-1'}
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