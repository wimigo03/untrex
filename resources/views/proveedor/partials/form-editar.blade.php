<div class="form-group row">
    <div class="col-md-4">
        <input type="hidden" name="proveedor_id" value="{{$proveedor->proveedor_id}}" id="proveedor_id">
        {{Form::label('razon_social','Razon social',['class' => 'd-inline font-verdana-bg'])}}
        <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {{Form::text('razon_social',$proveedor->razon_social,['id' => 'razon_social','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('razon_social') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeyup' => 'copiarRazonSocial(this);'])}}
        {!! $errors->first('razon_social','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('nombre_comercial','Nombre comercial',['class' => 'd-inline font-verdana-bg'])}}
        <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {{Form::text('nombre_comercial',$proveedor->nombre_comercial,['id' => 'nombre_comercial','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('nombre_comercial') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('nombre_comercial','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('nit','Nit',['class' => 'd-inline font-verdana-bg'])}}
        <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {{Form::text('nit',$proveedor->nit,['id' => 'nit','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('nit') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('nit','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        <input type="hidden" name="ciudad_old" value="{{ old('ciudad') }}" id="ciudad_old">
        {{Form::label('ciudad','Ciudad',['class' => 'd-inline font-verdana-bg'])}}
        <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {!! Form::select('ciudad',$ciudades,$proveedor->ciudad_id, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2' . ( $errors->has('ciudad') ? ' is-invalid' : '' ),'id'=>'ciudades']) !!}
        {!! $errors->first('ciudad','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
</div>
<div class="card card-custom otra_ciudad bg-muted">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                &nbsp;
            </div>
            <div class="col-md-3">
                {{Form::label('nueva_ciudad','Otra ciudad',['class' => 'd-inline font-verdana-bg'])}}
                <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
                {{Form::text('nueva_ciudad',null,['id' => 'nueva_ciudad','placeholder' => 'Nueva ciudad','class'=>'form-control form-control-sm font-verdana-bg text-center' . ( $errors->has('nueva_ciudad') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
                {!! $errors->first('nueva_ciudad','<span class="invalid-feedback d-block">:message</span>') !!}
            </div>
            <div class="col-md-3">
                {{Form::label('abreviatura','Abreviatura',['class' => 'd-inline font-verdana-bg'])}}
                <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
                {{Form::text('abreviatura',null,['id' => 'abreviatura','placeholder' => 'Abreviacion','class'=>'form-control form-control-sm font-verdana-bg text-center' . ( $errors->has('abreviatura') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
                {!! $errors->first('abreviatura','<span class="invalid-feedback d-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2">
        {{Form::label('tipo','Tipo',['class' => 'd-inline font-verdana-bg'])}}
        <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {!! Form::select('tipo',array('MATERIAL' => 'MATERIAL',
                                        'SERVICIO' => 'SERVICIO',
                                        'CONTADO' => 'CONTADO',
                                        'CREDITO' => 'CREDITO',
                                        'REPUESTO' => 'REPUESTO',
                                        'VARIOS' => 'VARIOS'),$proveedor->tipo, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2' . ( $errors->has('tipo') ? ' is-invalid' : '' ),'id'=>'tipo']) !!}
        {!! $errors->first('tipo','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        {{Form::label('nro_cuenta','Nro. de cuenta',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nro_cuenta',$proveedor->nro_cuenta,['id' => 'nro_cuenta','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('nro_cuenta') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('nro_cuenta','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('titular_cuenta','Titular de la cuenta',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('titular_cuenta',$proveedor->titular_cuenta,['id' => 'titular_cuenta','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('titular_cuenta') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('titular_cuenta','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        {{Form::label('banco','Banco',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('banco',$proveedor->banco,['id' => 'banco','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('banco') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('banco','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-6">
        {{Form::label('direccion','Direccion',['class' => 'd-inline font-verdana-bg'])}}
        <font color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {{Form::text('direccion',$proveedor->direccion,['id' => 'direccion','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('direccion') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('direccion','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('email','Correo electronico',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('email',$proveedor->email,['id' => 'email','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('email') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('email','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('contacto_1','Contacto Nro. 1',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('contacto_1',$proveedor->contacto1,['id' => 'contacto_1','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('contacto_1') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('contacto_1','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        {{Form::label('celular_1','Celular Nro. 1',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('celular_1',$proveedor->celular1,['id' => 'celular_1','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('celular_1') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('celular_1','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        {{Form::label('fijo_1','Fijo Nro. 1',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('fijo_1',$proveedor->fijo1,['id' => 'fijo_1','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('fijo_1') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('fijo_1','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        {{Form::label('contacto_2','Contacto Nro. 2',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('contacto_2',$proveedor->contacto2,['id' => 'contacto_2','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('contacto_2') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('contacto_2','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        {{Form::label('celular_2','Celular Nro. 2',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('celular_2',$proveedor->celular2,['id' => 'celular_2','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('celular_2') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('celular_2','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        {{Form::label('fijo_2','Fijo Nro. 2',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('fijo_2',$proveedor->fijo2,['id' => 'fijo_2','class'=>'form-control form-control-sm font-verdana-bg' . ( $errors->has('fijo_2') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('fijo_2','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-8">
        {{Form::label('observaciones','Observaciones',['class' => 'd-inline font-verdana-bg'])}}
        {{ Form::textarea('observaciones',$proveedor->observaciones,['class'=>'form-control form-control-sm font-verdana-bg'. ( $errors->has('observaciones') ? ' is-invalid' : '' ),'rows' => 1]) }}
        {!! $errors->first('observaciones','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Actualizar&nbsp;
        </button>
    </div>
</div>