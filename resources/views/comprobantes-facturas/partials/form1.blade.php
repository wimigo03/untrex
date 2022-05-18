{{--{{Form::hidden('comprobante_id',$comprobante->id)}}--}}
<div class="form-group row">
    <div class="col-md-2">
        {{Form::label('numero','Nro Factura',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('numero',null,['class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('numero') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('numero','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('monto','Monto:',['class' => 'd-inline font-verdana-bg'])}}
        <div class="input-group input-group-sm">                                
            {{Form::text('monto',null,['class'=>'form-control form-control-sm font-verdana-bg', 'oninput' => 'limitDecimalPlaces(event, 2)','onkeypress' => 'return isNumberKey(event)', 'onKeyUp' => 'Copiar()' . ( $errors->has('monto') ? ' is-invalid' : '' ),'autocomplete'=>'off','id'=>'monto'])}}
            <div class="input-group-append"><span class="input-group-text">BS</span></div>
            {!! $errors->first('monto','<span class="invalid-feedback d-block">:message</span>') !!}
        </div>
    </div>
    <div class="col-md-2">
        {{Form::label('cod_control','Codigo de control',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('cod_control',null,['class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('cod_control') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('cod_control','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        {{Form::label('proveedor','Proveedor',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proveedor',$proveedores,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('proveedores') ? ' is-invalid' : '' ),'id'=>'proveedores']) !!}
        {!! $errors->first('proveedor','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    {{--
    <div class="col-md-6">
        {{Form::label('plan_cuenta','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plan_cuenta',$plan_cuentas,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuentas') ? ' is-invalid' : '' ),'id'=>'plan_cuentas']) !!}
        {!! $errors->first('plan_cuenta','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2 cheque">
        {{Form::label('tipo_transaccion','Tipo',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('tipo_transaccion', array('CHEQUE'=>'CHEQUE','TRANSFERENCIA'=>'TRANSFERENCIA'), null, ['class' => 'form-control form-control-sm ', 'placeholder' => '--Seleccionar--', 'id' => 'tipo_transaccion']) !!}
    </div>
    <div class="col-md-2 cheque">
        {{Form::label('cheque_nro','N° Cheque',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('cheque_nro',null,['class'=>'text-uppercase form-control form-control-sm'. ( $errors->has('cheque_nro') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('cheque_nro','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-6 cheque">
        {{Form::label('cheque_orden','A la Orden',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('cheque_orden',null,['class'=>'text-uppercase form-control form-control-sm'. ( $errors->has('cheque_orden') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('cheque_orden','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('plan_cuenta_auxiliar','Auxiliar',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plan_cuenta_auxiliar',$plan_cuentas_auxiliares,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuentas_auxiliares') ? ' is-invalid' : '' ),'id'=>'plan_cuentas_auxiliares']) !!}
        {!! $errors->first('plan_cuenta_auxiliar','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-8">
        {{Form::label('glosa','Glosa',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('glosa',null,['class'=>'form-control form-control-sm'. ( $errors->has('glosa') ? ' is-invalid' : '' ),'autocomplete'=>'off','id'=>'glosa'])}}
        {!! $errors->first('glosa','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2 vista-debe-bs">
        {{Form::label('debe_bs','Debe (Bs.)',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('debe_bs',null,['class'=>'form-control form-control-sm'. ( $errors->has('debe_bs') ? ' is-invalid' : '' ),'id'=>'debe_bs','autocomplete'=>'off','onkeyup' => 'countChars(this,1);','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('debe_bs','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2 vista-haber-bs">
        {{Form::label('haber_bs','Haber (Bs.)',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('haber_bs',null,['class'=>'form-control form-control-sm'. ( $errors->has('haber_bs') ? ' is-invalid' : '' ),'id'=>'haber_bs','autocomplete'=>'off','onkeyup' => 'countChars(this,2);','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('haber_bs','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2 vista-debe-sus">
        {{Form::label('debe_sus','Debe ($u$)',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('debe_sus',null,['class'=>'form-control form-control-sm'. ( $errors->has('debe_sus') ? ' is-invalid' : '' ),'id'=>'debe_sus','autocomplete'=>'off', 'readonly'=>'readonly'])}}
        {!! $errors->first('debe','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2 vista-haber-sus">
        {{Form::label('haber_sus','Haber ($u$)',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('haber_sus',null,['class'=>'form-control form-control-sm'. ( $errors->has('haber_sus') ? ' is-invalid' : '' ),'id'=>'haber_sus','autocomplete'=>'off', 'readonly'=>'readonly'])}}
        {!! $errors->first('haber_sus','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>--}}
    <div class="col-md-4 text-right"><br>
        <button class="btn btn-warning font-verdana-bg" type="submit" id="insertar">
            <i class="fas fa-arrow-down"></i>&nbsp;Insertar
        </button>
    </div>
</div>