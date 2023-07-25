<div class="form-group row">
    <div class="col-md-3">
        <label for="consorcio" class="d-inline font-verdana-bg">
            Consorcio
        </label>
        <select name="consorcio" id="consorcio" placeholder="--Consorcios--" class="form-control form-control-sm font-verdana-bg . {{ ( $errors->has('consorcio') ? ' is-invalid' : '' ) }}">
            <option value="">-</option>
            @foreach ($consorcios as $index => $value)
                <option value="{{ $index }}" @if(request('consorcio') == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
        {!! $errors->first('consorcio','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-3">
        <label for="socio" class="d-inline font-verdana-bg">
            Socio
        </label>
        <select name="socio" id="socio" placeholder="--Socios--" class="form-control form-control-sm font-verdana-bg . {{ ( $errors->has('username') ? ' is-invalid' : '' ) }}">
            <option value="">-</option>
            @foreach ($socios as $index => $value)
                <option value="{{ $index }}" @if(request('socio') == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
        {!! $errors->first('socio','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3">
        <label for="username" class="d-inline font-verdana-bg">
            Nombre de usuario
        </label>
        <input type="text" name="username" value="" class="form-control form-control-sm font-verdana-bg . {{ ( $errors->has('username') ? ' is-invalid' : '' ) }}" disabled>
    </div>
    <div class="col-md-4">
        <label for="name" class="d-inline font-verdana-bg">
            Nombre(s)
        </label>
        <input type="text" name="name" value="" class="form-control form-control-sm font-verdana-bg" . {{ ( $errors->has('name') ? ' is-invalid' : '' ) }}>
        {!! $errors->first('name','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        <label for="name" class="d-inline font-verdana-bg">
            Apellidos(s)
        </label>
        <input type="text" name="lastname" value="" class="form-control form-control-sm font-verdana-bg" . {{ ( $errors->has('lastname') ? ' is-invalid' : '' ) }}>
        {!! $errors->first('lastname','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4">
        <label for="n_carnet" class="d-inline font-verdana-bg">
            Nro. Carnet (Sin espacios)
        </label>
        <input type="text" name="n_carnet" value="" class="form-control form-control-sm font-verdana-bg" . {{ ( $errors->has('n_carnet') ? ' is-invalid' : '' ) }}>
        {!! $errors->first('n_carnet','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-5">
        <label for="email" class="d-inline font-verdana-bg">
            Email
        </label>
        <input type="text" name="email" value="" class="form-control form-control-sm font-verdana-bg" . {{ ( $errors->has('email') ? ' is-invalid' : '' ) }}>
        {!! $errors->first('email','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
</div>