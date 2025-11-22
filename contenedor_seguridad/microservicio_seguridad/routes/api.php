<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GatewayController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/create_user', [AuthController::class,'create_user']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout']);
Route::post('/request_reset_token', [AuthController::class, 'request_reset_token']);
Route::post('/confirm_reset_password', [AuthController::class, 'confirm_reset_password']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sales', [GatewayController::class, 'index']); #ESTO NO HACE PARTE DE ESTA PRIMERA PARTE DEL PROYECTO
Route::post('/sales', [GatewayController::class, 'store']); #ESTO NO HACE PARTE DE ESTA PRIMERA PARTE DEL PROYECTO