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
        {!! $errors->first('fecha','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2"><br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar&nbsp;
        </button>
    </div>
    <div class="col-md-7 text-right"><br>
        <a href="{{route('cotizaciones.create')}}" class="btn btn-success font-verdana-bg">
            <i class="fas fa-plus"></i>&nbsp;Crear Cotizacion
        </a>
    </div>
</div>