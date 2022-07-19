{{--<div class="form-group row">
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
<hr>--}}
<div class="form-group row">
    @if ($balance_apertura->estado == 1)
        <div class="col-md-5">
            <input type="hidden" name="comprobante_id" value="{{$balance_apertura->comprobante_fiscal_id}}">
            {{ Form::label('plancuenta','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::select('plancuenta', $plan_cuentas, null, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
            {!! $errors->first('plancuenta','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-4">
            {{ Form::label('auxiliar','Auxiliar',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::select('auxiliar', $auxiliares, null, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
            {!! $errors->first('auxiliar','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-3">
            {{ Form::label('centro','Centro',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::select('centro', $centros, null, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
            {!! $errors->first('centro','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-2">
            {{ Form::label('debe','Debe',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::text('debe', null, ['class' => 'form-control form-control-sm font-verdana','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
            {!! $errors->first('debe','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-2">
            {{ Form::label('haber','Haber',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::text('haber', null, ['class' => 'form-control form-control-sm font-verdana','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
            {!! $errors->first('haber','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-6">
            {{ Form::label('concepto','Concepto',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::text('concepto', 'BALANCE DE APERTURA', ['class' => 'form-control form-control-sm font-verdana','autocomplete'=>'off']) !!}
            {!! $errors->first('concepto','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
        <div class="col-md-2 text-right">
            <br>
            @if ($balance_apertura->estado == 1)
                <button type="submit" class="btn btn-primary font-verdana-bg">
                    <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Insertar&nbsp;
                </button>
            @endif
        </div>
    @endif
    <div class="col-md-2">
        {{ Form::label('total_debe','Total debe',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('total_debe', $total_debe, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
    </div>
    <div class="col-md-2">
        {{ Form::label('total_haber','Total haber',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('total_haber', $total_haber, ['readonly' => true,'class' => 'form-control form-control-sm font-verdana','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
    </div>
    <div class="col-md-3">
        <br>
        <a href="{{route('balanceapertura.index',$balance_apertura->proyecto_id)}}" class="btn btn-primary font-verdana-bg">
            <i class="fas fa-angle-double-left"></i>
        </a>
        <a href="{{route('comprobantes.fiscales.pdf', $comprobante->comprobante_id)}}" class="btn btn-warning font-verdana-bg" target="_blank">
            <i class="fas fa-print" aria-hidden="true"></i>
        </a>
    </div>
    @if ($balance_apertura->estado == 1)
        <div class="col-md-2 text-right">
            <br>
            <a href="{{route('balanceaperturaf.aprobar',$balance_apertura->id)}}" class="btn btn-success font-verdana-bg">
                <i class="fas fa-check-circle"></i>&nbsp;Aprobar
            </a>
        </div>
    @endif
    @if ($balance_apertura->estado == 2)
        <div class="col-md-2 text-right">
            <br>
            {!! Form::text('Estado','APROBADO', ['readonly' => true,'class' => 'form-control form-control-sm text-center font-verdana-bg bg-success']) !!}
        </div>
    @endif
</div>
<div class="form-group row">
    <div class="col-md-12">
        <center>
            <div class="table-responsive table-striped">
                <table id="tablaAjax" class="display responsive" style="width:100%">
                    <thead>
                        <tr class="font-verdana">
                            <td class="text-center p-1"><b>ID</b></td>
                            <td class="text-center p-1"><b>CODIGO</b></td>
                            <td class="text-center p-1"><b>CUENTA</b></td>
                            <td class="text-center p-1"><b>AUXILIAR</b></td>
                            <td class="text-center p-1"><b>CENTRO</b></td>
                            <td class="text-center p-1"><b>GLOSA</b></td>
                            <td class="text-center p-1"><b>DEBE</b></td>
                            <td class="text-center p-1"><b>HABER</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comprobante_detalle as $datos)
                            <tr class="font-verdana">
                                <td class="text-center p-1">{{$datos->comprobante_detalle_id}}</td>
                                <td class="text-justify p-1">{{$datos->codigo}}</td>
                                <td class="text-justify p-1">{{$datos->cuenta}}</td>
                                <td class="text-justify p-1">{{$datos->auxiliar}}</td>
                                <td class="text-center p-1">{{$datos->centro}}</td>
                                <td class="text-justify p-1">{{strtoupper($datos->glosa)}}</td>
                                <td class="text-right p-1">{{number_format($datos->debe,2,'.',',')}}</td>
                                <td class="text-right p-1">{{number_format($datos->haber,2,'.',',')}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </center>
    </div>
</div>