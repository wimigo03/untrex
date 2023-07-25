<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Consorcios;
//use App\Proyectos;
//use App\Ciudades;
//use App\ComprobantesDetalle;
//use App\PlanCuentas;
//use App\PlanCuentasAuxiliares;
//use App\Proveedores;
//use App\Facturas;
//use App\ComprobanteFacturas;
use Carbon\Carbon;
use DB;

class ProyectosController extends Controller
{
    public function index(){dd("ok");
        if(auth()->user()->id != 1){
            return back();
        }
        $consorcios = DB::table('consorcios')->get();
        return view('consorcios.index',compact('consorcios'));
    }

    public function search(Request $request){
        dd($request->all());
        $proveedor = DB::table('proveedores as a')
                        ->where('a.razon_social','like','%' . $request->razon_social .'%')
                        ->select('a.id as proveedor_id','a.razon_social','a.nombre_comercial','a.nit','a.ciudad')
                        ->get();
    }

    public function create(){
        $anho_actual = date('Y');
        for($i=($anho_actual-2);$i<=($anho_actual+2);$i++){
            $gestion[$i] = $i;
        }
        $proyectos = Proyectos::pluck('nombre','id');
        return view('balance-apertura.create',compact('proyectos','gestion'));
    }

    public function proyectos($consorcio_id){
        if(auth()->user()->id != 1){
            return back();
        }
        $consorcio = DB::table('consorcios')->where('id',$consorcio_id)->first();
        $proyectos = DB::table('proyectos')->where('consorcio_id',$consorcio_id)->get();
        return view('consorcios.proyectos',compact('consorcio','proyectos'));
    }

    public function socios($consorcio_id){
        if(auth()->user()->id != 1){
            return back();
        }
        $consorcio = DB::table('consorcios')->where('id',$consorcio_id)->first();
        $socios = DB::table('socios')->where('consorcio_id',$consorcio_id)->get();
        return view('consorcios.socios',compact('consorcio','socios'));
    }

    public function store(Request $request){
        dd($request->all());
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //return response()->json(['message' => 'Error']);
            return back()->withInput()->with('danger','Hay un error en el sistema, por favor llamar al encargado de desarrollo...');
        }
        return redirect()->route('proveedor.index')->with('message','Se agrego un nuevo proveedor al sistema..');
    }

    /*public function editar($proveedor_id){
        $proveedor = Proveedores::where('id',$proveedor_id)->first();
        $proveedor = DB::table('proveedores as a')
                        ->where('a.id',$proveedor_id)
                        ->select('a.id as proveedor_id','a.razon_social','a.nombre_comercial','a.nit','a.ciudad_id','a.tipo','a.nro_cuenta','a.titular_cuenta',
                                    'a.banco','a.direccion','a.email','a.contacto1','a.celular1','a.fijo1','a.contacto2','a.celular2','a.fijo2','a.observaciones')
                        ->first();
        $ciudades = Ciudades::where('estado',1)->orderBy('id','asc')->pluck('nombre','id');
        return view('proveedor.editar',compact('proveedor','ciudades'));
    }*/

    /*public function update(Request $request){
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
    }*/
}
