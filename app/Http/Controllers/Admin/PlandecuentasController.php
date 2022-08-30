<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PlanCuentas;
use App\Proyectos;
use DB;

class PlandecuentasController extends Controller
{
    public function index(){
        $proyecto_id = null;
        $plancuentas = null;
        $html = "";
        $proyectos = DB::table('user_proyectos as a')
                            ->join('proyectos as b','b.id','a.proyecto_id')
                            ->where('a.user_id',auth()->user()->id)
                            ->where('a.estado','1')
                            ->select('b.id','b.nombre')
                            ->get();
        return view('plandecuentas.index',compact('proyecto_id','plancuentas','html','proyectos'));
    }

    public function search(Request $request){
        if($request->proyecto_id != null){
            $proyecto_id = $request->proyecto_id;
            $proyectos  = Proyectos::where('estado',1)->get();
            $planCuentas = PlanCuentas::where('parent_id', '0')
                                        ->where('proyecto_id', $proyecto_id)
                                        ->where('estado',1)
                                        ->orderBy('id', 'asc')
                                        ->get();
            $html = $this->tree($planCuentas);
            return view('plandecuentas.index',compact('proyecto_id','proyectos','planCuentas','html'));
        }else{
            $proyecto_id = null;
            $proyectos  = Proyectos::where('estado',1)->get();
            $planCuentas = null;
            $html = "";
            return view('plandecuentas.index',compact('proyecto_id','proyectos','planCuentas','html'));
        }
    }

    private function tree($plancuentas){
        $tree  = '<ul id="treeview-plan-cuentas" class = "filetree" >';
        $tree .=    '<li class = "tree-view" ></li>';
        foreach ($plancuentas as $planCuenta){
           $tree .=     '<li class = "closed" >';
           $tree .=         '<span class = "folder" style="cursor: pointer;">';
           $tree .=             " (" . $planCuenta->codigo . ") " . $planCuenta->nombre;
           $tree .=         '</span>';
           $tree .=         '<input type = "hidden" class = "plan_cuenta_id" value = "' . $planCuenta->id . '">';
            if(count($planCuenta->hijos)){
                $tree .= $this->vistaHijo($planCuenta);
            }
        }
        $tree .= '<ul>';
        return $tree;
    }

    public function vistaHijo($planCuenta){
        $html ='<ul>';
        //TODO: cambiar a orden ascendete los hijos
        foreach ($planCuenta->hijos as $arr){
            if(count($arr->hijos)){
                $html .= '<li class = "closed">';
                $html .=    '<span class = "folder">';
                $html .=        " (" . $arr->codigo . ") " . $arr->nombre;
                $html .=    '</span>';
                $html .=    '<input type = "hidden" class = "plan_cuenta_id " value = "' . $arr->id . '">';                  
                $html .=    $this->vistaHijo($arr);
            }else{
                $html .= '<li>';
                $html .=    '<span class = "file" style="cursor: pointer;">';
                $html .=        " (" . $arr->codigo . ") " . $arr->nombre;
                $html .=    '</span>';                                 
                $html .=    '<input type = "hidden" class = "plan_cuenta_id " value = "' . $arr->id . '">';                  
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public function getSelectedData($id){
        $planCuenta = PlanCuentas::find($id);
        if($planCuenta != null){
            $status_code = '200';
            /*$moneda = Moneda::find($planCuenta->moneda_id);
            if($moneda != null){
                $planCuenta->moneda = $moneda->nombre;
            }*/
        }else{
            $status_code = '404';
        }
        return response()->json([
            'status_code' => $status_code,
            'data' => $planCuenta
        ]);
    }

    public function create(Request $request){
        $proyecto_id = $request->proyecto_id;
        $id = $request->crear_plan_cuenta_id;
        if($id == null){
            return back()->with('danger','La peticion no puede ser procesada...');
        }
        $parent = PlanCuentas::find($id);
        return view('plandecuentas.create',compact('parent','proyecto_id'));
    }

    public function store(Request $request){
        $request->validate([
           'codigo_padre' => 'required',
           'nombre_padre' => 'required',
           'nombre_dependiente' => 'required',
           //'descripcion' => 'required',
           'cuenta_detalle' => 'required',
           'cheque' => 'required',
           'moneda' => 'required'
        ]);

        $codigo = $request->codigo_padre.".".((PlanCuentas::where('parent_id', $request->parent_id)->count())+1);
        $datos = new PlanCuentas();
        $datos->proyecto_id = $request->proyecto_id;
        $datos->nombre = strtoupper($request->nombre_dependiente);
        $datos->codigo = $codigo;
        $datos->parent_id = $request->parent_id;
        $datos->descripcion = strtoupper($request->descripcion);
        $datos->cuenta_detalle = $request->cuenta_detalle;
        $datos->cheque = $request->cheque;
        $datos->moneda = $request->moneda;
        $datos->estado = 1;
        $datos->save();

        return redirect()->route('plandecuentas.index')->with('message','Se agrego un nuevo plan de cuentas...');
    }

    public function editar(Request $request){
        $id = $request->editar_plan_cuenta_id;
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
