<div class="form-group row font-verdana-bg">
    <div class="col-md-4">
        {{Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proyecto',$proyectos,old('proyecto'), ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2','id'=>'proyecto_id']) !!}
        {!! $errors->first('proyecto','<span class="invalid-feedback d-block">Proyecto no seleccionado.</span>') !!}
        <input type="hidden" name="proyecto_old" value="{{old('proyecto')}}" id="proyecto_old">
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha Inicial','Fecha Inicial',['class' => 'd-inline'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message_inicial" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha_inicial',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha_inicial','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countCharsInicial(this);'])}}
        {!! $errors->first('fecha_inicial','<span class="invalid-feedback d-block">Fecha inicial no seleccionada.</span>') !!}
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha Final','Fecha Final',['class' => 'd-inline'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message_final" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha_final',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha_final','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countCharsFinal(this);'])}}
        {!! $errors->first('fecha_final','<span class="invalid-feedback d-block">Fecha final no seleccionada.</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('Estado','Estado',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('estado', array('A'=>'APROBADO','B'=>'BORRADOR','A_B'=>'TODO'), null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
        {!! $errors->first('estado','<span class="invalid-feedback d-block">Tipo no seleccionado.</span>') !!}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-6">
        {{Form::label('plancuenta_inicial_id','Desde',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plancuenta_inicial_id',isset($plancuenta)?$plancuenta:array(''=>'--Seleccionar--'),null, ['disabled' => 'true','placeholder'=>'--Plan cuenta inicial--','class' => 'form-control form-control-sm select2','id'=>'plancuenta_inicial_id']) !!}
        {!! $errors->first('plancuenta_inicial_id','<span class="invalid-feedback d-block">Cuenta no seleccionado.</span>') !!}
    </div>
    <div class="col-md-6">
        {{Form::label('plancuenta_final_id','Hasta',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plancuenta_final_id',isset($plancuenta)?$plancuenta:array(''=>'--Seleccionar--'),null, ['disabled' => 'true','placeholder'=>'--Plan cuenta final--','class' => 'form-control form-control-sm select2','id'=>'plancuenta_final_id']) !!}
        {!! $errors->first('plancuenta_final_id','<span class="invalid-feedback d-block">Cuenta no seleccionado.</span>') !!}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-12 text-right"><br>
        <button type="submit" class="btn btn-primary font-verdana-bg" onclick="hide();">
            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Procesar&nbsp;
        </button>
        <i class="fa fa-spinner custom-spinner fa-spin fa-2x fa-fw spinner-btn-hide" style="display: none;"></i>
    </div>
</div>