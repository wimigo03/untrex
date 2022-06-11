@extends('adminlte::page')
@section('title', 'Plan de cuentas')
{{--@section('content_header')
    <h1>Dashboardd</h1>
@stop--}}
@section('content')
<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('/') }}"><i class="fas fa-home fa-xs"></i> <font size="2px">Inicio</font></a> /
        <a href="#"><i class="fas fa-sitemap fa-xs"></i><font size="2px"> Estructura</font></a>
    </div>
</div>
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>PLAN DE CUENTAS</b>
                    {{--<a href="{{ route('personal.index') }}" class="btn btn-sm pull-right text-white"><i class="fa fa-reply" aria-hidden="true"></i></a>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        {{Form::label('proyecto','Seleccionar proyecto',['class' => 'd-inline font-verdana-bg'])}}
                        <form method="GET" action="{{ route('plandecuentas.search') }}" id="form-plan-cuentas-search">
                            <select name="proyecto_id" id="proyecto" class="form-control form-control-sm mb-1">
                                <option value="">--Proyecto--</option>
                                    @foreach ($proyectos as $datos)
                                        <option value="{{$datos->id}}" @if((isset($proyecto_id) && $proyecto_id) == $datos->id)	selected @endif>{{$datos->nombre}}</option>
                                    @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-5">
                                    @if ($proyecto_id != null)
                                        {!! Form::model(Request::all(),['route'=> ['plandecuentasauxiliares.index']]) !!}
                                            {{Form::hidden('proyecto_id',$proyecto_id,['id' => 'proyecto_id'])}}
                                            <br>
                                            <button type="submit" class="btn btn-sm btn-block btn-secondary">
                                                <i class="fas fa-list"></i>&nbsp;Cuentas auxiliares
                                            </button>
                                        {!! Form::close()!!}
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    {!! Form::model(Request::all(),['route'=> ['plandecuentas.editar_dependiente']]) !!}
                                        {{Form::hidden('editar_plan_cuenta_id',null,['id' => 'js_editar_plan_cuenta_id'])}}
                                        <br>
                                        <button type="submit" class="btn btn-sm btn-block btn-primary btn-accion-seleccionado" style="display: none">
                                            <i class="fas fa-edit"></i>&nbsp;Modificar
                                        </button>
                                    {!! Form::close()!!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::model(Request::all(),['route'=> ['plandecuentas.create_dependiente']]) !!}
                                        {{Form::hidden('proyecto_id',$proyecto_id)}}
                                        {{Form::hidden('crear_plan_cuenta_id',null,['id' => 'js_crear_plan_cuenta_id'])}}
                                        <br>
                                        <button type="submit" class="btn btn-sm btn-block btn-success btn-accion-seleccionado" style="display: none">
                                            <i class="fas fa-plus"></i>&nbsp;Crear Sub cuenta
                                        </button>
                                    {!! Form::close()!!}  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($proyecto_id != null)
                    @include('plandecuentas.partials.encabezado')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 font-verdana-bg">
                                    <div style="height:400px;overflow-y: scroll;">
                                        {!! $html !!}
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
    {{--<link rel="stylesheet" href="/css/treeview_c.css">--}}
    <link rel="stylesheet" href="/css/tree/jquery-treeview.css">
    <link rel="stylesheet" href="/css/style.css">
@stop

@section('js')
    <script type="text/javascript" src="/js/tree/jquery-treeview.js"></script>
    {{--<script type="text/javascript" src="/js/plan_cuentas/index.js"></script>--}}
    {{--<script type="text/javascript" src="/js/plan_cuentas/treeview_c.js"></script>
    <script type="text/javascript" src="/js/plan_cuentas/jquery.min.js"></script>--}}
    {{--<script type="text/javascript" src="/js/plan_cuentas/bootstrap.bundle.min.js"></script>--}}
    <script>
        $('#proyecto').change(function() {
            $('#form-plan-cuentas-search').submit();
        });
        
        function getDatos(plan_cuenta_id){
			var url = "{{ route('plandecuentas.get-selected-data',':id') }}";
			url = url.replace(':id',plan_cuenta_id)
			$.ajax({  
				type: 'GET', 
				url: url,
				data: {   
					id: plan_cuenta_id
				}, 
				success: function(data){
					if(data['status_code'] == 200){
						cargarDatos(data['data']);
					}else{
						toastr.error('Ocurrio un problema al obtener los datos, por favor intente nuevamente.', 'Ups!');
						$("#card_datos").addClass('bg-secondary');
						$(".vista-datos").show();
						$(".vista-loading").hide();
					}
				},  
				error: function(xhr){
					console.log(xhr.responseText);
				}
			}); 
		}

        function cargarDatos(datos){
            console.log(datos);
			//$('#padre_id').val(datos.padre_id).trigger('change');
			$('#js_parent_id').val(datos.parent_id);
            $('#js_editar_plan_cuenta_id').val(datos.id);
            $('#js_crear_plan_cuenta_id').val(datos.id);
            $('#js_auxiliar_plan_cuenta_id').val(datos.id);
			$('#js_codigo').val(datos.codigo);
			$('#js_nombre').val(datos.nombre);
			$('#js_descripcion').val(datos.descripcion);
			$('#js_moneda').val(datos.moneda);
            if(datos.cuenta_detalle == '1'){
                var cuenta_detalle = 'SI';
            }else{
                var cuenta_detalle = 'NO';
            }
			$("#js_cuenta_detalle").val(cuenta_detalle);
            if(datos.cheque == '1'){
                var cheque = 'SI';
            }else{
                var cheque = 'NO';
            }
			$("#js_cheque").val(cheque);
            if(datos.auxiliar == '1'){
                var auxiliar = 'SI';
            }else{
                var auxiliar = 'NO';
            }
			$("#js_auxiliar").val(auxiliar);
			/*if(datos.cuenta_detalle == '1'){
				$("#cuenta_detalle_text > i").removeClass('fa fa-times');
				$("#cuenta_detalle_text > i").addClass('fa fa-check');
			}else{
				$("#cuenta_detalle_text > i").removeClass('fa fa-check');
				$("#cuenta_detalle_text > i").addClass('fa fa-times');
			}
			if(datos.auxiliar == '1'){
				$("#auxiliar_text > i").removeClass('fa fa-times');
				$("#auxiliar_text > i").addClass('fa fa-check');
			}else{
				$("#auxiliar_text > i").removeClass('fa fa-check');
				$("#auxiliar_text > i").addClass('fa fa-times');
			}
			if(datos.cheque == '1'){
				$("#cheque_text > i").removeClass('fa fa-times');
				$("#cheque_text > i").addClass('fa fa-check');
			}else{
				$("#cheque_text > i").removeClass('fa fa-check');
				$("#cheque_text > i").addClass('fa fa-times');
			}*/

			$("#estado").val(datos.estado);
			$(".vista-datos").show();
			$(".vista-loading").hide();
			$(".btn").hide();
			if(datos.estado == 1){
				$(".btn-habilitar").hide();
				$(".btn-deshabilitar").show();
			}else{
				$(".btn-deshabilitar").hide();
				$(".btn-habilitar").show();
			}
			$(".btn-accion-seleccionado").show();
		}

        document.addEventListener('DOMContentLoaded',function () {
			@if($html != "")
				$("#treeview-plan-cuentas").treeview();
			@endif
			$("ul#treeview-plan-cuentas li span").click(function(){
				$(".vista-datos").hide();
				$(".vista-loading").show();
				var li = $(this).parents("li").eq(0);
				var plan_cuenta_id_selected = li.find("input.plan_cuenta_id").val();
				getDatos(plan_cuenta_id_selected);
			});
		});
    </script>
@stop