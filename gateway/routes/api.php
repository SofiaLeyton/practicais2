<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\GateController;

Route::get('/ping', function () {
    return ['message' => 'Gateway working'];
});

//SEGURIDAD
Route::post('/seguridad/login', function (Request $request) {
    return app(GateController::class)->handle($request, 'seguridad', 'login');
});

Route::post('/seguridad/create_user', function (Request $request) {
    return app(GateController::class)->handle($request, 'seguridad', 'create_user');
});


// PEDIDOS
Route::post('/pedidos/crearPedido', function (Request $request) {
    return app(GateController::class)->handle($request, 'pedidos', 'crearPedido');
});
#http://localhost:9000/api/pedidos/crearPedido

Route::get('/pedidos/verPedidos', function (Request $request) {
    return app(GateController::class)->handle($request, 'pedidos', 'verPedidos');
});
#http://localhost:9000/api/pedidos/verPedidos



//PRODUCTOS
Route::post('/productos/crearProducto', function (Request $request) {
    return app(GateController::class)->handle($request, 'productos', 'crearProducto');
});
#http://localhost:9000/api/productos/crearProducto

Route::delete('/productos/eliminarProducto', function (Request $request) {
    return app(GateController::class)->handle($request, 'productos', 'eliminarProducto');
});
#http://localhost:9000/api/productos/eliminarProducto

Route::put('/productos/modificarProducto', function (Request $request) {
    return app(GateController::class)->handle($request, 'productos', 'modificarProducto');
});
#http://localhost:9000/api/productos/modificarProducto

Route::get('/productos/verProducto', function (Request $request) {
    return app(GateController::class)->handle($request, 'productos', 'verProducto');
});
#http://localhost:9000/api/productos/verProducto



//CORREOS
#NO necesito una ruta para correos, ya que este se comunica directamente con pedidos,
#Es decir, al realizar un pedido el correo de confirmacion se manda automaticamente
#Por lo cual es solo necesario crearPedido para que microservicio correo funcione


//REPORTES 
// Reportes de productos
Route::get('/reportes/productos/pdf', function(Request $request) {
    return app(GateController::class)->handle($request, 'reportes', 'reporte/productos/pdf/');
});
#http://localhost:9000/api/reportes/productos/pdf

Route::get('/reportes/productos/excel', function(Request $request) {
    return app(GateController::class)->handle($request, 'reportes', 'reporte/productos/excel/');
});

// Reportes de pedidos
Route::get('/reportes/pedidos/pdf', function(Request $request) {
    return app(GateController::class)->handle($request, 'reportes', 'reporte/pedidos/pdf/');
});

Route::get('/reportes/pedidos/excel', function(Request $request) {
    return app(GateController::class)->handle($request, 'reportes', 'reporte/pedidos/excel/');
});

// Reportes de usuarios
Route::get('/reportes/usuarios/pdf', function(Request $request) {
    return app(GateController::class)->handle($request, 'reportes', 'reporte/usuarios/pdf/');
});

Route::get('/reportes/usuarios/excel', function(Request $request) {
    return app(GateController::class)->handle($request, 'reportes', 'reporte/usuarios/excel/');
});