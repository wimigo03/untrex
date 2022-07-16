<div class="form-group row">
    <div class="col-md-2">
        {{ Form::label('Nro','Nro',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Nro',$comprobante->nro_comprobante, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-2">
        {{ Form::label('Moneda','Moneda',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Moneda',$comprobante->moneda, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-4">
        {{ Form::label('Creado por','Creado por',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Creado por',strtoupper($comprobante->creador), ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-2">
        {{ Form::label('Tipo','Tipo',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Tipo',$comprobante->tipo_comprobante, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-2">
        {{ Form::label('Estado','Estado',['class' => 'd-inline font-verdana-bg'])}}
        @if($comprobante->status == 0)
            {!! Form::text('Estado','PENDIENTE', ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg bg-secondary']) !!}
        @else
            @if($comprobante->status == 1)
                {!! Form::text('Estado','APROBADO', ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg bg-success']) !!}
            @else
                {!! Form::text('Estado','ANULADO', ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg bg-danger']) !!}
            @endif
        @endif
    </div>
    <div class="col-md-9">
        {{ Form::label('Concepto','Concepto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Concepto',$comprobante->concepto, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-3">
        {{ Form::label('Proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('proyecto',$comprobante->nombre, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-2">
        {{ Form::label('Fecha','Fecha',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Fecha',\Carbon\Carbon::parse($comprobante->fecha)->format('d/m/Y'), ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-4">
        {{ Form::label('Aprobado por','Aprobado por',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Aprobado por',strtoupper($comprobante->autorizado), ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-2">
        @php
            if($comprobante->copia == 1){
                $copia = 'SI';
            }else{
                $copia = 'NO';
            }
        @endphp
        {{ Form::label('Copia','Copia',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Copia',$copia, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-2">
        {{ Form::label('Monto','Monto Total',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('Monto',$comprobante->monto, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana-bg']) !!}
    </div>
    <div class="col-md-2 text-right">
        <br>
        <a href="{{route('balanceapertura.index',$balance_apertura->proyecto_id)}}" class="btn btn-sm btn-primary font-verdana-bg">
            <i class="fas fa-angle-double-left"></i>
        </a>
        <button type="submit" class="btn btn-sm btn-primary font-verdana-bg">
            <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Guardar&nbsp;
        </button>
        <a href="{{route('comprobantes.pdf', $comprobante->comprobante_id)}}" class="btn btn-sm btn-warning font-verdana-bg" target="_blank">
            <i class="fas fa-print" aria-hidden="true"></i>
        </a>
    </div>
</div>
<hr>
<div class="form-group row">
    <div class="col-md-12">
        <center>
            <div class="table-responsive table-striped">
                <table id="tablaAjax" class="display responsive" style="width:100%">
                    <thead>
                        <tr class="font-verdana">
                            <td class="text-center p-1"><b>CODIGO</b></td>
                            <td class="text-center p-1"><b>CUENTA</b></td>
                            <td width="25%" class="text-center p-1"><b>AUXILIAR</b></td>
                            <td width="10%" class="text-center p-1"><b>CENTRO</b></td>
                            <td width="25%" class="text-center p-1"><b>GLOSA</b></td>
                            <td width="8%" class="text-center p-1"><b>DEBE</b></td>
                            <td width="8%" class="text-center p-1"><b>HABER</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comprobante_detalle as $datos)
                            <tr class="font-verdana">
                                <td class="text-justify p-1">{{$datos->codigo}}</td>
                                <td class="text-justify p-1">{{$datos->cuenta}}</td>
                                <td class="text-justify p-1">
                                    <input type="hidden" name="comprobante_detalle_id[]" value="{{$datos->comprobante_detalle_id}}">
                                    {!! Form::select('auxiliar_id[]', $auxiliares, $datos->auxiliar, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
                                </td>
                                <td class="text-justify p-1">
                                    {!! Form::select('centro_id[]', $centros, $datos->centro, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
                                </td>
                                <td class="text-justify p-1">
                                    {!! Form::text('glosa[]', $datos->glosa, ['class' => 'form-control form-control-sm font-verdana','autocomplete'=>'off']) !!}
                                </td>
                                <td class="text-right p-1">
                                    {!! Form::text('debe[]', $datos->debe, ['class' => 'form-control form-control-sm font-verdana text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                </td>
                                <td class="text-right p-1">
                                    {!! Form::text('haber[]', $datos->haber, ['class' => 'form-control form-control-sm font-verdana-bg text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </center>
    </div>
</div>