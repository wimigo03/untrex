<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;

//require_once __DIR__ . '/admin.php';

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\TipoCambioController;
use App\Http\Controllers\Admin\PlandecuentasController;
use App\Http\Controllers\Admin\PlandecuentasAuxiliaresController;
use App\Http\Controllers\Admin\FacturasController;
use App\Http\Controllers\Admin\BalanceAperturaController;
use App\Http\Controllers\Admin\BalanceAperturaFController;
use App\Http\Controllers\Admin\ComprobantesController;
use App\Http\Controllers\Admin\ComprobantesDetalleController;
use App\Http\Controllers\Admin\ProveedorController;
use App\Http\Controllers\Admin\ComprobantesFiscalesController;
use App\Http\Controllers\Admin\ComprobantesFiscalesDetalleController;
use App\Http\Controllers\Admin\EstadoResultadoController;
use App\Http\Controllers\Admin\EstadoResultadoFController;
use App\Http\Controllers\Admin\BalanceGeneralController;
use App\Http\Controllers\Admin\BalanceGeneralFController;
use App\Http\Controllers\Admin\LibroMayorPorCuentaController;
use App\Http\Controllers\Admin\LibroMayorFPorCuentaController;
use App\Http\Controllers\Admin\LibroMayorFDe1a99Controller;
use App\Http\Controllers\Admin\LibroMayorDe1a99Controller;
use App\Http\Controllers\Admin\LibroBancoFController;
use App\Http\Controllers\Admin\LibroBancoController;
use App\Http\Controllers\Admin\LibroMayorPorAuxiliarController;
use App\Http\Controllers\Admin\ConsorciosController;
use App\Http\Controllers\Admin\LibroDiarioController;
use App\Http\Controllers\Admin\LibroDiarioFController;

Route::get('/', function () {
    return view('/');
});

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/store', [LoginController::class, 'store'])->name('store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuario.index');
    Route::get('/usuarios/search', [UsuarioController::class, 'search'])->name('usuario.search');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuario.create');
    Route::post('/usuarios/store', [UsuarioController::class, 'store'])->name('usuario.store');
    Route::get('/usuarios/editar/{id}', [UsuarioController::class, 'editar'])->name('usuario.editar');
    Route::post('/usuarios/update', [UsuarioController::class, 'update'])->name('usuario.update');
    Route::get('/usuarios/proyectos/{proyecto_id}', [UsuarioController::class, 'proyectos'])->name('usuario.proyecto.index');
    Route::get('/usuarios/proyectos/baja/{id}', [UsuarioController::class, 'bajaProyecto'])->name('usuario.proyecto.baja');
    Route::get('/usuarios/proyectos/alta/{id}', [UsuarioController::class, 'altaProyecto'])->name('usuario.proyecto.alta');
    Route::get('/usuarios/proyectos/search/{proyecto_id}', [UsuarioController::class, 'proyectosSearch'])->name('usuario.proyecto.search');
    Route::get('/usuarios/proyectos/create/{proyecto_id}', [UsuarioController::class, 'proyectosCreate'])->name('usuario.proyecto.create');
    Route::post('/usuarios/proyectos/store', [UsuarioController::class, 'proyectosStore'])->name('usuario.proyecto.store');

    //Tipo de cambio
    Route::post('/tipo-cambio/update', [TipoCambioController::class, 'update'])->name('tipo_cambio.update');
    Route::get('/tipo-cambio/editar/{id}', [TipoCambioController::class, 'editar'])->name('tipo_cambio.editar');
    Route::post('/tipo-cambio/store', [TipoCambioController::class, 'store'])->name('tipo_cambio.store');
    Route::get('/tipo-cambio/create', [TipoCambioController::class, 'create'])->name('tipo_cambio.create');
    Route::get('/tipo-cambio/indexAjax', [TipoCambioController::class, 'indexAjax'])->name('tipo_cambio.indexAjax');
    Route::post('/tipo-cambio/search', [TipoCambioController::class, 'search'])->name('tipo_cambio.search');
    Route::get('/tipo-cambio', [TipoCambioController::class, 'index'])->name('tipo_cambio.index');

    //Plan de cuentas
    Route::get('/plandecuentas/excel', [PlandecuentasController::class, 'excel'])->name('plandecuentas.excel');
    Route::post('/plandecuentas/store/editar', [PlandecuentasController::class, 'update'])->name('store_editar_dependiente');
    Route::post('/plandecuentas/store', [PlandecuentasController::class, 'store'])->name('store_dependiente');
    Route::get('/plandecuentas/cargar/{id}', [PlandecuentasController::class, 'ajaxSeleccionar'])->name('seleccionar-cargo');
    Route::post('/plandecuentas/editar', [PlandecuentasController::class, 'editar'])->name('plandecuentas.editar_dependiente');
    Route::post('/plandecuentas/create', [PlandecuentasController::class, 'create'])->name('plandecuentas.create_dependiente');
    Route::get('/plandecuentas/get-selected-data/{id}',[PlandecuentasController::class, 'getSelectedData'])->name('plandecuentas.get-selected-data');
    Route::get('/plandecuentas/search', [PlandecuentasController::class, 'search'])->name('plandecuentas.search');
    Route::get('/plandecuentas', [PlandecuentasController::class, 'index'])->name('plandecuentas.index');

    //Proveedores
    Route::post('/proveedor/update', [ProveedorController::class, 'update'])->name('proveedor.update');
    Route::get('/proveedor/editar/{proveedor_id}', [ProveedorController::class, 'editar'])->name('proveedor.editar');
    Route::post('/proveedor/store', [ProveedorController::class, 'store'])->name('proveedor.store');
    Route::get('/proveedor/create', [ProveedorController::class, 'create'])->name('proveedor.create');
    route::post('/proveedor/search', [ProveedorController::class, 'search'])->name('proveedor.search');
    route::get('/proveedor/indexAjax', [ProveedorController::class, 'indexAjax'])->name('proveedor.indexAjax');
    route::get('/proveedor/index', [ProveedorController::class, 'index'])->name('proveedor.index');

    //Facturas
    Route::post('/facturaComprobante/store_factura', [FacturasController::class, 'store_factura'])->name('facturas.comprobante.store.factura');
    Route::get('/facturaComprobante/delete/{factura_id}', [FacturasController::class, 'delete'])->name('facturas.comprobante.delete');
    Route::post('/facturaComprobante/store', [FacturasController::class, 'store'])->name('facturas.comprobante.store');
    Route::get('/facturaComprobante/get_proveedor/{id}', [FacturasController::class, 'getProveedor']);
    Route::get('/facturaComprobante/{comprobante_id}', [FacturasController::class, 'comprobanteCreate'])->name('facturas.comprobante.create');
    Route::get('/facturas/delete/{factura_id}', [FacturasController::class, 'delete'])->name('facturas.delete');
    Route::post('/facturas/store', [FacturasController::class, 'store'])->name('facturas.store');
    Route::get('/facturas/get_proveedor/{id}', [FacturasController::class, 'getProveedor']);
    Route::get('/facturas/create/{id}', [FacturasController::class, 'create'])->name('facturas.create');
    Route::post('/facturas/search', [FacturasController::class, 'search'])->name('facturas.search');
    Route::get('/facturas/indexAjax', [FacturasController::class, 'indexAjax'])->name('facturas.indexAjax');
    Route::get('/facturas/index', [FacturasController::class, 'index'])->name('facturas.index');

    //Balance General Base I
    route::post('/balancegeneral/excel', [BalanceGeneralController::class, 'excel'])->name('balancegeneral.excel');
    route::post('/balancegeneral/pdf', [BalanceGeneralController::class, 'pdf'])->name('balancegeneral.pdf');
    route::post('/balancegeneral/search', [BalanceGeneralController::class, 'search'])->name('balancegeneral.search');
    route::get('/balancegeneral/index/{proyecto_id}', [BalanceGeneralController::class, 'index'])->name('balancegeneral.index');
    route::get('/balancegeneral/proyectos', [BalanceGeneralController::class, 'proyectos'])->name('balancegeneral.proyectos');

    //Balance General Base II
    route::post('/balancegeneralf/excel', [BalanceGeneralFController::class, 'excel'])->name('balancegeneralf.excel');
    route::post('/balancegeneralf/pdf', [BalanceGeneralFController::class, 'pdf'])->name('balancegeneralf.pdf');
    route::post('/balancegeneralf/search', [BalanceGeneralFController::class, 'search'])->name('balancegeneralf.search');
    route::get('/balancegeneralf/index/{proyecto_id}', [BalanceGeneralFController::class, 'index'])->name('balancegeneralf.index');
    route::get('/balancegeneralf/proyectos', [BalanceGeneralFController::class, 'proyectos'])->name('balancegeneralf.proyectos');

    //Balance de apertura Base I
    Route::get('/balanceapertura/aprobar/{balance_apertura_id}', [BalanceAperturaController::class, 'aprobar'])->name('balanceapertura.aprobar');
    Route::post('/balanceapertura/update', [BalanceAperturaController::class, 'update'])->name('balanceapertura.update');
    Route::get('/balanceapertura/editar/{balance_apertura_id}', [BalanceAperturaController::class, 'editar'])->name('balanceapertura.editar');
    Route::post('/balanceapertura/store', [BalanceAperturaController::class, 'store'])->name('balanceapertura.store');
    Route::get('/balanceapertura/create/{proyecto_id}', [BalanceAperturaController::class, 'create'])->name('balanceapertura.create');
    route::post('/balanceapertura/search', [BalanceAperturaController::class, 'search'])->name('balanceapertura.search');
    route::get('/balanceapertura/index/{proyecto_id}', [BalanceAperturaController::class, 'index'])->name('balanceapertura.index');
    route::get('/balanceapertura/proyectos', [BalanceAperturaController::class, 'proyectos'])->name('balanceapertura.proyectos');

    //Balance de apertura Base II
    Route::get('/balanceaperturaf/aprobar/{balance_apertura_id}', [BalanceAperturaFController::class, 'aprobar'])->name('balanceaperturaf.aprobar');
    Route::post('/balanceaperturaf/update', [BalanceAperturaFController::class, 'update'])->name('balanceaperturaf.update');
    Route::get('/balanceaperturaf/editar/{balance_apertura_id}', [BalanceAperturaFController::class, 'editar'])->name('balanceaperturaf.editar');
    Route::post('/balanceaperturaf/store', [BalanceAperturaFController::class, 'store'])->name('balanceaperturaf.store');
    Route::get('/balanceaperturaf/create/{proyecto_id}', [BalanceAperturaFController::class, 'create'])->name('balanceaperturaf.create');
    route::post('/balanceaperturaf/search', [BalanceAperturaFController::class, 'search'])->name('balanceaperturaf.search');
    route::get('/balanceaperturaf/index/{proyecto_id}', [BalanceAperturaFController::class, 'index'])->name('balanceaperturaf.index');
    route::get('/balanceaperturaf/proyectos', [BalanceAperturaFController::class, 'proyectos'])->name('balanceaperturaf.proyectos');

    //Comprobantes
    Route::get('/comprobantes/pdf/{comprobante_id}', [ComprobantesController::class, 'pdf'])->name('comprobantes.pdf');
    Route::get('/comprobantes/rechazar/{comprobante_id}', [ComprobantesController::class, 'rechazar'])->name('comprobantes.rechazar');
    Route::get('/comprobantes/aprobar/{comprobante_id}', [ComprobantesController::class, 'aprobar'])->name('comprobantes.aprobar');
    Route::get('/comprobantes/show/{comprobante_id}', [ComprobantesController::class, 'show'])->name('comprobantes.show');
    Route::post('/comprobantes/update', [ComprobantesController::class, 'update'])->name('comprobantes.update');
    Route::get('/comprobantes/editar/{comprobante_id}', [ComprobantesController::class, 'editar'])->name('comprobantes.editar');
    Route::post('/comprobantes/store', [ComprobantesController::class, 'store'])->name('comprobantes.store');
    Route::get('/comprobantes/create', [ComprobantesController::class, 'create'])->name('comprobantes.create');
    Route::post('/comprobantes/search', [ComprobantesController::class, 'search'])->name('comprobantes.search');
    Route::get('/comprobantes/indexAjax', [ComprobantesController::class, 'indexAjax'])->name('comprobantes.indexAjax');
    Route::get('/comprobantes', [ComprobantesController::class, 'index'])->name('comprobantes.index');

    //Comprobantes Detalles
    Route::get('/comprobantesdetalles/delete/{comprobante_detalle_id}', [ComprobantesDetalleController::class, 'delete'])->name('comprobantesdetalles.delete');
    Route::post('/comprobantesdetalles/update', [ComprobantesDetalleController::class, 'update'])->name('comprobantesdetalles.update');
    Route::get('/comprobantesdetalles/editar/{comprobante_detalle_id}', [ComprobantesDetalleController::class, 'editar'])->name('comprobantesdetalles.editar');
    Route::post('/comprobantesdetalles/finalizar', [ComprobantesDetalleController::class, 'finalizar'])->name('comprobantesdetalles.finalizar');
    Route::get('/comprobantesdetalles/get_plancuenta/{id}', [ComprobantesDetalleController::class, 'getPlanCuenta']);
    Route::post('/comprobantesdetalles/insertar', [ComprobantesDetalleController::class, 'insertar'])->name('comprobantesdetalles.insertar');
    Route::get('/comprobantesdetalles/create/{comprobante}', [ComprobantesDetalleController::class, 'create'])->name('comprobantesdetalles.create');

    //Comprobantes Fiscales
    Route::get('/comprobantesfiscales/pdf/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'pdf'])->name('comprobantes.fiscales.pdf');
    Route::get('/comprobantesfiscales/rechazar/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'rechazar'])->name('comprobantes.fiscales.rechazar');
    Route::get('/comprobantesfiscales/aprobar/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'aprobar'])->name('comprobantes.fiscales.aprobar');
    Route::get('/comprobantesfiscales/show/{comprobante_fiscal_id}', [ComprobantesFiscalesController::class, 'show'])->name('comprobantes.fiscales.show');
    Route::post('/comprobantesfiscales/update', [ComprobantesFiscalesController::class, 'update'])->name('comprobantes.fiscales.update');
    Route::get('/comprobantesfiscales/editar/{comprobante_id}', [ComprobantesFiscalesController::class, 'editar'])->name('comprobantes.fiscales.editar');
    Route::post('/comprobantesfiscales/search', [ComprobantesFiscalesController::class, 'search'])->name('comprobantes.fiscales.search');
    Route::get('/comprobantesfiscales/indexAjax', [ComprobantesFiscalesController::class, 'indexAjax'])->name('comprobantes.fiscales.indexAjax');
    Route::get('/comprobantesfiscales', [ComprobantesFiscalesController::class, 'index'])->name('comprobantes.fiscales.index');

    //Comprobantes Fiscales Detalle
    Route::get('/comprobantesfiscalesdetalles/delete/{comprobante_detalle_id}', [ComprobantesFiscalesDetalleController::class, 'delete'])->name('comprobantesfiscalesdetalles.delete');
    Route::post('/comprobantesfiscalesdetalles/update', [ComprobantesFiscalesDetalleController::class, 'update'])->name('comprobantesfiscalesdetalles.update');
    Route::get('/comprobantesfiscalesdetalles/editar/{comprobante_fiscal_id}', [ComprobantesFiscalesDetalleController::class, 'editar'])->name('comprobantesfiscalesdetalles.editar');
    Route::get('/comprobantesfiscalesdetalles/edit_header/{comprobante_fiscal_id}', [ComprobantesFiscalesDetalleController::class, 'editar_encabezado'])->name('comprobantesfiscalesdetalles.edit_header');
    Route::post('/comprobantesfiscalesdetalles/finalizar', [ComprobantesFiscalesDetalleController::class, 'finalizar'])->name('comprobantesfiscalesdetalles.finalizar');
    Route::post('/comprobantesfiscalesdetalles/insertar', [ComprobantesFiscalesDetalleController::class, 'insertar'])->name('comprobantesfiscalesdetalles.insertar');
    Route::get('/comprobantesfiscalesdetalles/create/{comprobante}', [ComprobantesFiscalesDetalleController::class, 'create'])->name('comprobantesfiscalesdetalles.create');

    //Estado de resultados Base I
    route::post('/estadoresultado/excel', [EstadoResultadoController::class, 'excel'])->name('estadoresultado.excel');
    route::post('/estadoresultado/pdf', [EstadoResultadoController::class, 'pdf'])->name('estadoresultado.pdf');
    route::post('/estadoresultado/search', [EstadoResultadoController::class, 'search'])->name('estadoresultado.search');
    route::get('/estadoresultado/index/{proyecto_id}', [EstadoResultadoController::class, 'index'])->name('estadoresultado.index');
    route::get('/estadoresultado/proyectos', [EstadoResultadoController::class, 'proyectos'])->name('estadoresultado.proyectos');

    //Estado de resultados Base II
    route::post('/estadoresultadof/excel', [EstadoResultadoFController::class, 'excel'])->name('estadoresultadof.excel');
    route::post('/estadoresultadof/pdf', [EstadoResultadoFController::class, 'pdf'])->name('estadoresultadof.pdf');
    route::post('/estadoresultadof/search', [EstadoResultadoFController::class, 'search'])->name('estadoresultadof.search');
    route::get('/estadoresultadof/index/{proyecto_id}', [EstadoResultadoFController::class, 'index'])->name('estadoresultadof.index');
    route::get('/estadoresultadof/proyectos', [EstadoResultadoFController::class, 'proyectos'])->name('estadoresultadof.proyectos');

    //Libro mayor por cuenta Base I
    Route::get('libromayor/porcuenta/auxiliarExcel2/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat/{dat6}', [LibroMayorPorCuentaController::class, 'auxiliarExcel2'])->name('libromayor.porcuenta.auxiliarExcel2');
    Route::get('libromayor/porcuenta/auxiliarExcel1/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorPorCuentaController::class, 'auxiliarExcel1'])->name('libromayor.porcuenta.auxiliarExcel1');
    Route::get('libromayor/porcuenta/auxiliarpdf2/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat/{dat6}', [LibroMayorPorCuentaController::class, 'auxiliarPdf2'])->name('libromayor.porcuenta.auxiliarPdf2');
    Route::get('libromayor/porcuenta/auxiliarpdf1/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorPorCuentaController::class, 'auxiliarPdf1'])->name('libromayor.porcuenta.auxiliarPdf1');
    Route::get('libromayor/porcuenta/generalExcel/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorPorCuentaController::class, 'generalExcel'])->name('libromayor.porcuenta.generalExcel');
    Route::get('libromayor/porcuenta/generalpdf/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorPorCuentaController::class, 'generalPdf'])->name('libromayor.porcuenta.generalPdf');
    Route::get('/libromayor/porcuenta/seleccionar', [LibroMayorPorCuentaController::class, 'seleccionar']);
    Route::post('/libromayor/porcuenta/findauxiliar', [LibroMayorPorCuentaController::class, 'findauxiliar'])->name('libromayor.porcuenta.findauxiliar');
    Route::post('/libromayor/porcuenta/search', [LibroMayorPorCuentaController::class, 'search'])->name('libromayor.porcuenta.search');
    Route::get('/libromayor/porcuenta/index', [LibroMayorPorCuentaController::class, 'index'])->name('libromayor.porcuenta.index');

    //Libro mayor por cuenta Base II
    Route::get('libromayorf/porcuenta/auxiliarExcel2/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat/{dat6}', [LibroMayorFPorCuentaController::class, 'auxiliarExcel2'])->name('libromayorf.porcuenta.auxiliarExcel2');
    Route::get('libromayorf/porcuenta/auxiliarExcel1/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorFPorCuentaController::class, 'auxiliarExcel1'])->name('libromayorf.porcuenta.auxiliarExcel1');
    Route::get('libromayorf/porcuenta/auxiliarpdf2/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat/{dat6}', [LibroMayorFPorCuentaController::class, 'auxiliarPdf2'])->name('libromayorf.porcuenta.auxiliarPdf2');
    Route::get('libromayorf/porcuenta/auxiliarpdf1/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorFPorCuentaController::class, 'auxiliarPdf1'])->name('libromayorf.porcuenta.auxiliarPdf1');
    Route::get('libromayorf/porcuenta/generalExcel/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorFPorCuentaController::class, 'generalExcel'])->name('libromayorf.porcuenta.generalExcel');
    Route::get('libromayorf/porcuenta/generalpdf/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}', [LibroMayorFPorCuentaController::class, 'generalPdf'])->name('libromayorf.porcuenta.generalPdf');
    Route::get('/libromayorf/porcuenta/seleccionar', [LibroMayorFPorCuentaController::class, 'seleccionar']);
    Route::post('/libromayorf/porcuenta/findauxiliar', [LibroMayorFPorCuentaController::class, 'findauxiliar'])->name('libromayorf.porcuenta.findauxiliar');
    Route::post('/libromayorf/porcuenta/search', [LibroMayorFPorCuentaController::class, 'search'])->name('libromayorf.porcuenta.search');
    Route::get('/libromayorf/porcuenta/index', [LibroMayorFPorCuentaController::class, 'index'])->name('libromayorf.porcuenta.index');

    //Libro mayor por cuenta Base I
    Route::get('libromayor/de1a99/excel/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroMayorDe1a99Controller::class, 'excel'])->name('libromayor.de1a99.excel');
    Route::get('libromayor/de1a99/pdf/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroMayorDe1a99Controller::class, 'pdf'])->name('libromayor.de1a99.pdf');
    Route::get('/libromayor/de1a99/seleccionar', [LibroMayorDe1a99Controller::class, 'seleccionar']);
    Route::post('/libromayor/de1a99/search', [LibroMayorDe1a99Controller::class, 'search'])->name('libromayor.de1a99.search');
    Route::get('/libromayor/de1a99/index', [LibroMayorDe1a99Controller::class, 'index'])->name('libromayor.de1a99.index');

    //Libro mayor por cuenta Base II
    Route::get('libromayorf/de1a99/excel/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroMayorFDe1a99Controller::class, 'excel'])->name('libromayorf.de1a99.excel');
    Route::get('libromayorf/de1a99/pdf/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroMayorFDe1a99Controller::class, 'pdf'])->name('libromayorf.de1a99.pdf');
    Route::get('/libromayorf/de1a99/seleccionar', [LibroMayorFDe1a99Controller::class, 'seleccionar']);
    Route::post('/libromayorf/de1a99/search', [LibroMayorFDe1a99Controller::class, 'search'])->name('libromayorf.de1a99.search');
    Route::get('/libromayorf/de1a99/index', [LibroMayorFDe1a99Controller::class, 'index'])->name('libromayorf.de1a99.index');

    //Libro banco
    route::get('/librobanco/excel', [LibroBancoController::class, 'excel'])->name('librobanco.excel');
    Route::get('librobanco/pdf3/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroBancoController::class, 'pdf3'])->name('librobanco.pdf3');
    Route::get('librobanco/pdf2/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroBancoController::class, 'pdf2'])->name('librobanco.pdf2');
    Route::get('librobanco/pdf1/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroBancoController::class, 'pdf1'])->name('librobanco.pdf1');
    route::get('/librobanco/search', [LibroBancoController::class, 'search'])->name('librobanco.search');
    Route::get('/librobanco/seleccionar', [LibroBancoController::class, 'seleccionar']);
    route::get('/librobanco/index', [LibroBancoController::class, 'index'])->name('librobanco.index');

    //Libro banco F
    route::get('/librobancof/excel', [LibroBancoFController::class, 'excel'])->name('librobancof.excel');
    Route::get('librobancof/pdf3/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroBancoFController::class, 'pdf3'])->name('librobancof.pdf3');
    Route::get('librobancof/pdf2/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroBancoFController::class, 'pdf2'])->name('librobancof.pdf2');
    Route::get('librobancof/pdf1/dat1/{dat1}/dat2/{dat2}/dat3/{dat3}/dat4/{dat4}/dat5/{dat5}/dat6/{dat6}', [LibroBancoFController::class, 'pdf1'])->name('librobancof.pdf1');
    route::get('/librobancof/search', [LibroBancoFController::class, 'search'])->name('librobancof.search');
    Route::get('/librobancof/seleccionar', [LibroBancoFController::class, 'seleccionar']);
    route::get('/librobancof/index', [LibroBancoFController::class, 'index'])->name('librobancof.index');

    //Plan de cuentas auxiliares
    Route::post('/plandecuentasauxiliares/store', [PlandecuentasAuxiliaresController::class, 'store'])->name('plandecuentasauxiliares.store');
    Route::get('/plandecuentasauxiliares/create/{proyecto_id}', [PlandecuentasAuxiliaresController::class, 'create'])->name('plandecuentasauxiliares.create');
    Route::get('/plandecuentasauxiliares/indexAjax/{proyecto_id}', [PlandecuentasAuxiliaresController::class, 'indexAjax'])->name('plandecuentasauxiliares.indexAjax');
    Route::get('/plandecuentasauxiliares/{proyecto_id}', [PlandecuentasAuxiliaresController::class, 'index'])->name('plandecuentasauxiliares.index');

    //Consorcios
    Route::get('/consorcio/socios/{consorcio_id}', [ConsorciosController::class, 'socios'])->name('consorcios.socios');
    Route::get('/consorcio/proyectos/{consorcio_id}', [ConsorciosController::class, 'proyectos'])->name('consorcios.proyectos');
    Route::get('/consorcio/create', [ConsorciosController::class, 'create'])->name('consorcios.create');
    Route::get('/consorcio/index', [ConsorciosController::class, 'index'])->name('consorcios.index');

    //Libro mayor por auxiliar
    route::get('/libromayor/porauxiliar/index', [LibroMayorPorAuxiliarController::class, 'index'])->name('libromayor.porauxiliar.index');

    //Libro diario 1
    route::get('/librodiario/excel', [LibroDiarioController::class, 'excel'])->name('librodiario.excel');
    route::get('/librodiario/search', [LibroDiarioController::class, 'search'])->name('librodiario.search');
    route::get('/librodiario/index', [LibroDiarioController::class, 'index'])->name('librodiario.index');

    //Libro diario 2
    route::get('/librodiariof/excel', [LibroDiarioFController::class, 'excel'])->name('librodiariof.excel');
    route::get('/librodiariof/search', [LibroDiarioFController::class, 'search'])->name('librodiariof.search');
    route::get('/librodiariof/index', [LibroDiarioFController::class, 'index'])->name('librodiariof.index');
});
