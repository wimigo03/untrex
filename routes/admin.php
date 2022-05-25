<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PlandecuentasController;
use App\Http\Controllers\Admin\PlandecuentasAuxiliaresController;
use App\Http\Controllers\Admin\CotizacionesController;
use App\Http\Controllers\Admin\ComprobantesController;
use App\Http\Controllers\Admin\ComprobantesDetalleController;
use App\Http\Controllers\Admin\ComprobantesFiscalesController;
use App\Http\Controllers\Admin\ComprobantesFiscalesDetalleController;
use App\Http\Controllers\Admin\FacturasController;

//Facturas
Route::post('/facturaComprobante/store_factura', [FacturasController::class, 'store_factura'])->name('facturas.comprobante.store.factura');
Route::get('/facturaComprobante/delete/{factura_id}', [FacturasController::class, 'delete'])->name('facturas.comprobante.delete');
Route::post('/facturaComprobante/store', [FacturasController::class, 'store'])->name('facturas.comprobante.store');
Route::get('/facturaComprobante/get_proveedor/{id}', [FacturasController::class, 'getProveedor']);
Route::get('/facturaComprobante/{comprobante_id}', [FacturasController::class, 'comprobanteCreate'])->name('facturas.comprobante.create');
//Comprobantes Fiscales Detalle
Route::get('/comprobantesfiscalesdetalles/delete/{comprobante_detalle_id}', [ComprobantesFiscalesDetalleController::class, 'delete'])->name('comprobantesfiscalesdetalles.delete');
Route::post('/comprobantesfiscalesdetalles/update', [ComprobantesFiscalesDetalleController::class, 'update'])->name('comprobantesfiscalesdetalles.update');
Route::get('/comprobantesfiscalesdetalles/editar/{comprobante_fiscal_id}', [ComprobantesFiscalesDetalleController::class, 'editar'])->name('comprobantesfiscalesdetalles.editar');
Route::post('/comprobantesfiscalesdetalles/finalizar', [ComprobantesFiscalesDetalleController::class, 'finalizar'])->name('comprobantesfiscalesdetalles.finalizar');
Route::post('/comprobantesfiscalesdetalles/insertar', [ComprobantesFiscalesDetalleController::class, 'insertar'])->name('comprobantesfiscalesdetalles.insertar');
route::get('/comprobantesfiscalesdetalles/create/{comprobante}', [ComprobantesFiscalesDetalleController::class, 'create'])->name('comprobantesfiscalesdetalles.create');
//Comprobantes Fiscales
Route::get('/comprobantesfiscales/pdf/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'pdf'])->name('comprobantes.fiscales.pdf');
Route::get('/comprobantesfiscales/rechazar/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'rechazar'])->name('comprobantes.fiscales.rechazar');
Route::get('/comprobantesfiscales/aprobar/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'aprobar'])->name('comprobantes.fiscales.aprobar');
Route::get('/comprobantesfiscales/show/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'show'])->name('comprobantes.fiscales.show');
route::post('/comprobantesfiscales/search', [ComprobantesFiscalesController::class, 'search'])->name('comprobantes.fiscales.search');
route::get('/comprobantesfiscales/indexAjax', [ComprobantesFiscalesController::class, 'indexAjax'])->name('comprobantes.fiscales.indexAjax');
route::get('/comprobantesfiscales', [ComprobantesFiscalesController::class, 'index'])->name('comprobantes.fiscales.index');
//Comprobantes Detalles
Route::get('/comprobantesdetalles/delete/{comprobante_detalle_id}', [ComprobantesDetalleController::class, 'delete'])->name('comprobantesdetalles.delete');
Route::post('/comprobantesdetalles/update', [ComprobantesDetalleController::class, 'update'])->name('comprobantesdetalles.update');
Route::get('/comprobantesdetalles/editar/{comprobante_detalle_id}', [ComprobantesDetalleController::class, 'editar'])->name('comprobantesdetalles.editar');
Route::post('/comprobantesdetalles/finalizar', [ComprobantesDetalleController::class, 'finalizar'])->name('comprobantesdetalles.finalizar');
Route::get('/comprobantesdetalles/get_plancuenta/{id}', [ComprobantesDetalleController::class, 'getPlanCuenta']);
Route::post('/comprobantesdetalles/insertar', [ComprobantesDetalleController::class, 'insertar'])->name('comprobantesdetalles.insertar');
route::get('/comprobantesdetalles/create/{comprobante}', [ComprobantesDetalleController::class, 'create'])->name('comprobantesdetalles.create');
//Comprobantes
Route::get('/comprobantes/pdf/{comprobante_id}', [ComprobantesController::class, 'pdf'])->name('comprobantes.pdf');
Route::get('/comprobantes/rechazar/{comprobante_id}', [ComprobantesController::class, 'rechazar'])->name('comprobantes.rechazar');
Route::get('/comprobantes/aprobar/{comprobante_id}', [ComprobantesController::class, 'aprobar'])->name('comprobantes.aprobar');
Route::get('/comprobantes/show/{comprobante_id}', [ComprobantesController::class, 'show'])->name('comprobantes.show');
Route::post('/comprobantes/store', [ComprobantesController::class, 'store'])->name('comprobantes.store');
route::get('/comprobantes/create', [ComprobantesController::class, 'create'])->name('comprobantes.create');
route::post('/comprobantes/search', [ComprobantesController::class, 'search'])->name('comprobantes.search');
route::get('/comprobantes/indexAjax', [ComprobantesController::class, 'indexAjax'])->name('comprobantes.indexAjax');
route::get('/comprobantes', [ComprobantesController::class, 'index'])->name('comprobantes.index');
//Cotizaciones
//route::get('/cotizacionesAjax', [CotizacionesController::class, 'indexAjax'])->name('cotizaciones.indexAjax');
Route::post('/cotizaciones/search', [CotizacionesController::class, 'search'])->name('cotizaciones.search');
Route::post('/cotizaciones/store', [CotizacionesController::class, 'store'])->name('cotizaciones.store');
route::get('/cotizaciones/create', [CotizacionesController::class, 'create'])->name('cotizaciones.create');
route::get('/cotizaciones', [CotizacionesController::class, 'index'])->name('cotizaciones.index');
//Plan de cuentas auxiliares
Route::post('/plandecuentasauxiliares/store', [PlandecuentasAuxiliaresController::class, 'store'])->name('plandecuentasauxiliares.store');
route::get('/plandecuentasauxiliares/create', [PlandecuentasAuxiliaresController::class, 'create'])->name('plandecuentasauxiliares.create');
route::get('/plandecuentasauxiliares', [PlandecuentasAuxiliaresController::class, 'index'])->name('plandecuentasauxiliares.index');
//Plan de cuentas
Route::post('/plandecuentas/store/editar', [PlandecuentasController::class, 'update'])->name('store_editar_dependiente');
Route::post('/plandecuentas/store', [PlandecuentasController::class, 'store'])->name('store_dependiente');
Route::get('/plandecuentas/cargar/{id}', [PlandecuentasController::class, 'ajaxSeleccionar'])->name('seleccionar-cargo');
route::get('/plandecuentas/editar/{id}', [PlandecuentasController::class, 'editar'])->name('editar_dependiente');
route::get('/plandecuentas/editar/{id}', [PlandecuentasController::class, 'editar'])->name('editar_dependiente');
route::get('/plandecuentas/create/{id}', [PlandecuentasController::class, 'create'])->name('create_dependiente');
route::get('/plandecuentas', [PlandecuentasController::class, 'index'])->name('plandecuentas.index');
route::get('', [HomeController::class, 'index']);

