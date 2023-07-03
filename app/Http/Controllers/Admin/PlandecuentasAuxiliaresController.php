<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PlanCuentasAuxiliares;
use App\Proyectos;
use App\Proveedores;
use Auth;
use DB;

class PlandecuentasAuxiliaresController extends Controller
{
    public function index(Request $request){
        if(auth()->id() == 1){
            $this->reprocesarAuxiliaresProveedores();
        }
        $plancuentasauxiliares = PlanCuentasAuxiliares::where('proyecto_id',$request->proyecto_id)->where('estado',1)->orderBy('id', 'desc')->paginate();
        $proyecto = Proyectos::where('id',$request->proyecto_id)->first();
        return view('plandecuentasauxiliares.index',compact('plancuentasauxiliares','proyecto'));
    }

    private function reprocesarAuxiliaresProveedores(){
        $proveedores = proveedores::get();
        foreach($proveedores as $datos){
            $auxiliar = PlanCuentasAuxiliares::where('tipo',1)->where('nombre','like','%' . $datos->razon_social . '%')->first();
            if($auxiliar != null){
                $auxiliares = PlanCuentasAuxiliares::find($auxiliar->id);
                $auxiliares->reg_id = $datos->id;
                $auxiliares->update();
            }
        }
    }
    
    public function indexAjax($proyecto_id){
        return datatables()
            ->query(DB::table('plan_cuentas_auxiliares as a')
            ->where('proyecto_id',$proyecto_id)
            ->where('estado',1)
            ->select('a.id as plancuentaauxiliar_id',
                    DB::raw("DATE_FORMAT(a.created_at,'%d/%m/%Y') as fecha_auxiliar"),
                    DB::raw("if(a.tipo = '1','PROVEEDOR',if(a.tipo = '2','TRABAJADOR',if(a.tipo = '3','CLIENTE','OTRO'))) as tipo_auxiliar"),
                    'a.nombre',
                    DB::raw("if(a.estado = '1','ACTIVO','NO ACTIVO') as estado_auxiliar")))
            ->filterColumn('fecha_auxiliar', function($query, $keyword) {
                $sql = "DATE_FORMAT(a.created_at,'%d/%m/%Y')  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->filterColumn('tipo_auxiliar', function($query, $keyword) {
                $sql = "if(a.tipo = '1','PROVEEDOR',if(a.tipo = '2','TRABAJADOR',if(a.tipo = '3','CLIENTE','OTRO')))  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            ->filterColumn('estado_auxiliar', function($query, $keyword) {
                $sql = "if(a.estado = '1','ACTIVO','NO ACTIVO')  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                })
            /*->addColumn('btnActions','tipo-cambio.partials.actions')
            ->rawColumns(['btnActions'])*/
            ->toJson();
    }

    public function create($proyecto_id){
        $proyecto = Proyectos::where('id',$proyecto_id)->first();
        return view('plandecuentasauxiliares.create',compact('proyecto'));
    }

    public function store(Request $request){
        $request->validate([
           'tipo' => 'required',
           'auxiliar' => 'required'
        ]);

        $datos = new PlanCuentasAuxiliares();
        $datos->proyecto_id = $request->proyecto_id;
        $datos->tipo = $request->tipo;
        $datos->nombre = strtoupper($request->auxiliar);
        $datos->estado = 1;
        $datos->save();

        return redirect()->route('plandecuentas.index')->with('message','Se agrego un nuevo auxiliar...');
    }

    public function editar($id){
        $datos = PlanCuentas::find($id);
        if($datos->cuenta_detalle == 1){
            $cuenta_detalle = "Si";
        }else{
            $cuenta_detalle = "No";
        }
        if($datos->cheque == 1){
            $cheque = "Si";
        }else{
            $cheque = "No";
        }
        $parent = PlanCuentas::find($datos->parent_id);
        return view('plandecuentas.edit',compact('datos','parent','cuenta_detalle','cheque'));
    }

    public function update(Request $request){
        
        $datos = PlanCuentas::find($request->plancuenta_id);
        $datos->nombre = $request->nombre_dependiente;
        $datos->descripcion = $request->descripcion;
        $datos->update();
        return redirect()->route('plandecuentas.index')->with('info','Plan de cuenta modificado con exito...');
    }

    public function ajaxSeleccionar($id){
        $plandecuenta = PlanCuentas::find($id);
        $plandecuentaParent = PlanCuentas::find($plandecuenta->parent_id);
        $codigo_padre = 0;
        $cuenta_detalle = "NO";
        $cheque = "NO";
        if($plandecuentaParent != null){
            $codigo_padre = $plandecuentaParent->codigo;
            if($plandecuenta->cuenta_detalle == '1'){
                $cuenta_detalle = "SI";
            }
            if($plandecuenta->cheque == '1'){
                $cheque = "SI";
            }
        }
        if($plandecuenta->count()>0){
            return response()->json([
                'id'=>$plandecuenta->id,
                'codigo_padre' => $codigo_padre,
                'codigo'=>$plandecuenta->codigo,
                'nombre'=>$plandecuenta->nombre,
                'parent_id'=>$plandecuenta->parent_id,
                'descripcion'=>$plandecuenta->descripcion,
                'cuenta_detalle'=>$cuenta_detalle,
                'cheque'=>$cheque,
            ]);
        }
        return response()->json(['error'=>'Algo Salio Mal']);
    }
}
