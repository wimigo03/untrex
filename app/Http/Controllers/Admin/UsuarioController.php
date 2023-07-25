<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TipoCambio;
use App\Models\User;
use App\Socios;
use App\Consorcios;
use App\Proyectos;
use App\UserProyectos;
use DB;

class UsuarioController extends Controller
{
    public function index(){
        if(auth()->user()->id != 1){
            return back();
        }
        $socios = Socios::pluck('nombre','id');
        $usuarios = User::where('id','!=',1)->orderBy('id','desc')->paginate(15);
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

    public function create(){
        if(auth()->user()->id != 1){
            return back();
        }
        $consorcios = Consorcios::where('estado','1')->pluck('nombre','id');
        $socios = Socios::where('consorcio_id','!=',null)->pluck('nombre','id');
        return view('usuarios.create',compact('consorcios','socios'));
    }

    public function store(Request $request){
        $request->validate([
            'consorcio' => 'required',
            'socio' => 'required',
            'name' => 'required',
            'lastname' => 'required',
            'n_carnet' => 'required|string|max:15'
        ]);
        
        $usuario = new User();
        $usuario->consorcio_id = $request->consorcio;
        $usuario->socio_id = $request->socio;
        $usuario->name = $request->name;
        $usuario->lastname = $request->lastname;
        $usuario->n_carnet = $request->n_carnet;
        $usuario->email = isset($request->email) ? $request->email : '';
        $usuario->username = substr($request->name, 0, 1) . substr($request->lastname, 0, 3) . User::get()->count();
        $usuario->password = bcrypt('1234554321');
        $usuario->estado = 1;
        $usuario->save();

        return redirect()->route('usuario.index')->with('info','Informacion de usuario actualizadas...');
    }

    public function editar($id){
        if(auth()->user()->id != 1){
            return back();
        }
        $usuario = User::where('id',$id)->first();
        return view('usuarios.editar',compact('usuario'));
    }

    public function update(Request $request){
        $request->validate([
            //'username' => 'required|unique:users,username|max:50',
            'name' => 'required',
            'lastname' => 'required',
            'n_carnet' => 'required|string|max:15'
        ]);
        
        $usuario = User::find($request->user_id);
        $usuario->name = $request->name;
        $usuario->lastname = $request->lastname;
        $usuario->email = $request->email;
        if($request->password != null){
            $usuario->password = bcrypt($request->password);
        }
        $usuario->estado = $request->estado;
        $usuario->update();

        return redirect()->route('usuario.index')->with('info','Informacion de usuario actualizadas...');
    }

    public function proyectos($proyecto_id){
        if(auth()->user()->id != 1){
            return back();
        }
        $usuarios = User::where('id','!=',1)->orderBy('id','desc')->pluck('username','id');
        $users_proyectos = UserProyectos::where('user_id','!=',1)->where('proyecto_id',$proyecto_id)->paginate(15);
        $proyecto = Proyectos::find($proyecto_id);
        
        return view('usuarios.proyectos-index',compact('usuarios','users_proyectos','proyecto'));
    }

    public function proyectosSearch($proyecto_id, Request $request){
        if(auth()->user()->id != 1){
            return back();
        }
        
        $usuarios = User::where('id','!=',1)->orderBy('id','desc')->pluck('username','id');
        $users_proyectos = UserProyectos::where('user_id','!=',1)
                                        ->byUser($request->usuario)
                                        ->byEstado($request->estado)
                                        ->paginate(15);
        
        return view('usuarios.proyectos-index',compact('usuarios','users_proyectos','proyecto_id'));
    }

    public function proyectosCreate($proyecto_id){
        if(auth()->user()->id != 1){
            return back();
        }
        $usuarios = User::where('id','!=',1)->orderBy('id','desc')->pluck('username','id');

        return view('usuarios.proyectos-create',compact('usuarios','proyecto_id'));
    }

    public function proyectosStore(Request $request){
        $request->validate([
            'usuario' => 'required',
            'proyecto_id' => 'required'
        ]);
        if(auth()->user()->id != 1){
            return back();
        }
        $user_proyecto = UserProyectos::where('user_id',$request->usuario)->where('proyecto_id',$request->proyecto_id)->first();
        if($user_proyecto != null){
            return redirect()->route('usuario.proyecto.index',$request->proyecto_id)->with('danger','Usuario NO añadido, ya existe en el proyecto...');
        }
        
        $user_proyecto = new UserProyectos();
        $user_proyecto->user_id = $request->usuario;
        $user_proyecto->proyecto_id = $request->proyecto_id;
        return redirect()->route('usuario.proyecto.index',$request->proyecto_id)->with('info','Usuario añadido...');
    }

    public function bajaProyecto($id){
        if(auth()->user()->id != 1){
            return back();
        }
        $users_proyecto = UserProyectos::find($id);
        $users_proyecto->estado = 0;
        $users_proyecto->update();
        return back();
    }

    public function altaProyecto($id){
        if(auth()->user()->id != 1){
            return back();
        }
        $users_proyecto = UserProyectos::find($id);
        $users_proyecto->estado = 1;
        $users_proyecto->update();
        return back();
    }
}
