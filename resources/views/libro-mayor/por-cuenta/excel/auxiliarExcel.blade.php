<table>
    <tr>
        <td colspan="9" align="center">
            <h4><b>_*LIBRO MAYOR POR CUENTA - AUXILIAR*_</b></h4>
            <h4><b>{{strtoupper($proyecto->nombre)}}</b></h4>
        </td>
    </tr>
    <tr>
        <td colspan="9" align="left">
            <b>CUENTA:&nbsp;</b>{{$plancuenta->nombre}}
        </td>
    </tr>
    <tr>
        <td colspan="9" align="left">
            <b>DESDE:&nbsp;</b>{{ \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="9" align="left">
            <b>HASTA:&nbsp;</b>{{ \Carbon\Carbon::parse($fecha_final)->format('d/m/Y') }}
        </td>
    </tr>
</table>
<table>
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
                                ->where('a.fecha','<=',$fecha_inicial)
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
        <tr>
            <td colspan="9" align="center"><b>{{$datos->auxiliar == null ? 'SIN AUXILIAR' : $datos->auxiliar}}</b></td>
        </tr>
        <tr>
            <td colspan="9" align="center">
                SALDO INICIAL:&nbsp;Bs. {{$saldo}}
                &nbsp;|&nbsp;
                SALDO FINAL:&nbsp;Bs. {{$saldo_final}}
                &nbsp;|&nbsp;
                TOTAL DEBE:&nbsp;Bs. {{$total_debe}}
                &nbsp;|&nbsp;
                TOTAL HABER:&nbsp;Bs. {{$total_haber}}				
            </td>
        </tr>
        <tr>
            <th align="center"><b>FECHA</b></th>
            <th colspan="2" align="center"><b>COMPROBANTE</b></th>
            <th align="center"><b>CENTRO</b></th>
            <th align="center"><b>CHEQUE</b></th>
            <th align="center"><b>GLOSA</b></th>
            <th align="center"><b>DEBE</b></th>
            <th align="center"><b>HABER</b></th>
            <th align="center"><b>SALDO</b></th>
        </tr>
            @foreach ($comprobantes_detalle as $data)
                <tr>
                    <td align="center">{{\Carbon\Carbon::parse($data->fecha)->format('d/m/Y')}}</td>
                    @php
                        if($data->status == 0){
                            $estado = "B";
                        }else{
                            $estado = "A";
                        }
                    @endphp
                    <td align="center">{{$data->nro_comprobante}}</td>
                    <td align="center"><b>{{$estado}}</b></td>
                    <td align="center">{{$data->centro}}</td>
                    <td align="center">{{strtoupper($data->cheque_nro)}}</td>
                    <td align="justify">{{strtoupper($data->glosa)}}</td>
                    <td align="right">{{$data->debe}}</td>
                    <td align="right">{{$data->haber}}</td>
                    @php
                        if($data->debe > 0){
                            $saldo += $data->debe;
                        }else{
                            $saldo -= $data->haber;
                        }
                    @endphp
                    <td align="right">{{$saldo}}</td>
                </tr>
            @endforeach
    @endforeach
</table>