<div class="form-group row">
    <div class="col-md-3">
        <select name="socio" id="socios" placeholder="--Socios--" class="form-control form-control-sm">
            <option value="">-</option>
            @foreach ($socios as $index => $value)
                <option value="{{ $index }}" @if(request('socio') == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <input type="text" name="username" value="{{request('username')}}" placeholder="--Usuario--" class="form-control form-control-sm font-verdana-bg">
    </div>
    <div class="col-md-4">
        <input type="text" name="name" value="{{request('name')}}" placeholder="--Nombre Completo--" class="form-control form-control-sm font-verdana-bg">
    </div>
    <div class="col-md-3">
        <input type="text" name="email" value="{{request('email')}}" placeholder="--Correo Electronico--" class="form-control form-control-sm font-verdana-bg">
    </div>
</div>