{{Form::hidden('comprobante_id',$comprobante->id)}}
<div class="form-group row">
    <div class="col-md-2">
        {{Form::label('Nro','Nro',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nro_comprobante',$comprobante->nro_comprobante,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nro_comprobante'])}}
        {!! $errors->first('nro_comprobante','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-1">
        {{Form::label('Tc','TC',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('taza_cambio',$comprobante->tipo_cambio,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'taza_cambio'])}}
        {!! $errors->first('taza_cambio','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-1">
        {{Form::label('Ufv','UFV',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('ufv',$comprobante->ufv,['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'ufv'])}}
        {!! $errors->first('ufv','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-1">
        {{ Form::label('moneda','Moneda',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('moneda', $comprobante->moneda, ['readonly'=>true,'class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('moneda') ? ' is-invalid' : '' )]) !!}
        {!! $errors->first('moneda','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-6">
        {{Form::label('nombre','Nombre',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nombre',strtoupper($user->name),['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'nombre'])}}
        {!! $errors->first('nombre','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    @if ($comprobante->status == 0)
        <div class="col-md-1 text-right">
            <br>
            <a href="{{route('comprobantes.editar', $comprobante->id)}}" class="btn btn-success font-verdana-bg">
                <i class="fas fa-edit" aria-hidden="true"></i>
            </a>
        </div>    
    @endif
    <div class="col-md-5">
        {{ Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::text('proyecto', $proyecto->nombre, ['readonly'=>true,'class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('proyecto') ? ' is-invalid' : '' )]) !!}
        {!! $errors->first('proyecto','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-1">
        {{ Form::label('copia','¿Copia?',['class' => 'd-inline font-verdana-bg'])}}
        @php
            if($comprobante->copia == 1){
                $copia = "SI";
            }else{
                $copia = "NO";
            }
        @endphp
        {!! Form::text('copia', $copia, ['readonly'=>true,'class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('copia') ? ' is-invalid' : '' )]) !!}
        {!! $errors->first('copia','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('Fecha','Fecha Comprobante',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('fecha',\Carbon\Carbon::parse($comprobante->fecha)->format('d/m/Y'),['readonly'=>true,'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countChars(this);'])}}
        {!! $errors->first('fecha','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('tipo','Tipo',['class' => 'd-inline font-verdana-bg font-verdana-bg'])}}
        {!! Form::select('tipo', array('1'=>'INGRESO','2'=>'EGRESO','3'=>'TRASPASO'), $comprobante->tipo, ['readonly'=>true,'class' => 'form-control form-control-sm ' . ( $errors->has('tipo') ? ' is-invalid' : '' )]) !!}
        {!! $errors->first('tipo','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-12">
        {{Form::label('concepto','Concepto',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::textarea('concepto',$comprobante->concepto,['readonly'=>true,'class'=>'form-control form-control-sm'. ( $errors->has('concepto') ? ' is-invalid' : '' ),'rows'=>'1'])}}
    </div>
</div>
<hr>
<div class="form-group row">
    {{--<div class="col-md-3">
        {{Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proyecto',$proyectos,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('proyectos') ? ' is-invalid' : '' ),'id'=>'proyectos']) !!}
        {!! $errors->first('proyecto','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>--}}
    <div class="col-md-3">
        {{Form::label('centro','Centro',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('centro',$centros,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('centros') ? ' is-invalid' : '' ),'id'=>'centros']) !!}
        {!! $errors->first('centro','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-6">
        {{Form::label('plan_cuenta','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plan_cuenta',$plan_cuentas,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuentas') ? ' is-invalid' : '' ),'id'=>'plan_cuentas']) !!}
        {!! $errors->first('plan_cuenta','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3 cheque">
        {{Form::label('tipo_transaccion','Tipo',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('tipo_transaccion', array('CHEQUE'=>'CHEQUE','TRANSFERENCIA'=>'TRANSFERENCIA'), null, ['class' => 'form-control form-control-sm ', 'placeholder' => '--Seleccionar--', 'id' => 'tipo_transaccion']) !!}
    </div>
    <div class="col-md-3 cheque">
        {{Form::label('cheque_nro','N° Cheque/Transferencia',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('cheque_nro',null,['class'=>'text-uppercase form-control form-control-sm'. ( $errors->has('cheque_nro') ? ' is-invalid' : '' ),'autocomplete'=>'off','onkeypress' => 'return valideKey(event);'])}}
        {!! $errors->first('cheque_nro','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-7 cheque">
        {{Form::label('cheque_orden','A la Orden',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('cheque_orden',null,['class'=>'text-uppercase form-control form-control-sm'. ( $errors->has('cheque_orden') ? ' is-invalid' : '' ),'autocomplete'=>'off'])}}
        {!! $errors->first('cheque_orden','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-5">
        {{Form::label('plan_cuenta_auxiliar','Auxiliar',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('plan_cuenta_auxiliar',$plan_cuentas_auxiliares,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2'. ( $errors->has('plan_cuentas_auxiliares') ? ' is-invalid' : '' ),'id'=>'plan_cuentas_auxiliares']) !!}
        {!! $errors->first('plan_cuenta_auxiliar','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-7">
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
    </div>
    <div class="col-md-4 text-right"><br>
        <button class="btn btn-primary font-verdana-bg" type="submit" id="insertar">
            <i class="fas fa-arrow-down"></i>&nbsp;Insertar
        </button>
        <a href="{{route('facturas.create', $comprobante->id)}}" class="btn btn-success font-verdana-bg">
            <i class="fas fa-book" aria-hidden="true"></i>&nbsp;Facturas
        </a>
    </div>
</div>