<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PlandecuentasController;

Route::post('/plandecuentas/store/editar', [PlandecuentasController::class, 'update'])->name('store_editar_dependiente');
Route::post('/plandecuentas/store', [PlandecuentasController::class, 'store'])->name('store_dependiente');
Route::get('/plandecuentas/cargar/{id}', [PlandecuentasController::class, 'ajaxSeleccionar'])->name('seleccionar-cargo');
route::get('/plandecuentas/editar/{id}', [PlandecuentasController::class, 'editar'])->name('editar_dependiente');
route::get('/plandecuentas/editar/{id}', [PlandecuentasController::class, 'editar'])->name('editar_dependiente');
route::get('/plandecuentas/create/{id}', [PlandecuentasController::class, 'create'])->name('create_dependiente');
route::get('/plandecuentas', [PlandecuentasController::class, 'index'])->name('plandecuentas.index');
route::get('', [HomeController::class, 'index']);

