<div class="form-group row font-verdana-bg">
    <div class="col-md-3">
        <select name="proyecto" id="proyecto" placeholder="--Seleccionar--" class="form-control form-control-sm">
            <option value="">-</option>
            @foreach ($proyectos as $index => $value)
                <option value="{{ $index }}" @if(request('proyecto') == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
        {!! $errors->first('proyecto','<span class="invalid-feedback d-block">Proyecto no seleccionado.</span>') !!}
    </div>
    <div class="col-md-2">
        <input type="text" name="fecha_i" value="{{request('fecha_i')}}" placeholder="--Inicial--" class="form-control form-control-sm font-verdana-bg" id="fecha_i" data-language="es" autocomplete="off" onkeyup="countCharsInicial(this);">
        <em><span id="message_i" class="text-danger font-verdana-sm"></span></em>
        {!! $errors->first('fecha_i','<span class="invalid-feedback d-block">Se debe introducir una fecha inicial.</span>') !!}
    </div>
    <div class="col-md-2">
        <input type="text" name="fecha_f" value="{{request('fecha_f')}}" placeholder="--Final--" class="form-control form-control-sm font-verdana-bg" id="fecha_f" data-language="es" autocomplete="off" onkeyup="countCharsFinal(this);">
        <em><span id="message_f" class="text-danger font-verdana-sm"></span></em>
        {!! $errors->first('fecha_f','<span class="invalid-feedback d-block">Se debe introducir una fecha final.</span>') !!}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-3">
        <select name="tipo_comp" id="tipo_comp" placeholder="--Seleccionar--" class="form-control form-control-sm">
            <option value="">-</option>
            <option value="1" @if(request('tipo_comp') == '1') selected @endif >INGRESO</option>
            <option value="2" @if(request('tipo_comp') == '2') selected @endif >EGRESO</option>
            <option value="3" @if(request('tipo_comp') == '3') selected @endif >TRASPASOS</option>
            <option value="4" @if(request('tipo_comp') == '4') selected @endif >TODOS</option>
        </select>
        {!! $errors->first('tipo_comp','<span class="invalid-feedback d-block">Tipo comprobante no seleccionado.</span>') !!}
    </div>
    <div class="col-md-3">
        <select name="estado" id="estado" placeholder="--Seleccionar--" class="form-control form-control-sm">
            <option value="">-</option>
            <option value="0" @if(request('estado') == '0') selected @endif >BORRADOR</option>
            <option value="1" @if(request('estado') == '1') selected @endif >APROBADOS</option>
            <option value="2" @if(request('estado') == '2') selected @endif >ANULADOS</option>
            <option value="3" @if(request('estado') == '3') selected @endif >TODOS</option>
        </select>
        {!! $errors->first('estado','<span class="invalid-feedback d-block">Estado no seleccionado.</span>') !!}
    </div>
</div>