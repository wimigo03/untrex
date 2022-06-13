<div class="form-group row font-verdana-bg">
    <div class="col-md-8">
        <strong>CUENTA: </strong>{{$plancuenta->nombre}}
    </div>
    <div class="col-md-2">
        <strong>DESDE: </strong>{{\Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y')}}
    </div>
    <div class="col-md-2 text-right">
        <strong>HASTA: </strong>{{\Carbon\Carbon::parse($fecha_final)->format('d/m/Y')}}
    </div>
</div>
<div class="form-group row font-verdana-bg">
    <div class="col-md-8">
        {!! Form::open(['route'=>'libromayor.porcuenta.findauxiliar']) !!}
            <input type="hidden" name="proyecto_id" value="{{$proyecto->id}}">
            <input type="hidden" name="fecha_inicial" value="{{$fecha_inicial}}">
            <input type="hidden" name="fecha_final" value="{{$fecha_final}}">
            <input type="hidden" name="tipo" value="{{$tipo}}">
            <input type="hidden" name="plancuenta_id" value="{{$plancuenta->id}}">
            <select name="plancuentaauxiliar_id" id="plancuentaauxiliar_id" onchange="this.form.submit()"class="form-control form-control-sm">
                <option value="">--Buscar--</option>
                @foreach ($find_auxiliares as $datos)
                    <option value="{{ $datos->plancuentaauxiliar_id }}">{{ $datos->auxiliar }}</option>
                @endforeach
            </select>
        {!! Form::close()!!}
    </div>
    <div class="col-md-1">
        {!! Form::model(Request::all(),['route'=> ['libromayor.porcuenta.search']]) !!}
            <input type="hidden" name="proyecto" value="{{$proyecto->id}}">
            <input type="hidden" name="fecha_inicial" value="{{\Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y')}}">
            <input type="hidden" name="fecha_final" value="{{\Carbon\Carbon::parse($fecha_final)->format('d/m/Y')}}">
            <input type="hidden" name="tipo" value="{{$tipo}}">
            <input type="hidden" name="plancuenta_id" value="{{$plancuenta->id}}">
            <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Todos los auxiliares" class="btn btn-warning font-verdana-bg">
                <i class="fas fa-angle-double-left"></i>
            </button>
        {!! Form::close()!!}
    </div>
    <div class="col-md-3 text-right">
        <a href="{{route('libromayor.porcuenta.index')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Regresar" class="btn btn-primary font-verdana-bg">
            <i class="fas fa-angle-double-left"></i>
        </a>
        {{--<a href="{{ route('libromayor.porcuenta.excel',['dat1' => $proyecto,'dat2' => $tipo,'dat3' => $fecha_inicial,'dat4' => $fecha_final,'dat5' => $plancuenta->id]) }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Exportar a Excel" class="btn btn-sm btn-success font-verdana-bg">
            <i class="fas fa-file-excel"></i>
        </a>--}}
        @if (isset($plancuentaauxiliar_id))
            <a href="{{ route('libromayor.porcuenta.auxiliarPdf2',['dat1' => $proyecto,'dat2' => $tipo,'dat3' => $fecha_inicial,'dat4' => $fecha_final,'dat5' => $plancuenta->id,'dat6' => $plancuentaauxiliar_id]) }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Exportar a Pdf" class="btn btn-danger font-verdana-bg" target="_blank">
                <i class="fas fa-file-pdf"></i>
            </a>
        @else
            <a href="{{ route('libromayor.porcuenta.auxiliarPdf1',['dat1' => $proyecto,'dat2' => $tipo,'dat3' => $fecha_inicial,'dat4' => $fecha_final,'dat5' => $plancuenta->id]) }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Exportar a Pdf" class="btn btn-danger font-verdana-bg" target="_blank">
                <i class="fas fa-file-pdf"></i>
            </a> 
        @endif
        
    </div>
</div>