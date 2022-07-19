@extends('adminlte::page')
@section('title', 'Balance General')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-title"><b>BALANCE GENERAL</b></div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('proyecto_id',$proyectos,$proyecto_id, ['disabled' => true,'placeholder'=>'--Seleccionar--','class' => 'form-control form-control-sm bg-gray text-center', 'id' => 'proyecto_id']) !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class=" row">
                    <div class="col-md-10">
                        <strong>DEL </strong>{{\Carbon\Carbon::parse($start_date)->format('d/m/Y')}} <strong>AL: </strong>{{\Carbon\Carbon::parse($end_date)->format('d/m/Y')}}
                    </div>
                    <div class="col-md-2">
                        {!! Form::open(['route'=>'balancegeneral.pdf','target' => '_blank']) !!}
                            <input type="hidden" name="status" value="{{$status_text}}">
                            <input type="hidden" name="start_date" value="{{$start_date}}">
                            <input type="hidden" name="end_date" value="{{$end_date}}">
                            <input type="hidden" name="proyecto_id" value="{{$proyecto_id}}">
                            <button class="btn btn-block btn-danger font-verdana-bg">
                                <span><i class="fas fa-file-pdf"></i>&nbsp;Exportar a Pdf</span>
                            </button>
                        {!! Form::close()!!}
                    </div>
                    {{--<div class="col-md-1">
                        {!! Form::open(['route'=>'estadoresultado.excel']) !!}
                            <input type="hidden" name="year" value="{{$anho}}">
                            <input type="hidden" name="proyecto_id" value="{{$proyecto_id}}">
                            <input type="hidden" name="status" value="{{$status_text}}">
                            <input type="hidden" name="start_date" value="{{$start_date}}">
                            <input type="hidden" name="end_date" value="{{$end_date}}">
                            <button class="btn btn-block btn-success font-verdana-bg">
                                <span><i class="fas fa-file-excel"></i>&nbsp;Excel</span>
                            </button>
                        {!! Form::close()!!}
                    </div>--}}
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
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($activos as $activo)
                                                    @php
                                                        $nroPuntos = 1;
                                                        for ($i=0; $i < strlen($activo->codigo); $i++) { 
                                                            if($activo->codigo[$i] == '.'){
                                                                $nroPuntos++;
                                                            }
                                                        }
                                                        $nroColumna = $nroMaxColumna - $nroPuntos;
                                                    @endphp
                                                    <tr class="font-verdana">
                                                        <td class="text-justify p-1">{{ $activo->codigo }}</td>
                                                        <td class="text-justify p-1">{{ $activo->nombre  }}</td>
                                                        @for ($i = 0; $i < $nroColumna; $i++)
                                                            <td></td>
                                                        @endfor
                                                        <td class="text-right p-1">
                                                            @if (isset($totales[$activo->id]))
                                                                {{number_format($totales[$activo->id],2,'.',',')}}
                                                            @endif
                                                        </td>
                                                        @php
                                                            $nroColumna = $nroMaxColumna - $nroColumna -1;
                                                        @endphp
                                                        @for ($i = 0; $i < $nroColumna; $i++)
                                                            <td></td>
                                                        @endfor
                                                    </tr>
                                                @endforeach
                                                @foreach ($pasivos as $pasivo)
                                                    @php
                                                        $nroPuntos = 1;
                                                        for ($i=0; $i < strlen($pasivo->codigo); $i++) { 
                                                            if($pasivo->codigo[$i] == '.'){
                                                                $nroPuntos++;
                                                            }
                                                        }
                                                        $nroColumna = $nroMaxColumna - $nroPuntos;
                                                    @endphp
                                                    <tr class="font-verdana">
                                                        <td class="text-justify p-1">{{ $pasivo->codigo }}</td>
                                                        <td class="text-justify p-1">{{ $pasivo->nombre  }}</td>
                                                        @for ($i = 0; $i < $nroColumna; $i++)
                                                            <td></td>
                                                        @endfor
                                                        <td class="text-right p-1">
                                                            @if (isset($totales[$pasivo->id]))
                                                                {{number_format($totales[$pasivo->id],2,'.',',')}}
                                                            @endif
                                                        </td>
                                                        @php
                                                            $nroColumna = $nroMaxColumna - $nroColumna - 1;
                                                        @endphp
                                                        @for ($i = 0; $i < $nroColumna; $i++)
                                                            <td></td>
                                                        @endfor
                                                    </tr>
                                                @endforeach
                                                @foreach ($patrimonios as $patrimonio)
                                                    @php
                                                        $nroPuntos = 1;
                                                        for ($i=0; $i < strlen($patrimonio->codigo); $i++) { 
                                                            if($patrimonio->codigo[$i] == '.'){
                                                                $nroPuntos++;
                                                            }
                                                        }
                                                        $nroColumna = $nroMaxColumna - $nroPuntos;
                                                    @endphp
                                                    <tr class="font-verdana">
                                                        <td class="text-justify p-1">{{ $patrimonio->codigo }}</td>
                                                        <td class="text-justify p-1">{{ $patrimonio->nombre  }}</td>
                                                        @for ($i = 0; $i < $nroColumna; $i++)
                                                            <td></td>
                                                        @endfor
                                                        <td class="text-right p-1">
                                                            @if (isset($totales[$patrimonio->id]))
                                                                {{number_format($totales[$patrimonio->id],2,'.',',')}}
                                                            @endif
                                                        </td>
                                                        @php
                                                            $nroColumna = $nroMaxColumna - $nroColumna - 1;
                                                        @endphp
                                                        @for ($i = 0; $i < $nroColumna; $i++)
                                                            <td></td>
                                                        @endfor
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="font-verdana">
                                                <td class="text-left p-1"><strong>TOTAL:</strong></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right p-1"><strong>CAPITAL&nbsp;<i class="fas fa-arrow-alt-circle-right"></i></strong></td>
                                                <td class="text-right p-1"><strong>{{number_format($capital,2,'.',',')}}</strong></td>
                                                <td class="text-left p-1"><strong>{{number_format($activo_pasivo,2,'.',',')}}</strong></td>
                                                <td class="text-left p-1"><strong><i class="fas fa-arrow-alt-circle-left"></i>&nbsp;ACTIVO + PASIVO</strong></td>
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
