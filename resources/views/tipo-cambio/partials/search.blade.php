<div class="form-group row font-verdana-bg">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha_desde','Desde',['class' => 'd-inline'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message_desde" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha_desde',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha_desde','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countCharsDesde(this);'])}}
        {!! $errors->first('fecha_desde','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {{Form::label('Fecha_hasta','Hasta',['class' => 'd-inline'])}}
            </div>
            <div class="col-md-6 text-right">
                <em><span id="message_hasta" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('fecha_hasta',null,['class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'fecha_hasta','data-language' => 'es', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'onkeyup' => 'countCharsHasta(this);'])}}
        {!! $errors->first('fecha_hasta','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2"><br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar&nbsp;
        </button>
    </div>
    <div class="col-md-4 text-right"><br>
        <a href="{{route('tipo_cambio.create')}}" class="btn btn-success font-verdana-bg">
            <i class="fas fa-plus"></i>&nbsp;Crear
        </a>
    </div>
</div>