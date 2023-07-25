<div class="form-group row">
    <div class="col-md-3">
        <select name="usuario" id="usuarios" placeholder="--usuarios--" class="form-control form-control-sm">
            <option value="">-</option>
            @foreach ($usuarios as $index => $value)
                <option value="{{ $index }}" @if(request('usuario') == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="estado" id="estados" placeholder="--estados--" class="form-control form-control-sm">
            <option value="">-</option>
            <option value="1" @if(request('estado') == '1') selected @endif >ACTIVOS</option>
            <option value="0" @if(request('estado') == '0') selected @endif >NO ACTIVOS</option>
        </select>
    </div>
</div>