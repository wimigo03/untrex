@if (isset($consulta_comprobantes) && (count($consulta_comprobantes) > 0))
    <div class="form-group row">
        <div class="col-md-12 font-verdana-sm">
            <p class="text-muted">Mostrando 
                <strong>{{ $consulta_comprobantes->count() }}</strong> registros de 
                <strong>{{$consulta_comprobantes->total() }}</strong> totales
            </p>
            {{ $consulta_comprobantes->appends(Request::all())->links() }}
        </div>
        <div class="col-md-12">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="font-verdana">
                        <td class="text-justify p-1"><b>FECHA</b></td>
                        <td class="text-justify p-1"><b>COMPROBANTE</b></td>
                        <td class="text-justify p-1"><b>ESTADO</b></td>
                        <td class="text-justify p-1"><b>CUENTA</b></td>
                        <td class="text-right p-1"><b>DEBE</b></td>
                        <td class="text-right p-1"><b>HABER</b></td>
                        <td class="text-justify p-1"><b>GLOSA</b></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($consulta_comprobantes as $datos)
                        <tr class="font-verdana">
                            <td class="text-justify p-1">
                                {{$datos->comprobante != null ? \Carbon\Carbon::parse($datos->comprobante->fecha)->format('d/m/Y') : '#'}}
                            </td>
                            <td class="text-justify p-1">
                                <a href="{{ route('comprobantes.fiscales.show',$datos->comprobante_fiscal_id) }}" target="_blank">
                                    {{$datos->comprobante != null ? $datos->comprobante->nro_comprobante : '#'}}
                                </a>
                            </td>
                            <td class="text-justify p-1">
                                {{$datos->comprobante != null ? $datos->comprobante->estado_comp : '#'}}
                            </td>
                            <td class="text-justify p-1">
                                {{$datos->cuenta != null ? $datos->cuenta->codigo . ' - ' . $datos->cuenta->nombre : '#'}}
                            </td>
                            <td class="text-right p-1">
                                {{number_format($datos->debe,2,'.',',')}}
                            </td>
                            <td class="text-right p-1">
                                {{number_format($datos->haber,2,'.',',')}}
                            </td>
                            <td class="text-justify p-1">
                                {{strtoupper($datos->glosa)}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif