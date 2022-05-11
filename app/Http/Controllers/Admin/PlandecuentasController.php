<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PlanCuentas;
use DB;

class PlandecuentasController extends Controller
{
    public function index(){

        $plancuentas = PlanCuentas::where('parent_id', '0')->where('estado',1)->orderBy('id', 'asc')->get();
		$html = $this->arbol($plancuentas);
        return view('plandecuentas.index',compact('html'));
    }

    public function arbol($plancuenta, $state = 1){
        $numero = 1;
        $html2 = "";
        foreach($plancuenta as $row) {
            $html2 .= '<ul id="menu-group-'.$numero.'" class="nav menu"> ';
            $html2 .= '<li class="item-'.$numero.' deeper parent">';
            $html2 .= '<a class="" href="#">
                        <span data-value="'.$row->id.'" data-codigo="'.$row->codigo.'" data-toggle="collapse" data-parent="#menu-group-'.$numero.'" href="#sub-item-'.$numero;
            if($state){
                $html2 .= '-1" class="sign"><i class="icon-plus icon-white"></i></span>';
            }else{
                $html2 .= '-1" class=""><i class="icon-plus icon-white"></i></span>';
            }
            $html2 .= '<span data-value="'.$row->id.'" data-codigo="'.$row->codigo.'" data-toggle="collapse" data-parent="#menu-group-'.$numero.'" href="#sub-item-'.$numero.'-1" class="lbl">'. $row->codigo . " " . $row->nombre.'</span>';
            $html2 .= '</a>';
            $plancuenta_tree = PlanCuentas::where('estado', '1')->where('parent_id', $row->id)->orderBy('id', 'asc')->get();
            $html2 .= $this->categoryTree2($plancuenta_tree, $row->id, $numero, 1);
            $html2 .= '</li>';
            $numero++;
            $html2 .= '</ul>';
        }
        return $html2;
    }

    public function categoryTree2($plancuenta, $parent_id = 0,$num_sub,$numero,$state = 1){
        $html = '<ul class="children nav-child unstyled small collapse" id="sub-item-'.$num_sub.'-'.$numero.'">';
        foreach($plancuenta as $row) {
            $numero++;
            if ($row->parent_id == $parent_id) {
                $html .= '<li class="item-' . $numero . ' deeper parent">';
                $html .= '<a class="" href="#">
                            <span data-value="'.$row->id.'" data-codigo="'.$row->codigo.'" data-toggle="collapse" data-parent="#menu-group-'.$numero.'" href="#sub-item-'.$num_sub.'-'.$row->id;
                if($state){
                    $html .= '" class="sign"><i class="icon-plus icon-white"></i></span>';
                }else{
                    $html .= '" class=""><i class="icon-plus icon-white"></i></span>';
                }
                $html .= '<span data-value="'.$row->id.'" data-codigo="'.$row->codigo.'" data-toggle="collapse" data-parent="#menu-group-'.$numero.'" href="#sub-item-'.$num_sub.'-'.$row->id.'" class="lbl">'. $row->codigo." ".$row->nombre.'</span>';
                $html .= '</a>';
                $plancuenta2 = PlanCuentas::where('estado', '1')->where('parent_id', $row->id)->where('deleted_at',null)->orderBy('id', 'asc')->get();
                $html .= $this->categoryTree2($plancuenta2, $row->id, $num_sub, $row->id, $html);
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public function create($id){
        if($id == "create-dependiente" || $id == null){
            return back()->with('danger','La peticion no puede ser procesada...');
        }
        $parent = PlanCuentas::find($id);
        return view('plandecuentas.create',compact('parent'));
    }

    public function store(Request $request){
        $request->validate([
           'codigo_padre' => 'required',
           'nombre_padre' => 'required',
           'nombre_dependiente' => 'required',
           'descripcion' => 'required',
           'cuenta_detalle' => 'required',
           'cheque' => 'required'  
        ]);

        $codigo = $request->codigo_padre.".".((PlanCuentas::where('parent_id', $request->parent_id)->count())+1);
        $datos = new PlanCuentas();
        $datos->nombre = strtoupper($request->nombre_dependiente);
        $datos->codigo = $codigo;
        $datos->parent_id = $request->parent_id;
        $datos->descripcion = strtoupper($request->descripcion);
        $datos->cuenta_detalle = $request->cuenta_detalle;
        $datos->cheque = $request->cheque;
        $datos->estado = 1;
        $datos->save();

        return redirect()->route('plandecuentas.index')->with('message','Se agrego un nuevo plan de cuentas...');
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
