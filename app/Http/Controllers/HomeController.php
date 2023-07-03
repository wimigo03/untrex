<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function msj_error(){
        return '[ERROR - RASTREANDO... USTED INTENTA ACCEDER A UN LUGAR QUE NO EXISTE. POR FAVOR ESPERE SENTADO. LA POLICIA LLEGA EN UNOS MINUTOS]';
    }
}
