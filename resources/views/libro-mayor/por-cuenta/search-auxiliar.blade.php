@extends('adminlte::page')
@section('title', 'Plan de cuenta')
@section('content')
@include('components.flash_alerts')
<div class="form-group row">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-gradient-secondary text-white">
                <div class="card-title"><b>LIBRO MAYOR POR AUXILIAR - {{strtoupper($tipo)}} - {{strtoupper($proyecto->nombre)}}</b></div>
            </div>
            <div class="card-body">
                @include('libro-mayor.por-cuenta.partials.encabezado-auxiliar')
                @if ($auxiliares != null)
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="font-verdana">
                                        <td class="text-center p-1"><b>FECHA</b></td>
                                        <td width="15%" colspan="2" class="text-center p-1"><b>COMPROBANTE</b></td>
                                        <td class="text-center p-1"><b>CENTRO</b></td>
                                        <td class="text-center p-1"><b>CHEQUE</b></td>
                                        <td class="text-center p-1"><b>GLOSA</b></td>
                                        <td class="text-center p-1"><b>DEBE</b></td>
                                        <td class="text-center p-1"><b>HABER</b></td>
                                        <td class="text-center p-1"><b>SALDO</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($auxiliares as $datos)
                                        @php
                                            $sumarRestar = DB::table('comprobantes as a')
                                                                ->join('comprobantes_detalles as b','b.comprobante_id','a.id')
                                                                ->join('centros as c','c.id','b.centro_id')
                                                                ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                                                                ->where('a.proyecto_id',$proyecto->id)
                                                                ->where('b.plancuenta_id',$plancuenta->id)
                                                                ->where('b.plancuentaauxiliar_id',$datos->plancuentaauxiliar_id)
                                                                ->where('a.status','!=','2')
                                                                ->where('a.fecha','>=',$fecha_saldo_inicial)
                                                                ->where('a.fecha','<',$fecha_inicial)
                                                                ->where('b.deleted_at',null)
                                                                ->select('b.debe','b.haber')
                                                                ->orderBy('a.fecha','asc')
                                                                ->get();
                                            $saldo = 0;
                                            foreach($sumarRestar as $sumaResta){
                                                $saldo += $sumaResta->debe;
                                                $saldo -= $sumaResta->haber;
                                            }
                                            $comprobantes_detalle = DB::table('comprobantes as a')
                                                                        ->join('comprobantes_detalles as b','b.comprobante_id','a.id')
                                                                        ->join('centros as c','c.id','b.centro_id')
                                                                        ->leftjoin('plan_cuentas_auxiliares as d','d.id','b.plancuentaauxiliar_id')
                                                                        ->where('a.proyecto_id',$proyecto->id)
                                                                        ->where('b.plancuenta_id',$plancuenta->id)
                                                                        ->where('b.plancuentaauxiliar_id',$datos->plancuentaauxiliar_id)
                                                                        ->where('a.status','!=','2')
                                                                        ->where('a.fecha','>=',$fecha_inicial)
                                                                        ->where('a.fecha','<=',$fecha_final)
                                                                        ->where('b.deleted_at',null)
                                                                        ->select('a.id as comprobante_id','a.fecha','a.nro_comprobante','a.status','c.abreviatura as centro',DB::raw("if(isnull(d.nombre),'S/N',d.nombre) as auxiliar"),'b.cheque_nro','b.glosa','b.debe','b.haber')
                                                                        ->orderBy('a.fecha','asc')
                                                                        ->get();
                                            $saldo_final = $saldo;
                                            $total_debe = 0;
                                            $total_haber = 0;
                                            foreach ($comprobantes_detalle as $comp) {
                                                $saldo_final += $comp->debe;
                                                $saldo_final -= $comp->haber;
                                                $total_debe += $comp->debe;
                                                $total_haber += $comp->haber;
                                            }
                                        @endphp
                                        <tr class="font-verdana">
                                            <td colspan="9" class="bg bg-secondary text-center p-1"><b>{{$datos->auxiliar == null ? 'SIN AUXILIAR' : $datos->auxiliar}}</b></td>
                                        </tr>
                                        <tr class="font-verdana">
                                            <td colspan="9" class="bg bg-danger text-center p-1">
                                                SALDO INICIAL:&nbsp;Bs. {{number_format($saldo,2,'.',',')}}
                                                &nbsp;|&nbsp;
                                                SALDO FINAL:&nbsp;Bs. {{number_format($saldo_final,2,'.',',')}}
                                                &nbsp;|&nbsp;
                                                TOTAL DEBE:&nbsp;Bs. {{number_format($total_debe,2,'.',',')}}
                                                &nbsp;|&nbsp;
                                                TOTAL HABER:&nbsp;Bs. {{number_format($total_haber,2,'.',',')}}
                                            </td>
                                        </tr>
                                            @foreach ($comprobantes_detalle as $data)
                                                <tr class="font-verdana">
                                                    <td class="text-center p-1">{{\Carbon\Carbon::parse($data->fecha)->format('d/m/Y')}}</td>
                                                    @php
                                                        if($data->status == 0){
                                                            $estado = "P";
                                                            $color = "text-secondary";
                                                        }else{
                                                            $estado = "A";
                                                            $color = "text-success";
                                                        }
                                                    @endphp
                                                    <td class="text-center p-1">
                                                        <a href="{{ route('comprobantes.show',$data->comprobante_id) }}" target="_blank">
                                                            {{$data->nro_comprobante}}
                                                        </a>
                                                    </td>
                                                    <td class="text-center p-1 {{$color}}"><b>{{$estado}}</b></td>
                                                    <td class="text-center p-1">{{$data->centro}}</td>
                                                    <td class="text-center p-1">{{strtoupper($data->cheque_nro)}}</td>
                                                    <td class="text-justify p-1">{{strtoupper($data->glosa)}}</td>
                                                    <td class="text-right p-1">{{number_format($data->debe,2,'.',',')}}</td>
                                                    <td class="text-right p-1">{{number_format($data->haber,2,'.',',')}}</td>
                                                    @php
                                                        if($data->debe > 0){
                                                            $saldo += $data->debe;
                                                        }else{
                                                            $saldo -= $data->haber;
                                                        }
                                                    @endphp
                                                    <td class="text-right p-1">{{number_format($saldo,2,'.',',')}}</td>
                                                </tr>
                                            @endforeach
                                        {{--<tr class="font-verdana">
                                            <td colspan="6" class="bg bg-danger text-justify p-1">TOTAL AUXILIAR</td>
                                            <td class="bg bg-danger text-right p-1">{{$datos->total_auxiliar_debe == null ? '0' : number_format($datos->total_auxiliar_debe,2,'.',',')}}</td>
                                            <td class="bg bg-danger text-right p-1">{{$datos->total_auxiliar_haber == null ? '0' : number_format($datos->total_auxiliar_haber,2,'.',',')}}</td>
                                            <td class="bg bg-danger text-right p-1">{{number_format($saldo,2,'.',',')}}</td>
                                        </tr>--}}
                                    @endforeach
                                </tbody>
                            </table>
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
@stop

@section('js')
    <script>
     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        $('#plancuentaauxiliar_id').select2({placeholder: "--Buscar--"});   
    </script>
@stop