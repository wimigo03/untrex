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
        {{Form::label('Nro_cheque_inicial','Nro. Cheque Inicial',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('nro_cheque_inicial',null, ['placeholder'=>'--Desde--','class' => 'form-control form-control-sm font-verdana-bg','id'=>'nro_cheque_inicial']) !!}
        {!! $errors->first('nro_cheque_inicial','<span class="invalid-feedback d-block">Campo obligatorio.</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('Nro_cheque_final','Nro. Cheque Final',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('nro_cheque_final',null, ['placeholder'=>'--Hasta--','class' => 'form-control form-control-sm font-verdana-bg','id'=>'nro_cheque_final']) !!}
        {!! $errors->first('nro_cheque_final','<span class="invalid-feedback d-block">Campo obligatorio.</span>') !!}
    </div>
    <div class="col-md-8">
        {{Form::label('plancuenta_id','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plancuenta_id',isset($plancuenta)?$plancuenta:array(''=>'--Seleccionar--'),null, ['disabled' => 'true','placeholder'=>'--Plan cuenta--','class' => 'form-control form-control-sm select2','id'=>'plancuenta_id']) !!}
        {!! $errors->first('plancuenta_id','<span class="invalid-feedback d-block">Cuenta no seleccionado.</span>') !!}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-12 text-right"><br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Procesar&nbsp;
        </button>
    </div>
</div>