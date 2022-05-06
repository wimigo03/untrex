<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PlandecuentasController;
use App\Http\Controllers\Admin\CotizacionesController;
use App\Http\Controllers\Admin\ComprobantesController;
use App\Http\Controllers\Admin\ComprobantesDetalleController;

//Comprobantes Detalles
route::get('/comprobantesdetalles/create/{comprobante}', [ComprobantesDetalleController::class, 'create'])->name('comprobantesdetalles.create');
//Comprobantes
Route::post('/comprobantes/store', [ComprobantesController::class, 'store'])->name('comprobantes.store');
route::get('/comprobantes/create', [ComprobantesController::class, 'create'])->name('comprobantes.create');
route::get('/comprobantes', [ComprobantesController::class, 'index'])->name('comprobantes.index');
//Cotizaciones
//route::get('/cotizacionesAjax', [CotizacionesController::class, 'indexAjax'])->name('cotizaciones.indexAjax');
Route::post('/cotizaciones/search', [CotizacionesController::class, 'search'])->name('cotizaciones.search');
Route::post('/cotizaciones/store', [CotizacionesController::class, 'store'])->name('cotizaciones.store');
route::get('/cotizaciones/create', [CotizacionesController::class, 'create'])->name('cotizaciones.create');
route::get('/cotizaciones', [CotizacionesController::class, 'index'])->name('cotizaciones.index');
//Plan de cuentas
Route::post('/plandecuentas/store/editar', [PlandecuentasController::class, 'update'])->name('store_editar_dependiente');
Route::post('/plandecuentas/store', [PlandecuentasController::class, 'store'])->name('store_dependiente');
Route::get('/plandecuentas/cargar/{id}', [PlandecuentasController::class, 'ajaxSeleccionar'])->name('seleccionar-cargo');
route::get('/plandecuentas/editar/{id}', [PlandecuentasController::class, 'editar'])->name('editar_dependiente');
route::get('/plandecuentas/editar/{id}', [PlandecuentasController::class, 'editar'])->name('editar_dependiente');
route::get('/plandecuentas/create/{id}', [PlandecuentasController::class, 'create'])->name('create_dependiente');
route::get('/plandecuentas', [PlandecuentasController::class, 'index'])->name('plandecuentas.index');
route::get('', [HomeController::class, 'index']);

