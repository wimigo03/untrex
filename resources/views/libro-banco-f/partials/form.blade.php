<div class="form-group row font-verdana-bg">
    <div class="col-md-6">
        {{Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proyecto',$proyectos,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2','id'=>'proyecto_id']) !!}
        {!! $errors->first('proyecto','<span class="invalid-feedback d-block">Proyecto no seleccionado.</span>') !!}
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha_inicial','Fecha Inicial',['class' => 'd-inline'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message_inicial" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha_inicial',null,['placeholder'=>'--Desde--','class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha_inicial','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countCharsInicial(this);'])}}
        {!! $errors->first('fecha_inicial','<span class="invalid-feedback d-block">Fecha inicial no seleccionada.</span>') !!}
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha_final','Fecha Final',['class' => 'd-inline'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message_final" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha_final',null,['placeholder'=>'--Hasta--','class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha_final','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countCharsFinal(this);'])}}
        {!! $errors->first('fecha_final','<span class="invalid-feedback d-block">Fecha final no seleccionada.</span>') !!}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-2">
        {{Form::label('tipo','Tipo',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('tipo',array('Todo'=>'Todo','Cheque'=>'Cheque','Transferencia'=>'Transferencia'),null, ['class' => 'form-control form-control-sm','id'=>'tipo']) !!}
        {!! $errors->first('tipo','<span class="invalid-feedback d-block">Tipo no seleccionado.</span>') !!}
    </div>
    <div class="col-md-2 cheque">
        {{Form::label('Nro_inicial','Nro. Inicial',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('nro_inicial',null, ['placeholder'=>'--Desde--','class' => 'form-control form-control-sm font-verdana-bg','id'=>'nro_inicial','onkeypress' => 'return valideKey(event);']) !!}
        {!! $errors->first('nro_inicial','<span class="invalid-feedback d-block">Campo obligatorio.</span>') !!}
    </div>
    <div class="col-md-2 cheque">
        {{Form::label('Nro_final','Nro. Final',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('nro_final',null, ['placeholder'=>'--Hasta--','class' => 'form-control form-control-sm font-verdana-bg','id'=>'nro_final','onkeypress' => 'return valideKey(event);']) !!}
        {!! $errors->first('nro_final','<span class="invalid-feedback d-block">Campo obligatorio.</span>') !!}
    </div>
    <div class="col-md-6">
        {{Form::label('plancuenta_id','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plancuenta_id',isset($plancuenta)?$plancuenta:array(''=>'--Seleccionar--'),null, ['disabled' => 'true','placeholder'=>'--Plan cuenta--','class' => 'form-control form-control-sm select2','id'=>'plancuenta_id']) !!}
        {!! $errors->first('plancuenta_id','<span class="invalid-feedback d-block">Cuenta no seleccionado.</span>') !!}
    </div>
</div>