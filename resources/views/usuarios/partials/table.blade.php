<div class="form-group row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped" id="tablaAjax" style="white-space: nowrap;">
                <thead>
                    <tr class="font-verdana-bg">
                        <td class="text-left p-1" style="vertical-align: bottom;"><b>COD</b></td>
                        <td class="text-left p-1" style="vertical-align: bottom;"><b>SOCIO</b></td>
                        <td class="text-left p-1" style="vertical-align: bottom;"><b>USERNAME</b></td>
                        <td class="text-left p-1" style="vertical-align: bottom;"><b>NOMBRE COMPLETO</b></td>
                        <td class="text-left p-1" style="vertical-align: bottom;"><b>CORREO ELECTRONICO</b></td>
                        <td class="text-center p-1" style="vertical-align: bottom;"><b>ESTADO</b></td>
                        <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $datos)
                            <tr class="font-verdana-bg">
                                <td class="text-justify p-1" style="vertical-align: middle;">{{$datos->id}}</td>
                                <td class="text-justify p-1" style="vertical-align: middle;">{{isset($datos->socio)?$datos->socio->nombre:'#'}}</td>
                                <td class="text-justify p-1" style="vertical-align: middle;">{{$datos->username}}</td>
                                <td class="text-justify p-1" style="vertical-align: middle;">{{$datos->name}}</td>
                                <td class="text-justify p-1" style="vertical-align: middle;">{{$datos->email}}</td>
                                <td class="text-center p-1" style="vertical-align: middle;">
                                    @if ($datos->estado == '1')
                                        <span class="btn btn-xs btn-success font-verdana-sm"><b>ACTIVO</b></span>
                                    @else
                                        <span class="btn btn-xs btn-danger font-verdana-sm"><b>NO ACTIVO</b></span>
                                    @endif
                                </td>
                                <td class="text-center p-1" style="vertical-align: middle;">
                                    <a href="{{ route('usuario.editar', $datos->id) }}" class="btn btn-xs btn-warning">
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 font-verdana-sm">
        <p class="text-muted">Mostrando 
            <strong>{{ $usuarios->count() }}</strong> registros de 
            <strong>{{$usuarios->total() }}</strong> totales
        </p>
        {{ $usuarios->appends(Request::all())->links() }}
    </div>
</div>

