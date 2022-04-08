<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipmentController;
use Illuminate\Support\Facades\Route;

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

//Authentication routers
Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Equipment routers
Route::prefix('equipments')->group(function() {

    Route::get('/', [EquipmentController::class, 'index']);

    Route::get('/{id}', [EquipmentController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function() {
        
        Route::post('/', [EquipmentController::class, 'store']);

        Route::put('/{id}', [EquipmentController::class, 'update']);

        Route::delete('/{id}', [EquipmentController::class, 'destroy']);

    });
});