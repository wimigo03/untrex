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
                        {!! Form::select('proyecto_id',$proyectos,$proyecto_id, ['disabled' => true,'placeholder'=>'--Seleccionar--','class' => 'form-control form-control-sm bg-warning text-center', 'id' => 'proyecto_id']) !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class=" row">
                    <div class="col-md-10">
                        <strong>DEL </strong>{{\Carbon\Carbon::parse($start_date)->format('d/m/Y')}} <strong>AL: </strong>{{\Carbon\Carbon::parse($end_date)->format('d/m/Y')}}
                    </div>
                    <div class="col-md-1">
                        {!! Form::open(['route'=>'estadoresultadof.pdf','target' => '_blank']) !!}
                            <input type="hidden" name="status" value="{{$status_text}}">
                            <input type="hidden" name="start_date" value="{{$start_date}}">
                            <input type="hidden" name="end_date" value="{{$end_date}}">
                            <input type="hidden" name="proyecto_id" value="{{$proyecto_id}}">
                            <button class="btn btn-block btn-danger font-verdana-bg">
                                &nbsp;<i class="fas fa-lg fa-file-pdf"></i>&nbsp;
                            </button>
                        {!! Form::close()!!}
                    </div>
                    <div class="col-md-1">
                        {!! Form::open(['route'=>'estadoresultadof.excel']) !!}
                            <input type="hidden" name="status" value="{{$status_text}}">
                            <input type="hidden" name="start_date" value="{{$start_date}}">
                            <input type="hidden" name="end_date" value="{{$end_date}}">
                            <input type="hidden" name="proyecto_id" value="{{$proyecto_id}}">
                            <button class="btn btn-block btn-success font-verdana-bg">
                                &nbsp;<i class="fas fa-lg fa-file-excel"></i>&nbsp;
                            </button>
                        {!! Form::close()!!}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="height:500px;overflow-y: scroll;">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-bordered table-striped table-hover">
                                            <thead>
                                                <tr class="font-verdana-bg">
                                                    <td><b>CODIGO</b></td>
                                                    <td><b>CUENTA</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ingresos as $ing)
                                                    @php
                                                        $nroPuntos = 1;
                                                        for ($i=0; $i < strlen($ing->codigo); $i++) { 
                                                            if($ing->codigo[$i] == '.'){
                                                                $nroPuntos++;
                                                            }
                                                        }
                                                        $nroColumna = $nroMaxColumna - $nroPuntos;
                                                    @endphp
                                                    @if (isset($totales[$ing->id]) && $totales[$ing->id] != 0)
                                                        <tr class="font-verdana">
                                                            <td class="text-justify p-1">{{ $ing->codigo }}</td>
                                                            <td class="text-justify p-1">{{ $ing->nombre  }}</td>
                                                            @for ($i = 0; $i < $nroColumna; $i++)
                                                                <td></td>
                                                            @endfor
                                                            <td class="text-right p-1">
                                                                @if (isset($totales[$ing->id]))
                                                                    {{number_format($totales[$ing->id],2,'.',',')}}
                                                                @endif
                                                            </td>
                                                            @php
                                                                $nroColumna = $nroMaxColumna - $nroColumna -1;
                                                            @endphp
                                                            @for ($i = 0; $i < $nroColumna; $i++)
                                                                <td></td>
                                                            @endfor
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                @foreach ($costos as $costo)
                                                    @php
                                                        $nroPuntos = 1;
                                                        for ($i=0; $i < strlen($costo->codigo); $i++) { 
                                                            if($costo->codigo[$i] == '.'){
                                                                $nroPuntos++;
                                                            }
                                                        }
                                                        $nroColumna = $nroMaxColumna - $nroPuntos;
                                                    @endphp
                                                    @if (isset($totales[$costo->id]) && $totales[$costo->id] != 0)
                                                        <tr class="font-verdana">
                                                            <td class="text-justify p-1">{{ $costo->codigo }}</td>
                                                            <td class="text-justify p-1">{{ $costo->nombre  }}</td>
                                                            @for ($i = 0; $i < $nroColumna; $i++)
                                                                <td></td>
                                                            @endfor
                                                            <td class="text-right p-1">
                                                                @if (isset($totales[$costo->id]))
                                                                    {{number_format($totales[$costo->id],2,'.',',')}}
                                                                @endif
                                                            </td>
                                                            @php
                                                                $nroColumna = $nroMaxColumna - $nroColumna - 1;
                                                            @endphp
                                                            @for ($i = 0; $i < $nroColumna; $i++)
                                                                <td></td>
                                                            @endfor
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                @foreach ($gastos as $gasto)
                                                    @php
                                                        $nroPuntos = 1;
                                                        for ($i=0; $i < strlen($gasto->codigo); $i++) { 
                                                            if($gasto->codigo[$i] == '.'){
                                                                $nroPuntos++;
                                                            }
                                                        }
                                                        $nroColumna = $nroMaxColumna - $nroPuntos;
                                                    @endphp
                                                    @if (isset($totales[$gasto->id]) && $totales[$gasto->id] != 0)
                                                        <tr class="font-verdana">
                                                            <td class="text-justify p-1">{{ $gasto->codigo }}</td>
                                                            <td class="text-justify p-1">{{ $gasto->nombre  }}</td>
                                                            @for ($i = 0; $i < $nroColumna; $i++)
                                                                <td></td>
                                                            @endfor
                                                            <td class="text-right p-1">
                                                                @if (isset($totales[$gasto->id]))
                                                                    {{number_format($totales[$gasto->id],2,'.',',')}}
                                                                @endif
                                                            </td>
                                                            @php
                                                                $nroColumna = $nroMaxColumna - $nroColumna - 1;
                                                            @endphp
                                                            @for ($i = 0; $i < $nroColumna; $i++)
                                                                <td></td>
                                                            @endfor
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot class="font-verdana">
                                                <td><strong>TOTAL:</strong></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right p-1"><strong>{{number_format($total,2,'.',',')}}</strong></td>
                                            </tfoot>
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
        
    </script>
@stop
