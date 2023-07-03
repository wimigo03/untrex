<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class LoginController extends Controller
{   
    /*protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/


    public function index(){
        return view('login');
    }

    public function store(){
        $credentials = $this->validate(request(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        $credentials['estado'] = 1;
        $singin = Auth::attempt($credentials);
        if (!$singin) {
            $this->logout();
            return back()
                ->withErrors(['username' => trans('auth.failed')])
                ->withInput(request(['username', 'password']));
        }
        /*dd("ok");
        if($credentials['password'] == "123456" && $user->accesos == 4){
            $this->logout();
            return back()->with('msg-danger', 'Su acceso fue cancelado porque no cambio su contraseña en 3 sesiones por favor comunicarse con el administrador...');
        }*/
        //dd("ok");
        return redirect()->to('/home');
    } 
    
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
