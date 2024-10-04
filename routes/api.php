<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Thrubus\TarifController;
use App\Http\Controllers\Thrubus\UserController;
use App\Http\Controllers\TransaksiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/me',[UserController::class,'me']);

Route::get('/layanan',[TarifController::class,'getService']);

Route::prefix('user')->group(function () {
    Route::get('/pool',[UserController::class,'getPool']);
});

Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/refresh', [AuthController::class,'refresh']);
Route::post('/logout', [AuthController::class,'logout']);
