<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'users'], function() {

    Route::get('/list',[UsersController::class,'list']);

    Route::get('/detail',[UsersController::class,'detail']);

    Route::get('/delete',[UsersController::class,'delete']);

    Route::post('/update',[UsersController::class,'update']);

    Route::post('/add',[UsersController::class,'add']);
    

});

Route::group(['prefix' => 'roles'], function() {

    Route::get('/delete',[RolesController::class,'delete']);

    Route::post('/update',[RolesController::class,'update']);

    Route::post('/add',[RolesController::class,'add']);
    

});
