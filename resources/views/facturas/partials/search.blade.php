<div class="form-group row font-verdana-bg">
    <div class="col-md-3">
        {{Form::label('socio','Socio',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('socio',$socios,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2','id'=>'socios']) !!}
    </div>
    <div class="col-md-3">
        {{Form::label('proveedor','Proveedor',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proveedor',$proveedores,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2','id'=>'proveedor']) !!}
    </div>
    <div class="col-md-3">
        {{Form::label('nro_factura','Nro Factura',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nro_factura',null,['class'=>'form-control form-control-sm font-verdana-bg','id'=>'nro_factura','onkeypress' => 'return valideKey(event);'])}}
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha','Fecha',['class' => 'd-inline'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countChars(this);'])}}
    </div>
    <div class="col-md-5">
        {{Form::label('glosa','Glosa',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('glosa',null,['class'=>'form-control form-control-sm font-verdana-bg','id'=>'glosa'])}}
    </div>
    <div class="col-md-2">
        {{Form::label('monto','Monto',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('monto',null,['class'=>'form-control form-control-sm font-verdana-bg','id'=>'monto','onkeypress' => 'return valideKey(event);'])}}
    </div>
    <div class="col-md-5 text-right"><br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar&nbsp;
        </button>
        @if (isset($facturas))
            <a href="{{route('facturas.index')}}" class="btn btn-danger font-verdana-bg">
                &nbsp;<i class="fas fa-reply" aria-hidden="true"></i>&nbsp;Todas las facturas
            </a>
        @endif
    </div>
</div>