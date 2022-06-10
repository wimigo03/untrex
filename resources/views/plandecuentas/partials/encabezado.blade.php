<div class="card">
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-2">
                {{Form::label('Codigo','Codigo Cuenta',['class' => 'd-inline font-verdana-bg'])}}
                {{Form::text('codigo',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_codigo'])}}
            </div>
            <div class="col-md-4">
                {{Form::label('Nombre','Cuenta',['class' => 'd-inline font-verdana-bg'])}}
                {{Form::text('nombre',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_nombre'])}}
            </div>
            <div class="col-md-6">
                {{Form::label('Descripcion','Descripcion',['class' => 'd-inline font-verdana-bg'])}}
                {{Form::text('descripcion',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_descripcion'])}}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-2">
                {{Form::label('Cuenta_detalle','Cuenta detalle',['class' => 'd-inline font-verdana-bg'])}}
                {{Form::text('cuenta_detalle',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_cuenta_detalle'])}}
            </div>
            <div class="col-md-2">
                {{Form::label('Cheque','Cheque',['class' => 'd-inline font-verdana-bg'])}}
                {{Form::text('cheque',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_cheque'])}}
            </div>
            <div class="col-md-2">
                {{Form::label('Auxiliar','Auxiliar',['class' => 'd-inline font-verdana-bg'])}}
                {{Form::text('auxiliar',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_auxiliar'])}}
            </div>
            <div class="col-md-2">
                {{Form::label('Moneda','moneda',['class' => 'd-inline font-verdana-bg'])}}
                {{Form::text('moneda',null,['readonly' => true, 'class'=>'form-control form-control-sm font-verdana-bg', 'id' => 'js_moneda'])}}
            </div>
            {{--<div class="col-md-8 text-right">
                <br>
                <a href="{{route('editar_dependiente','editar-dependiente')}}" id="editar-dependiente" data-url='{{route('editar_dependiente','editar-dependiente')}}' class="btn btn-warning font-verdana-bg">
                    <i class="fas fa-edit"></i>&nbsp;Modificar
                </a>
                <a href="{{route('create_dependiente','create-dependiente')}}" id="create-dependiente" data-url='{{route('create_dependiente','create-dependiente')}}' class="btn btn-success font-verdana-bg">
                    <i class="fas fa-plus"></i>&nbsp;Dependiente
                </a>
            </div>--}}
        </div>
    </div>
</div>