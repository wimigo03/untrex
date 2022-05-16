<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PlanCuentasAuxiliares;
use DB;

class PlandecuentasAuxiliaresController extends Controller
{
    public function index(){
        $plancuentasauxiliares = PlanCuentasAuxiliares::where('estado',1)->orderBy('id', 'desc')->paginate();
        return view('plandecuentasauxiliares.index',compact('plancuentasauxiliares'));
    }

    public function create(){
        return view('plandecuentasauxiliares.create');
    }

    public function store(Request $request){
        $request->validate([
           'tipo' => 'required',
           'auxiliar' => 'required'
        ]);

        $datos = new PlanCuentasAuxiliares();
        $datos->tipo = $request->tipo;
        $datos->nombre = strtoupper($request->auxiliar);
        $datos->estado = 1;
        $datos->save();

        return redirect()->route('plandecuentasauxiliares.index')->with('message','Se agrego un nuevo auxiliar...');
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
