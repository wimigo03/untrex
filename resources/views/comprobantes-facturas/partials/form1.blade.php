{{Form::hidden('comprobante_id',$comprobante->id)}}
{{Form::hidden('socio_id',$comprobante->socio_id)}}
<div class="form-group row">
    <div class="col-md-2">
        {{Form::label('numero','Nro Factura',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('numero',null,['class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('numero') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('numero','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('monto','Monto',['class' => 'd-inline font-verdana-bg'])}}
        <div class="input-group input-group-sm">                                
            {{Form::text('monto',0,['class'=>'form-control form-control-sm font-verdana-bg', 'oninput' => 'limitDecimalPlaces(event, 2)','onkeypress' => 'return isNumberKey(event)','onkeyup' => 'countCharsMonto(this);' . ( $errors->has('monto') ? ' is-invalid' : '' ),'autocomplete'=>'off','id'=>'monto'])}}
            <div class="input-group-append "><span class="input-group-text">BS</span></div>
            {!! $errors->first('monto','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
    </div>
    <div class="col-md-2">
        {{Form::label('cod_control','Codigo de control',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('cod_control',null,['class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('cod_control') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('cod_control','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('proveedor','Proveedor',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proveedor',$proveedores,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('proveedor') ? ' is-invalid' : '' ),'id'=>'proveedores']) !!}
        {!! $errors->first('proveedor','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('empresa','Empresa',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('empresa',$socio->empresa,['readonly' => true,'class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('empresa') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('empresa','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha','Fecha',['class' => 'd-inline font-verdana-bg'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countChars(this);'])}}
        {!! $errors->first('fecha','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('nit','Nit',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nit',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nit','data-language' => 'es'])}}
        {!! $errors->first('nit','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('Nro_de_autorizacion','Nro de autorizacion',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nro_autorizacion',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nro_autorizacion','data-language' => 'es'])}}
        {!! $errors->first('nro_autorizacion','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('razon_social','Razon Social',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('razon_social',null,['readonly' => true,'class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('razon_social') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('razon_social','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proyecto',$proyectos,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('proyectos') ? ' is-invalid' : '' ),'id'=>'proyectos']) !!}
        {!! $errors->first('proyecto','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('nro_dui','Nro Dui',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nro_dui',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nro_dui','data-language' => 'es'])}}
        {!! $errors->first('nro_dui','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('excento','Excento',['class' => 'd-inline font-verdana-bg'])}}
        <div class="input-group input-group-sm">                                
            {{Form::text('excento',0,['class'=>'form-control form-control-sm font-verdana-bg', 'oninput' => 'limitDecimalPlaces(event, 2)','onkeypress' => 'return isNumberKey(event)','onkeyup' => 'countCharsExcento(this);' . ( $errors->has('excento') ? ' is-invalid' : '' ),'autocomplete'=>'off','id'=>'excento'])}}
            <div class="input-group-append "><span class="input-group-text">BS</span></div>
            {!! $errors->first('excento','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
    </div>
    <div class="col-md-2">
        {{Form::label('descuento','Descuento',['class' => 'd-inline font-verdana-bg'])}}
        <div class="input-group input-group-sm">                                
            {{Form::text('descuento',0,['class'=>'form-control form-control-sm font-verdana-bg', 'oninput' => 'limitDecimalPlaces(event, 2)','onkeypress' => 'return isNumberKey(event)','onkeyup' => 'countCharsDescuento(this);' . ( $errors->has('excento') ? ' is-invalid' : '' ),'autocomplete'=>'off','id'=>'descuento'])}}
            <div class="input-group-append "><span class="input-group-text">BS</span></div>
            {!! $errors->first('decuento','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
    </div>
    <div class="col-md-2">
        {{Form::label('tipo','Tipo:',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('tipo',array(''=>'--Seleccionar--','COMPRA'=>'COMPRA','VENTA'=>'VENTA'),null, ['class' => 'form-control form-control-sm select2'. ( $errors->has('tipo') ? ' is-invalid' : '' ),'id'=>'tipo']) !!}
        {!! $errors->first('tipo','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        <div id="estado">
            {{Form::label('estado','Estado:',['class' => 'd-inline font-verdana-bg'])}}
            {!! Form::select('estado',array(''=>'--Seleccionar--','V'=>'VALIDO','A'=>'ANULADO','N'=>'NULO'),null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('estado') ? ' is-invalid' : '' ),'id'=>'estado']) !!}
            {!! $errors->first('estado','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
    </div>
    <div class="col-md-2">
        {{Form::label('centro','Centro',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('centro',$centros,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('centros') ? ' is-invalid' : '' ),'id'=>'centros']) !!}
        {!! $errors->first('centro','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-7">
        {{Form::label('plan_cuenta_debe','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plan_cuenta_debe',$plan_cuentas_1,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuenta_debe') ? ' is-invalid' : '' ),'id'=>'plan_cuenta_debe']) !!}
        {!! $errors->first('plan_cuenta_debe','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-5">
        {{Form::label('plan_cuenta_auxiliar','Auxiliar',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plan_cuenta_auxiliar',$plan_cuentas_auxiliares,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuentas_auxiliares') ? ' is-invalid' : '' ),'id'=>'plan_cuentas_auxiliares']) !!}
        {!! $errors->first('plan_cuenta_auxiliar','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-9">
        {{Form::label('glosa','Glosa',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('glosa',null,['class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('glosa') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('glosa','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3 text-right"><br>
        <a href="#" class="btn btn-warning font-verdana-bg" id="insertar">
            <i class="fas fa-arrow-down" aria-hidden="true"></i>&nbsp;Insertar
        </a>
        <span id="loading" style="display:none">
            <i class="fas fa-refresh fa-spin" aria-hidden="true"></i>Guardando...
        </span>
    </div>
</div>  