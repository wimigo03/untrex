<div class="row form-group">
    <div class="col-md-1">
        <input type="hidden" name="proyecto_id" value="{{$proyecto_id}}">
    </div>
    <div class="col-md-2">
        {{Form::label('estado','Estado',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('estado', array('0'=>'BORRADOR','1'=>'APROBADO'), null, ['class' => 'form-control form-control-sm select2', 'placeholder' => '--Seleccionar--']) !!}
    </div>
    <div class="col-md-2">
        {{Form::label('gestion','Gestion',['class' => 'd-inline font-verdana-bg'])}}
        {!! Form::select('gestion', $gestion, null, ['id' => 'gestion','class' => 'form-control form-control-sm', 'placeholder' => '--Seleccionar--']) !!}
    </div>
    <div class="col-md-2">
        <div class="row">
            <div class="col-md-8">
                {{Form::label('finicial','Fecha inicial',['class' => 'd-inline font-verdana-bg'])}}
            </div>
            <div class="col-md-4 text-right">
                <em><span id="message_i" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{Form::text('finicial', null, ['class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('finicial') ? ' is-invalid' : '' ), 'id' => 'finicial' ,'data-language' => 'es','autocomplete' => 'off','onkeyup' => 'countChars_i(this);', 'onkeypress' => 'return valideKeyDate(event);'])}}
        {!! $errors->first('finicial','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        <div class="row">
            <div class="col-md-8">
                {{Form::label('ffinal','Fecha final',['class' => 'd-inline font-verdana-bg'])}}
            </div>
            <div class="col-md-4 text-right">
                <em><span id="message_f" class="text-danger font-verdana-sm"></span></em>
            </div>
        </div>
        {{ Form::text('ffinal', null, ['class' => 'form-control form-control-sm font-verdana-bg' . ( $errors->has('ffinal') ? ' is-invalid' : '' ), 'id' => 'ffinal' ,'data-language' => 'es','autocomplete' => 'off','onkeyup' => 'countChars_f(this);', 'onkeypress' => 'return valideKeyDate(event);']) }}
        {!! $errors->first('ffinal','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        <br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Generar&nbsp;
        </button>
    </div>
</div>