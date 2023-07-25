<div class="form-group row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped" id="tablaAjax" style="white-space: nowrap;">
                <thead>
                    <tr class="font-verdana-bg">
                        <td class="text-left p-1" style="vertical-align: bottom;"><b>CODIGO</b></td>
                        <td class="text-left p-1" style="vertical-align: bottom;"><b>USERNAME</b></td>
                        <td class="text-center p-1" style="vertical-align: bottom;"><b>ESTADO</b></td>
                        <td class="text-center p-1"><i class="fa fa-bars" aria-hidden="true"></i></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users_proyectos as $datos)
                            <tr class="font-verdana-bg">
                                <td class="text-justify p-1" style="vertical-align: middle;">{{$datos->id}}</td>
                                <td class="text-justify p-1" style="vertical-align: middle;">{{$datos->user->username}}</td>
                                <td class="text-center p-1" style="vertical-align: middle;">
                                    @if ($datos->estado == '1')
                                        <span class="btn btn-xs btn-success font-verdana-sm"><b>ACTIVO</b></span>
                                    @else
                                        <span class="btn btn-xs btn-danger font-verdana-sm"><b>NO ACTIVO</b></span>
                                    @endif
                                </td>
                                <td class="text-center p-1" style="vertical-align: middle;">
                                    @if ($datos->estado == '1')
                                        <a href="{{ route('usuario.proyecto.baja', $datos->id) }}" class="btn btn-xs btn-danger">
                                            <i class="fas fa-arrow-down" aria-hidden="true"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('usuario.proyecto.alta', $datos->id) }}" class="btn btn-xs btn-success">
                                            <i class="fas fa-arrow-up" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 font-verdana-sm">
        <p class="text-muted">Mostrando 
            <strong>{{ $users_proyectos->count() }}</strong> registros de 
            <strong>{{$users_proyectos->total() }}</strong> totales
        </p>
        {{ $users_proyectos->appends(Request::all())->links() }}
    </div>
</div>

