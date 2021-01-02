<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;



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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
  
Route::group(['middleware' => 'auth.jwt'], function () {
 
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('items', [ItemsController::class, 'index']);
    Route::get('items/{id}', [ItemsController::class, 'show']);
    Route::post('items/create', [ItemsController::class, 'store']);
    Route::put('items/edit/{id}', [ItemsController::class, 'update']);
    Route::delete('item/delete/{id}', [ItemsController::class, 'destroy']);





  
});
