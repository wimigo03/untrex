<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TipoCambio;
use App\Models\User;
use App\Socios;
use DB;

class UsuarioController extends Controller
{
    public function index(){
        if(auth()->user()->id != 1){
            return back();
        }
        $socios = Socios::pluck('nombre','id');
        $usuarios = User::where('id','!=',1)->paginate(15);
        return view('usuarios.index',compact('socios','usuarios'));
    }

    public function search(Request $request){
        if(auth()->user()->id != 1){
            return back();
        }
        $socios = Socios::pluck('nombre','id');
        $usuarios = User::query()
                        ->bySocio($request->socio)
                        ->byUsername($request->username)
                        ->byName($request->name)
                        ->byEmail($request->email)
                        ->where('id','!=',1)
                        ->paginate(15);
        return view('usuarios.index',compact('socios','usuarios'));
    }

    /*public function create(){
        return view('tipo-cambio.create');
    }

    public function store(Request $request){
        $request->validate([
           'fecha' => 'required',
           'dolar_oficial'=>'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'dolar_compra' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'dolar_venta' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,3})?$/',
           'ufv' => 'required|numeric|regex:/^[\d]{0,2}(\.[\d]{1,5})?$/'
        ]);
        $date_cambio = substr($request->fecha,6,4) . '-' . substr($request->fecha,3,2) . '-' . substr($request->fecha,0,2);
        $duplicado = TipoCambio::where('fecha',$date_cambio)->where('deleted_at',null)->first();
        if($duplicado != null){
            return back()->with('info', 'La fecha seleccionada ya tiene una cotizacion activa...');
        }
        $date_actual = date('Y-m-d');
        if($date_cambio > $date_actual){
            return back()->with('info', 'Las cotizaciones no pueden ser de fechas adelantadas...');
        }
        $tipo_cambio = new TipoCambio();
        $tipo_cambio->fecha = $date_cambio;
        $tipo_cambio->ufv = $request->ufv;
        $tipo_cambio->dolar_oficial = $request->dolar_oficial;
        $tipo_cambio->dolar_compra = $request->dolar_compra;
        $tipo_cambio->dolar_venta = $request->dolar_venta;
        $tipo_cambio->status = 1;
        $tipo_cambio->save();

        return redirect()->route('tipo_cambio.index')->with('message','Se agrego un nuevo tipo de cambio...');
    }*/

    public function editar($id){
        $usuario = User::where('id',$id)->first();
        return view('usuarios.editar',compact('usuario'));
    }

    public function update(Request $request){
        $request->validate([
            //'username' => 'required|unique:users,username|max:50',
            'username' => 'required|max:50',
            'name' => 'required|max:150',
            'email' => 'required|string|email|max:120',
            'password' => 'required|min:6|max:25',
            'estado' => 'required'
        ]);
        
        $usuario = User::find($request->user_id);
        $usuario->username = $request->username;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->estado = $request->estado;
        $usuario->update();

        return redirect()->route('usuario.index')->with('info','Informacion de usuario actualizadas...');
    }
}
