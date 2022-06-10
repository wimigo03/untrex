<div class="form-group row font-verdana-bg">
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
    <div class="col-md-2">
        {{Form::label('nro_comprobante','Nro Comprobante',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nro_comprobante',null,['class'=>'form-control form-control-sm font-verdana-bg','id'=>'nro_comprobante'])}}
    </div>
    <div class="col-md-3">
        {{Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('proyecto',$proyectos,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2','id'=>'proyectos']) !!}
    </div>
    <div class="col-md-2">
        {{Form::label('tipo','Tipo',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('tipo', array('1'=>'INGRESO','2'=>'EGRESO','3'=>'TRASPASO'), null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
    </div>
    <div class="col-md-2">
        {{Form::label('estado','Estado',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('estado', array('0'=>'BORRADOR','1'=>'APROBADO','2'=>'ANULADO'), null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2']) !!}
    </div>
    <div class="col-md-6">
        {{Form::label('concepto','Concepto',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('concepto',null,['class'=>'form-control form-control-sm font-verdana-bg','id'=>'concepto'])}}
    </div>
    <div class="col-md-2"><br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar&nbsp;
        </button>
    </div>
    <div class="col-md-4 text-right"><br>
        @if ($back == false)
            <a href="{{route('comprobantes.fiscales.index')}}" class="btn btn-primary font-verdana-bg">
                <i class="fas fa-angle-double-left"></i>
            </a>    
        @endif
    </div>
</div>