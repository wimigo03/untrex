<div class="form-group row">
    <div class="col-md-3">
        {{Form::label('razon_social','Razon social',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('razon_social',null,['class'=>'form-control form-control-sm font-verdana-bg','autocomplete'=>'off'])}}
    </div>
    <div class="col-md-3">
        {{Form::label('nombre_comercial','Nombre comercial',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nombre_comercial',null,['class'=>'form-control form-control-sm font-verdana-bg','autocomplete'=>'off'])}}
    </div>
    <div class="col-md-2">
        {{Form::label('nit','Nit',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('nit',null,['class'=>'form-control form-control-sm font-verdana-bg','autocomplete'=>'off'])}}
    </div>
    <div class="col-md-3">
        {{Form::label('ciudad','Ciudad',['class' => 'd-inline font-verdana-bg'])}}
        {{Form::text('ciudad',null,['class'=>'form-control form-control-sm font-verdana-bg','autocomplete'=>'off'])}}
    </div>
    <div class="col-md-1 text-right">
        <br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            &nbsp;<i class="fa fa-search" aria-hidden="true"></i>&nbsp;
        </button>
    </div>
</div>