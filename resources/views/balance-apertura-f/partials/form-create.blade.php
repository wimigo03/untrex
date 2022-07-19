<div class="form-group row">
    <div class="col-md-6">
        {{Form::label('proyecto','Proyecto',['class' => 'd-inline font-verdana-bg'])}}
        <font size="1px" color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        <input type="hidden" name="proyecto" value="{{$proyecto_id}}">
        {!! Form::select('proyecto',$proyectos,$proyecto_id, ['disabled' => true,'placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm' . ( $errors->has('proyecto') ? ' is-invalid' : '' ),'id'=>'proyecto']) !!}
        {!! $errors->first('proyecto','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('gestion','Gestion',['class' => 'd-inline font-verdana-bg'])}}
        <font size="1px" color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {!! Form::select('gestion',$gestion,null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2' . ( $errors->has('gestion') ? ' is-invalid' : '' ),'id'=>'gestion']) !!}
        {!! $errors->first('gestion','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2">
        {{Form::label('moneda','Moneda',['class' => 'd-inline font-verdana-bg'])}}
        <font size="1px" color="red">&nbsp;<i class="fas fa-asterisk fa-xs"></i></font>
        {!! Form::select('moneda',array('BS' => 'Bs', 'SUS' => '$us'),null, ['placeholder' => '--Seleccionar--','class' => 'form-control form-control-sm select2' . ( $errors->has('moneda') ? ' is-invalid' : '' ),'id'=>'moneda']) !!}
        {!! $errors->first('moneda','<span class="invalid-feedback d-block">:message</span>') !!}
    </div>
    <div class="col-md-2 text-right">
        <br>
        <button type="submit" class="btn btn-primary font-verdana-bg">
            <i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Guardar&nbsp;
        </button>
    </div>
</div>

{{--<div class="card card-custom">
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-12">
                <center>
                    <div class="table-responsive table-striped">
                        <table id="tablaAjax" class="display responsive" style="width:100%">
                            <thead>
                                <tr class="font-verdana">
                                    <td class="text-center p-1"><b>CODIGO</b></td>
                                    <td class="text-center p-1"><b>CUENTA</b></td>
                                    <td width="30%" class="text-center p-1"><b>AUXILIAR</b></td>
                                    <td width="10%" class="text-center p-1"><b>DEBE</b></td>
                                    <td width="10%" class="text-center p-1"><b>HABER</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activos as $datos_activos)
                                    <tr class="font-verdana">
                                        <td class="text-justify p-1">{{$datos_activos->codigo}}</td>
                                        <td class="text-justify p-1">{{$datos_activos->nombre}}</td>
                                        <td class="text-justify p-1">
                                            {!! Form::select('auxiliar_id[]', $auxiliares, null, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2' . ( $errors->has('auxiliar_id') ? ' is-invalid' : '' )]) !!}
                                        </td>
                                        <td class="text-right p-1">
                                            {!! Form::text('debe[]', 0, ['class' => 'form-control form-control-sm font-verdana-bg text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                        </td>
                                        <td class="text-right p-1">
                                            {!! Form::text('haber[]', 0, ['class' => 'form-control form-control-sm font-verdana-bg text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach ($pasivos as $datos_pasivos)
                                    <tr class="font-verdana">
                                        <td class="text-justify p-1">{{$datos_pasivos->codigo}}</td>
                                        <td class="text-justify p-1">{{$datos_pasivos->nombre}}</td>
                                        <td class="text-justify p-1">
                                            {!! Form::select('auxiliar_id[]', $auxiliares, null, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2' . ( $errors->has('auxiliar_id') ? ' is-invalid' : '' )]) !!}
                                        </td>
                                        <td class="text-right p-1">
                                            {!! Form::text('debe[]', 0, ['class' => 'form-control form-control-sm font-verdana-bg text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                        </td>
                                        <td class="text-right p-1">
                                            {!! Form::text('haber[]', 0, ['class' => 'form-control form-control-sm font-verdana-bg text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach ($patrimonios as $datos_patrimonios)
                                    <tr class="font-verdana">
                                        <td class="text-justify p-1">{{$datos_patrimonios->codigo}}</td>
                                        <td class="text-justify p-1">{{$datos_patrimonios->nombre}}</td>
                                        <td class="text-justify p-1">
                                            {!! Form::select('auxiliar_id[]', $auxiliares, null, ['placeholder' =>'--Seleccionar--','class' => 'form-control form-control-sm select2' . ( $errors->has('auxiliar_id') ? ' is-invalid' : '' )]) !!}
                                        </td>
                                        <td class="text-right p-1">
                                            {!! Form::text('debe[]', 0, ['class' => 'form-control form-control-sm font-verdana-bg text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                        </td>
                                        <td class="text-right p-1">
                                            {!! Form::text('haber[]', 0, ['class' => 'form-control form-control-sm font-verdana-bg text-right','autocomplete'=>'off','onkeypress' => 'return valideKey(event);']) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>--}}