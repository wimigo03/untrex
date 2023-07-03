<div class="form-group row">
    <div class="col-md-3">
        <label for="socio" class="d-inline font-verdana-bg">
            Socio
        </label>
        <input type="hidden" name="user_id" value="{{$usuario->id}}">
        <input type="text" value="{{ $usuario->socio!=null?$usuario->socio->nombre:'' }}" class="form-control form-control-sm font-verdana-bg" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3">
        <label for="username" class="d-inline font-verdana-bg">
            Nombre de usuario
        </label>
        <input type="text" name="username" value="{{ $usuario->username }}" class="form-control form-control-sm font-verdana-bg . {{ ( $errors->has('username') ? ' is-invalid' : '' ) }}">
        {!! $errors->first('username','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-4">
        <label for="name" class="d-inline font-verdana-bg">
            Nombre Completo
        </label>
        <input type="text" name="name" value="{{ $usuario->name }}" class="form-control form-control-sm font-verdana-bg" . {{ ( $errors->has('name') ? ' is-invalid' : '' ) }}>
        {!! $errors->first('name','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-5">
        <label for="email" class="d-inline font-verdana-bg">
            Email
        </label>
        <input type="text" name="email" value="{{ $usuario->email }}" class="form-control form-control-sm font-verdana-bg" . {{ ( $errors->has('email') ? ' is-invalid' : '' ) }}>
        {!! $errors->first('email','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3">
        <label for="password" class="d-inline font-verdana-bg">
            Contraseña
        </label>
        <input type="text" name="password" value="" class="form-control form-control-sm font-verdana-bg" . {{ ( $errors->has('password') ? ' is-invalid' : '' ) }}>
        {!! $errors->first('password','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        <label for="estado" class="d-inline font-verdana-bg">
            Estado
        </label>
        <select name="estado" id="estado" placeholder="--Seleccionar--" class="form-control form-control-sm" . {{ ( $errors->has('estado') ? ' is-invalid' : '' ) }}>
            <option value="1" @if ($usuario->estado == '1') selected @endif>ACTIVO</option>
            <option value="0" @if ($usuario->estado == '0') selected @endif>NO ACTIVO</option>
        </select>
        {!! $errors->first('estado','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
</div>