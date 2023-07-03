<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Proveedores;
use App\Ciudades;
use App\PlanCuentasAuxiliares;
//use App\PlanCuentas;
//use App\PlanCuentasAuxiliares;
//use App\Proveedores;
//use App\Facturas;
//use App\ComprobanteFacturas;
use Carbon\Carbon;
use DB;

class ProveedorController extends Controller
{
    public function index(){
        //$proveedor = DB::table('proveedores')->whereNotIn('ciudad',[1,2,3,4,5,6,7,8,9])->get();
        return view('proveedor.index');
    }

    public function indexAjax(){
        return datatables()
            ->query(DB::table('proveedores as a')
            ->join('ciudades as b','b.id','a.ciudad_id')
            ->select('a.id as proveedor_id','a.razon_social','a.nombre_comercial','a.nit','b.nombre'))
            ->addColumn('btnActions','proveedor.partials.actions')
            ->rawColumns(['btnActions'])
            ->toJson();
    }

    public function search(Request $request){
        dd($request->all());
        $proveedor = DB::table('proveedores as a')
                        ->where('a.razon_social','like','%' . $request->razon_social .'%')
                        ->select('a.id as proveedor_id','a.razon_social','a.nombre_comercial','a.nit','a.ciudad')
                        ->get();
    }

    public function create(){
        $ciudades = Ciudades::where('estado',1)->orderBy('id','asc')->pluck('nombre','id');
        return view('proveedor.create',compact('ciudades'));
    }

    public function store(Request $request){
        $request->validate([
            'razon_social' => 'required|max:120',
            'nombre_comercial' => 'required|max:120',
            'nit' => 'required|unique:proveedores,nit|max:15',
            'ciudad' => 'required',
            'tipo' => 'required',
            //'nueva_ciudad'=> 'required_if:ciudad,10|max:120',//El campo nueva_ciudad es obligatorio a menos que ciudad esté en 10.
            'nueva_ciudad'=> 'required_if:ciudad,10|max:120',//Si la ciudad es igual a 10 entonces el campo es obligatorio
            'abreviatura' => 'required_if:ciudad,10|max:25',
            'direccion' => 'required|max:120',
            'email' => 'nullable|string|email|max:120'
        ]);
        
        DB::beginTransaction();
        try {
            if($request->ciudad == 10){
                $ciudad = new Ciudades();
                $ciudad->nombre = $request->nueva_ciudad;
                $ciudad->abreviatura = $request->abreviatura;
                $ciudad->estado = 1;
                $ciudad->save();

                $ciudad_id = $ciudad->id;
            }else{
                $ciudad_id = $request->ciudad;
            }
            $proveedor = new Proveedores();
            $proveedor->razon_social = $request->razon_social;
            $proveedor->nombre_comercial = $request->nombre_comercial;
            $proveedor->nit = $request->nit;
            $proveedor->nro_cuenta = $request->nro_cuenta;
            $proveedor->titular_cuenta = $request->titular_cuenta;
            $proveedor->banco = $request->banco;
            $proveedor->ciudad_id = $ciudad_id;
            $proveedor->direccion = $request->direccion;
            $proveedor->tipo = $request->tipo;
            $proveedor->contacto1 = $request->contacto_1;
            $proveedor->contacto2 = $request->contacto_2;
            $proveedor->celular1 = $request->celular_1;
            $proveedor->celular2 = $request->celular_2;
            $proveedor->fijo1 = $request->fijo_1;
            $proveedor->fijo2 = $request->fijo_2;
            $proveedor->email = $request->email;
            $proveedor->observaciones = $request->observaciones;
            $proveedor->status = 1;
            $proveedor->save();

            $auxiliar = new PlanCuentasAuxiliares();
            $auxiliar->proyecto_id = 1;
            $auxiliar->tipo = 1;
            $auxiliar->nombre = $request->razon_social;
            $auxiliar->reg_id = $proveedor->id;
            $auxiliar->estado = 1;
            $auxiliar->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //return response()->json(['message' => 'Error']);
            return back()->withInput()->with('danger','Hay un error en el sistema, por favor llamar al encargado de desarrollo...');
        }
        return redirect()->route('proveedor.index')->with('message','Se agrego un nuevo proveedor al sistema..');
    }

    public function editar($proveedor_id){
        $proveedor = Proveedores::where('id',$proveedor_id)->first();
        $proveedor = DB::table('proveedores as a')
                        ->where('a.id',$proveedor_id)
                        ->select('a.id as proveedor_id','a.razon_social','a.nombre_comercial','a.nit','a.ciudad_id','a.tipo','a.nro_cuenta','a.titular_cuenta',
                                    'a.banco','a.direccion','a.email','a.contacto1','a.celular1','a.fijo1','a.contacto2','a.celular2','a.fijo2','a.observaciones')
                        ->first();
        $ciudades = Ciudades::where('estado',1)->orderBy('id','asc')->pluck('nombre','id');
        return view('proveedor.editar',compact('proveedor','ciudades'));
    }

    public function update(Request $request){
        $request->validate([
            'razon_social' => 'required|max:120',
            'nombre_comercial' => 'required|max:120',
            'nit' => 'required|max:15',
            'ciudad' => 'required',
            'tipo' => 'required',
            //'nueva_ciudad'=> 'required_if:ciudad,10|max:120',//El campo nueva_ciudad es obligatorio a menos que ciudad esté en 10.
            'nueva_ciudad'=> 'required_if:ciudad,10|max:120',//Si la ciudad es igual a 10 entonces el campo es obligatorio
            'abreviatura' => 'required_if:ciudad,10|max:25',
            'direccion' => 'required|max:120',
            'email' => 'nullable|string|email|max:120'
        ]);
        //dd($request->all());
        $total_nit = Proveedores::where('nit',$request->nit)->where('status',1)->count('id');
        if($total_nit > 1){
            return back()->withInput()->with('danger','El nit que intenta introducir ya esta vinculado a otro proveedor...');
        }
        
        DB::beginTransaction();
        try {
            if($request->ciudad == 10){
                $ciudad = new Ciudades();
                $ciudad->nombre = $request->nueva_ciudad;
                $ciudad->abreviatura = $request->abreviatura;
                $ciudad->estado = 1;
                $ciudad->save();

                $ciudad_id = $ciudad->id;
            }else{
                $ciudad_id = $request->ciudad;
            }
            $proveedor = Proveedores::find($request->proveedor_id);
            $proveedor->razon_social = $request->razon_social;
            $proveedor->nombre_comercial = $request->nombre_comercial;
            $proveedor->nit = $request->nit;
            $proveedor->nro_cuenta = $request->nro_cuenta;
            $proveedor->titular_cuenta = $request->titular_cuenta;
            $proveedor->banco = $request->banco;
            $proveedor->ciudad_id = $ciudad_id;
            $proveedor->direccion = $request->direccion;
            $proveedor->tipo = $request->tipo;
            $proveedor->contacto1 = $request->contacto_1;
            $proveedor->contacto2 = $request->contacto_2;
            $proveedor->celular1 = $request->celular_1;
            $proveedor->celular2 = $request->celular_2;
            $proveedor->fijo1 = $request->fijo_1;
            $proveedor->fijo2 = $request->fijo_2;
            $proveedor->email = $request->email;
            $proveedor->observaciones = $request->observaciones;
            $proveedor->status = 1;
            $proveedor->update();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //return response()->json(['message' => 'Error']);
            return back()->withInput()->with('danger','Hay un error en el sistema, por favor llamar al encargado de desarrollo...');
        }
        return redirect()->route('proveedor.index')->with('info','Los datos del proveedor fueron modificados...');
    }
}
